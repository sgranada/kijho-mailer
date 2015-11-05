<?php

namespace Kijho\MailerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Kijho\MailerBundle\Model\Email as KijhoEmail;

/**
 * Entidad para almacenar los correos electronicos
 * @ORM\Table(name="kijho_email")
 * @ORM\Entity
 */
class Email extends KijhoEmail {

    /**
     * Identificador del email
     * @ORM\Column(name="mail_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    protected $id;

    function getId() {
        return $this->id;
    }

    public function __toString() {
        return $this->getSubject();
    }
}
