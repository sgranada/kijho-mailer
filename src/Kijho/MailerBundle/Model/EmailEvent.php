<?php

namespace Kijho\MailerBundle\Model;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 */
class EmailEvent implements EmailEventInterface {

    /**
     * @var string
     * @ORM\Column(name="emev_name", type="string")
     * @Assert\NotBlank()
     */
    protected $name;

    /**
     * @var string
     * @ORM\Column(name="emev_slug", type="string", nullable=true)
     */
    protected $slug;

    public function __toString() {
        return $this->getName();
    }

    /**
     * {@inheritDoc}
     */
    public function getName() {
        return $this->name;
    }

    /**
     * {@inheritDoc}
     */
    public function getSlug() {
        return $this->slug;
    }

    public function getTemplate() {
        
    }
    
    /**
     * @param string $name
     */
    function setName($name) {
        $this->name = $name;
    }

    /**
     * @param string $slug
     */
    function setSlug($slug) {
        $this->slug = $slug;
    }
}
