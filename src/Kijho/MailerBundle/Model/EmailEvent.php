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
    
    /**
     * @var string
     * @ORM\Column(name="emev_template_slug", type="string", nullable=true)
     */
    protected $templateSlug;

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
    
    /**
     * {@inheritDoc}
     */
    function getTemplateSlug() {
        return $this->templateSlug;
    }

    /**
     * 
     * @param string $templateSlug
     */
    function setTemplateSlug($templateSlug) {
        $this->templateSlug = $templateSlug;
    }


}
