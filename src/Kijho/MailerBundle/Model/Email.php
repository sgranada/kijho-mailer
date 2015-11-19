<?php
namespace Kijho\MailerBundle\Model;

class Email implements EmailInterface {

    /**
     * @var string
     */
    protected $subject;

    /**
     * @var string
     */
    protected $content;

    /**
     * @var \DateTime
     */
    protected $sentDate;
    
    /**
     * {@inheritDoc}
     */
    public function getContent() {
        return $this->content;
    }

    /**
     * {@inheritDoc}
     */
    public function getSentDate() {
        return $this->sentDate;
    }

    /**
     * {@inheritDoc}
     */
    public function getSubject() {
        return $this->subject;
    }

    /**
     * 
     * @param string $subject
     */
    function setSubject($subject) {
        $this->subject = $subject;
    }

    /**
     * 
     * @param string $content
     */
    function setContent($content) {
        $this->content = $content;
    }

    /**
     * 
     * @param \DateTime $sentDate
     */
    function setSentDate(\DateTime $sentDate) {
        $this->sentDate = $sentDate;
    }

    
    

}
