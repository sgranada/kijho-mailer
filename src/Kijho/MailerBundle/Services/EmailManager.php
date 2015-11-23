<?php

namespace Kijho\MailerBundle\Services;

use Symfony\Component\HttpFoundation\RequestStack;
use Kijho\MailerBundle\Model\Email;
use Kijho\MailerBundle\Model\Template;
use Kijho\MailerBundle\Util\Util;

/*
 * EmailManager
 */

class EmailManager {

    private $mailer;
    private $request;
    private $container;
    private $em;

    public function __construct(RequestStack $requestStack, $mailer, $container, $em) {
        $this->request = $requestStack->getCurrentRequest();
        $this->mailer = $mailer;
        $this->container = $container;
        $this->em = $em;
    }

    public function getExample() {
        return $this->request->getClientIp();
    }

    /**
     * Esta funcion permite realizar el envio del un correo electronico
     * @author Cesar Giraldo <cnaranjo@kijho.com> 23/11/2015
     * @param Email $email instancia del correo electronico a enviar
     */
    public function send(Email $email) {

        $subject = $email->getSubject();
        $recipientName = $email->getRecipientName();
        $recipientEmail = (array)json_decode($email->getMailTo());
        $bodyHtml = $email->getTemplate()->getLayout()->getHeader() . $email->getContent() . $email->getTemplate()->getLayout()->getFooter();
        //$bodyText = 'body text..';

        /* @var $mailer \Swift_Mailer */
        if (!$this->mailer->getTransport()->isStarted()) {
            $this->mailer->getTransport()->start();
        }

        /* @var $message \Swift_Message */
        $message = $this->mailer->createMessage();
        $message->setSubject($subject);

        $message->setBody($bodyHtml, 'text/html');
        //$message->addPart($bodyText, 'text/plain', 'UTF8');

        if (!is_array($recipientEmail)) {
            $message->addTo($recipientEmail, $recipientName);
        } else {
            
            foreach ($recipientEmail as $recipient) {
                $message->addTo($recipient, $recipientName);
            }
        }
        $message->setFrom(array($email->getMailFrom() => $email->getFromName()));

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
                $emailText[trim($email)] = trim($email);
            }
            //var_dump($emailText);die();
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

}
