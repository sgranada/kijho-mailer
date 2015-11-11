<?php
namespace Kijho\MailerBundle\Model;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 */
class Layout implements LayoutInterface {

    const LANG_ES = 'es';
    const LANG_EN = 'en';
    
    /**
     * @var string
     * @ORM\Column(name="layo_name", type="string")
     * @Assert\NotBlank()
     */
    protected $name;
    
    /**
     * @var string
     * @ORM\Column(name="layo_header", type="text")
     * @Assert\NotBlank()
     */
    protected $header;

    /**
     * @var string
     * @ORM\Column(name="layo_footer", type="text")
     * @Assert\NotBlank()
     */
    protected $footer;

    /**
     * @var string
     * @ORM\Column(name="layo_language_code", type="string")
     * @Assert\NotBlank()
     */
    protected $languageCode;
    
    /**
     * @var \DateTime
     * @ORM\Column(name="layo_creation_date", type="datetime")
     * @Assert\NotBlank()
     */
    protected $creationDate;
    
    public function __construct() {
        
    }
    
    /**
     * {@inheritDoc}
     */
    public function getFooter() {
        return $this->footer;
    }

    /**
     * {@inheritDoc}
     */
    public function getHeader() {
        return $this->header;
    }

    /**
     * {@inheritDoc}
     */
    public function getLanguageCode() {
        return $this->languageCode;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getCreationDate() {
        return $this->creationDate;
    }

    /**
     * {@inheritDoc}
     */
    public function getName() {
        return $this->name;
    }

    /**
     * 
     * @param string $header
     */
    function setHeader($header) {
        $this->header = $header;
    }

    /**
     * 
     * @param string $footer
     */
    function setFooter($footer) {
        $this->footer = $footer;
    }

    /**
     * 
     * @param string $languageCode
     */
    function setLanguageCode($languageCode) {
        $this->languageCode = $languageCode;
    }
    
    /**
     * 
     * @param string $name
     */
    function setName($name) {
        $this->name = $name;
    }

    /**
     * 
     * @param \DateTime $creationDate
     */
    function setCreationDate(\DateTime $creationDate) {
        $this->creationDate = $creationDate;
    }
    
    public function __toString() {
        return $this->getName()." (".strtoupper($this->getLanguageCode()).")";
    }
}
