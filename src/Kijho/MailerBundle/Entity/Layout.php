<?php

namespace Kijho\MailerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Kijho\MailerBundle\Model\Layout as LayoutBase;

/**
 * @ORM\Table(name="kijho_layout")
 * @ORM\Entity
 */
class Layout extends LayoutBase {

    /**
     * Identificador del email
     * @ORM\Column(name="layo_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    protected $id;

    function getId() {
        return $this->id;
    }

    public function __toString() {
        return $this->getLanguageCode();
    }
}
