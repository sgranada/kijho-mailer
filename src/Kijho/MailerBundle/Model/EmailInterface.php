<?php
namespace Kijho\MailerBundle\Model;

/**
 */
interface EmailInterface
{
    /**
     * @return string
     */
    public function getSubject();

    /**
     * @return string
     */
    public function getContent();

    /**
     * @return \DateTime
     */
    public function getSentDate();

}
