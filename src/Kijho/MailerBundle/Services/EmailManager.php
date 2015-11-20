<?php

namespace Kijho\MailerBundle\Services;

use Symfony\Component\HttpFoundation\RequestStack;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class EmailManager {

    private $mailer;
    private $request;

    public function __construct(RequestStack $requestStack, $mailer) {
        $this->request = $requestStack->getCurrentRequest();
        $this->mailer = $mailer;
    }

    public function getExample() {
        return $this->request->getClientIp();
    }

    public function send($subject, $recipientName, $recipientEmail, $bodyHtml, $bodyText) {
        /* @var $mailer \Swift_Mailer */
        if (!$this->mailer->getTransport()->isStarted()) {
            $this->mailer->getTransport()->start();
        }

        /* @var $message \Swift_Message */
        $message = $this->mailer->createMessage();
        $message->setSubject($subject);

        $message->setBody($bodyHtml, 'text/html');
        $message->addPart($bodyText, 'text/plain', 'UTF8');

        $message->addTo($recipientEmail, $recipientName);
        $message->setFrom(array('example@gmail.com' => 'Chance'));

        $this->mailer->send($message);
        $this->mailer->getTransport()->stop();
    }

    public function composeEmail(Request $request) {
        $email = null;
        
        return $email;
    }

}
