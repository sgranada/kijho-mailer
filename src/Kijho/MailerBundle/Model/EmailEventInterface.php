<?php
namespace Kijho\MailerBundle\Model;

/**
 */
interface EmailEventInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getSlug();
    
    /**
     * @return Template
     */
    public function getTemplate();

}
