<?php

namespace Kijho\MailerBundle\Services;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Kijho\MailerBundle\Model\Email;
use Kijho\MailerBundle\Model\Template;
use Kijho\MailerBundle\Model\Settings;
use Kijho\MailerBundle\Util\Util;


/*
 * EmailManager
 * Esta clase implementa metodos generalizados para la construccion y 
 * envio de correos electronicos en la aplicacion, los cuales pueden ser utilizados
 * como un servicio
 */

class EmailManager {

    protected $mailer;
    protected $request;
    protected $container;
    protected $em;
    protected $templating;
    

    public function __construct(RequestStack $requestStack, ContainerInterface $container, $em) {
        $this->request = $requestStack->getCurrentRequest();
        $this->container = $container;
        $this->em = $em;
        $this->templating = $this->container->get('templating');
    }

    /**
     * Esta funcion permite realizar el envio del un correo electronico
     * @author Cesar Giraldo <cnaranjo@kijho.com> 23/11/2015
     * @param Email $email instancia del correo electronico a enviar
     */
    public function send(Email $email) {

        //verificamos que mailer se debe usar para el envio del correo
        $mailer = 'swiftmailer.mailer';
        $this->mailer = $this->container->get($mailer);

        $template = $email->getTemplate();
        if (!empty($template->getMailerSettings()) && $template->getMailerSettings() != 'default') {

            $mailer = 'swiftmailer.mailer.' . $template->getMailerSettings();
            $parameterName = $mailer . ".transport.name";
            if ($this->container->hasParameter($parameterName)) {
                $this->mailer = $this->container->get($mailer);
            }
        } else {
            $mailer = 'swiftmailer.mailer.default';
        }

        //instanciamos el mensaje (asunto, destinatario, contenido, etc)
        $subject = $email->getSubject();
        $recipientName = $email->getRecipientName();
        $recipientEmail = (array) json_decode($email->getMailTo());
        
        if (!$this->mailer->getTransport()->isStarted()) {
            $this->mailer->getTransport()->start();
        }

        $message = $this->mailer->createMessage();
        $message->setSubject($subject);

        if ($email->getMailCopyTo()) {
            $message->setBcc($email->getMailCopyTo());
        }

        $message->setBody($this->renderEmail($email), 'text/html', 'UTF8');

        //seteamos el o los destinatarios a quin va dirigido el correo
        if (!is_array($recipientEmail)) {
            $message->addTo($recipientEmail, $recipientName);
        } else {
            foreach ($recipientEmail as $recipient) {
                $message->addTo($recipient, $recipientName);
            }
        }

        //verificamos si el template tiene un email from, de lo contrario ponemos el del mailer
        if (!empty($email->getMailFrom())) {
            $message->setFrom(array($email->getMailFrom() => $email->getFromName()));
        } elseif ($this->container->hasParameter($mailer . '.transport.smtp.username')) {
            $message->setFrom(array($this->container->getParameter($mailer . '.transport.smtp.username') => $email->getFromName()));
        }

        //enviamos el correo
        $this->mailer->send($message);
        $this->mailer->getTransport()->stop();

        //marcamos el correo como enviado
        $email->setStatus(Email::STATUS_SENT);
        $email->setSentDate(Util::getCurrentDate());
        $this->em->persist($email);
        $this->em->flush();
    }

    /**
     * Esta funcion permite verificar si en una direccion de correo vienen
     * varios correos en linea, y permite construir el arreglo respectivo con
     * la lista de correos, si solo es uno, retorna el mismo correo
     * @author Cesar Giraldo <cnaranjo@kijho.com> 23/11/2015
     * @param string $emailText correo o correos a los que se quiere enviar
     * @return type emails filtrados, pueder ser un string o un arreglo de strings
     */
    private function buildArrayEmails($emailText) {

        $pos = strpos($emailText, ',');

        //buscamos si hay varios correos
        if ($pos !== false) {

            $emails = explode(',', $emailText);

            $emailText = array();
            foreach ($emails as $email) {
                if (!empty($email)) {
                    $emailText[trim($email)] = trim($email);
                }
            }
        }
        return $emailText;
    }

    /**
     * Esta funcion permite inicializar los datos de un correo electronico
     * @author Cesar Giraldo <cnaranjo@kijho.com> 23/11/2015
     * @param Template $template instancia del template medianete el cual se envia el correo
     * @param string $emailAddress direcion(es) de correo electronico
     * @return \Kijho\MailerBundle\Model\Email
     */
    public function composeEmail(Template $template, $emailAddress) {
        $emailStorage = $this->container->getParameter('kijho_mailer.storage')['email'];
        $email = new $emailStorage;
        $email->setTemplate($template);
        $email->setContent($template->getContentMessage());
        $email->setFromName($template->getFromName());
        $email->setGeneratedDate(Util::getCurrentDate());
        $email->setMailCopyTo($template->getCopyTo());
        $email->setMailFrom($template->getFromMail());

        //filtramos la direccion de correo para saber si vienen varias direcciones en una
        $emailAddress = $this->buildArrayEmails($emailAddress);
        $email->setMailTo(json_encode($emailAddress));

        $email->setStatus(Email::STATUS_PENDING);
        $email->setSubject($template->getSubject());

        return $email;
    }

    /**
     * Esta funcion permite obtener los mailers de la aplicacion
     * @author Cesar Giraldo <cnaranjo@kijho.com> 24/11/2015
     * @return array[string]
     */
    public function getMailers() {
        $mailers = array();
        if ($this->container->hasParameter('swiftmailer.mailers')) {
            $mailers = $this->container->getParameter('swiftmailer.mailers');
        }
        return $mailers;
    }

