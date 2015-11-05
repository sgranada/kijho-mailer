<?php
namespace Kijho\MailerBundle\Model;

class Layout implements LayoutInterface {

    /**
     * @var string
     */
    protected $header;

    /**
     * @var string
     */
    protected $footer;

    /**
     * @var string
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
