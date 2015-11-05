<?php
namespace Kijho\MailerBundle\Model;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Layout implements LayoutInterface {

    /**
     * @ORM\Column(name="layo_header", type="integer")
     * @var string
     */
    protected $header;

    /**
     * @var string
     * @ORM\Column(name="layo_footer", type="integer")
     */
    protected $footer;

    /**
     * @var string
     * @ORM\Column(name="layo_language_code", type="integer")
     */
    protected $languageCode;
    
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


}