    /**
     * Esta funcion permite analizar la bolsa de parametros del swiftmailer
     * y obtener los valores de dichos parametros
     * @author Cesar Giraldo - Kijho Technologies <cnaranjo@kijho.com> 19/11/2015
     * @return array[string] arreglo con los parametros del swiftmailer
     */
    public function getSwiftMailerSettings() {

        $bag = $this->container->getParameterBag();

        $basicParameters = array(
            "transport.name",
            "delivery.enabled",
            "transport.smtp.encryption",
            "transport.smtp.port",
            "transport.smtp.host",
            "transport.smtp.username",
            "transport.smtp.password",
            "transport.smtp.auth_mode",
            "transport.smtp.timeout",
            "transport.smtp.source_ip",
            "memory.path",
            "spool.enabled",
            "plugin.impersonate",
            "single_address");

        $mailers = $this->getMailers();

        //aca recorremos los mailers y armamos el arreglo reemplazando el nombre del mailer
        $swiftParameters = array();
        foreach ($mailers as $mailer) {
            foreach ($basicParameters as $parameter) {
                array_push($swiftParameters, $mailer . "." . $parameter);
            }
        }

        $otherParameters = array(
            "swiftmailer.spool.enabled",
            "swiftmailer.delivery.enabled",
            "swiftmailer.single_address",
            "swiftmailer.mailers",
            "swiftmailer.default_mailer");

        $swiftParameters = array_merge($swiftParameters, $otherParameters);

        $mailerSettings = array();
        foreach ($swiftParameters as $swiftParameter) {
            if ($bag->has($swiftParameter)) {
                $mailerSettings[$swiftParameter] = $bag->get($swiftParameter);
            }
        }

        return $mailerSettings;
    }

    /**
     * Esta funcion permite obtener la instancia con las configuraciones
     * generales del envio de correos, si no se han creado, las crea atomaticamente
     * @author Cesar Giraldo - Kijho Technologies <cnaranjo@kijho.com> 26/11/2015
     * @return Settings objeto con las configuraciones del modulo de correos
     */
    public function getMailerSettings() {
        $settingsStorage = $this->container->getParameter('kijho_mailer.storage')['settings'];

        $settings = $this->em->getRepository($settingsStorage)->findAll();
        if (!$settings) {
            $settings = new $settingsStorage;
            $settings->setSendMode(Settings::SEND_MODE_INSTANTANEOUS);
            $settings->setLimitEmailAmount(Settings::DEFAULT_EMAIL_AMOUNT);
            $settings->setIntervalToSend(Settings::DEFAULT_TIME_SEND);
            $this->em->persist($settings);
            $this->em->flush();
        } else {
            $settings = $settings[0];
        }
        return $settings;
    }

    /**
     * Permite establecer si el envio de correos debe ser de manera instantanea o no
     * @author Cesar Giraldo - Kijho Technologies <cnaranjo@kijho.com> 26/11/2015
     * @return boolean indicando si el envio debe ser instantaneo o no
     */
    public function isSendInstantaneous() {
        $settings = $this->getMailerSettings();

        if ($settings->getSendMode() == Settings::SEND_MODE_INSTANTANEOUS) {
            return true;
        }
        return false;
    }

    /**
     * Eta funcion permite verificar el metodo de envio de correos (numero limite)
     * para realizar el envio si ya se cumplio el tope establecido en las
     * configuraciones del proyecto
     * @author Cesar Giraldo - Kijho Technologies <cnaranjo@kijho.com> 26/11/2015
     */
    public function verifyPendingEmails() {
        $settings = $this->getMailerSettings();

        if ($settings->getSendMode() == Settings::SEND_MODE_BY_EMAIL_AMOUNT) {
            $emailAmount = $settings->getLimitEmailAmount();

            $emailStorage = $this->container->getParameter('kijho_mailer.storage')['email'];

            $search = array('status' => Email::STATUS_PENDING);
            $order = array('generatedDate' => 'ASC');
            $pendingEmails = $this->em->getRepository($emailStorage)->findBy($search, $order);

            if (count($pendingEmails) >= $emailAmount) {
                foreach ($pendingEmails as $email) {
                    $this->send($email);
                }
            }
        }
    }

    /**
     * Esta funcion permite consultar cualquier evento de emails indicando
     * su identificador (slug) unico
     * @author Cesar Giraldo - Kijho Technologies <cnaranjo@kijho.com> 27/11/2015
     * @param string $slug
     * @return \Kijho\MailerBundle\Model\EmailEvent instancia del evento de email
     */
    public function getEmailEvent($slug) {
        $emailEventStorage = $this->container->getParameter('kijho_mailer.storage')['email_event'];
        $emailEvent = $this->em->getRepository($emailEventStorage)->findOneBySlug($slug);
        return $emailEvent;
    }

    /**
     * Esta funcion permite obtener el html del correo electronico que sera enviado
     * @author Cesar Giraldo - Kijho Technologies <cnaranjo@kijho.com> 27/11/2015
     * @param Email $email instancia del correo que sera enviado
     * @param type $entity instancia de la entidad asociada en el correo
     * @return string texto con el html del correo
     */
    public function renderEmail($email, $entity = null) {

        if ($entity) {
            //hacer la validacion de la entidad con el email
        }

        $html = $this->templating->render('KijhoMailerBundle:Email:emailView.html.twig', array(
            'email' => $email,
            'entity' => $entity
        ));

        return $html;
    }

}
