<?php

namespace Kijho\MailerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Email
 * @ORM\Table(name="kijho_email")
 * @ORM\Entity
 */
class Email
{
    /**
     * Identificador del email
     * @ORM\Column(name="mail_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * Asunto del email
     * @ORM\Column(name="mail_subject", type="string", nullable=true)
     */
    protected $subject;

    
    /**
     * Contenido del email
     * @ORM\Column(name="mail_content", type="text", nullable=true)
     */
    protected $content;

    function getId() {
        return $this->id;
    }

    function getSubject() {
        return $this->subject;
    }

    function getContent() {
        return $this->content;
    }

    function setSubject($subject) {
        $this->subject = $subject;
    }

    function setContent($content) {
        $this->content = $content;
    }

    public function __toString() {
        return $this->getSubject();
    }
}
