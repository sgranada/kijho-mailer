<?php

namespace Kijho\MailerBundle\Services;

use Symfony\Component\HttpFoundation\RequestStack;
use Kijho\MailerBundle\Model\Email;
use Kijho\MailerBundle\Model\Template;
use Kijho\MailerBundle\Util\Util;

/*
 * EmailManager
 * Esta clase implementa metodos generalizados para la construccion y 
 * envio de correos electronicos en la aplicacion, los cuales pueden ser utilizados
 * como un servicio
 */

class EmailManager {

    private $mailer;
    private $request;
    private $container;
    private $em;

    public function __construct(RequestStack $requestStack, $container, $em) {
        $this->request = $requestStack->getCurrentRequest();
        $this->container = $container;
        $this->em = $em;
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
        $bodyHtml = '';

        if ($template->getLayout()) {
            $bodyHtml = $template->getLayout()->getHeader() . $email->getContent() . $template->getLayout()->getFooter();
        } else {
            $bodyHtml = $email->getContent();
        }

        if (!$this->mailer->getTransport()->isStarted()) {
            $this->mailer->getTransport()->start();
        }

        $message = $this->mailer->createMessage();
        $message->setSubject($subject);

        $message->setBody($bodyHtml, 'text/html', 'UTF8');

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

}
