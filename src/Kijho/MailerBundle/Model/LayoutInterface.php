<?php
namespace Kijho\MailerBundle\Model;

/**
 */
interface LayoutInterface
{
    /**
     * @return string
     */
    public function getName();
    
    /**
     * @return string
     */
    public function getHeader();

    /**
     * @return string
     */
    public function getFooter();

    /**
     * @return string
     */
    public function getLanguageCode();
    
    /**
     * @return \DateTime
     */
    public function getCreationDate();

}
