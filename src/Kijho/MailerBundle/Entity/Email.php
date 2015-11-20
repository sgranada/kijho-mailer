<?php

namespace Kijho\MailerBundle\Entity;

use Kijho\MailerBundle\Model\Email as BaseEmail;
use Doctrine\ORM\Mapping as ORM;

/**
 * Email
 * @ORM\Table(name="email")
 * @ORM\Entity
 */
class Email extends BaseEmail {

    /**
     * @ORM\Id
     * @ORM\Column(name="emai_id", type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;
    
    function getId() {
        return $this->id;
    }

}
