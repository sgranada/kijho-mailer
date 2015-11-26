<?php

namespace Kijho\MailerBundle\Entity;

use Kijho\MailerBundle\Model\EmailEvent as BaseEmailEvent;
use Doctrine\ORM\Mapping as ORM;

/**
 * Email Event
 * @ORM\Table(name="email_event")
 * @ORM\Entity
 */
class EmailEvent extends BaseEmailEvent {

    /**
     * @ORM\Id
     * @ORM\Column(name="emev_id", type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;
    
    /**
     * Template al que esta asociado el correo
     * @ORM\ManyToOne(targetEntity="Kijho\MailerBundle\Entity\EmailTemplate")
     * @ORM\JoinColumn(name="emev_template", referencedColumnName="temp_id")
     */
    protected $template;
    
    function getId() {
        return $this->id;
    }
    
    function getTemplate() {
        return $this->template;
    }

    function setTemplate($template = null) {
        $this->template = $template;
    }

}
