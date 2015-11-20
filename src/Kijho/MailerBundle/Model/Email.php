<?php

namespace Kijho\MailerBundle\Model;

class Email implements EmailInterface {

    /**
     * @var \DateTime
     * @ORM\Column(name="emai_generated_date", type="datetime", nullable=true)
     */
    protected $generatedDate;

    /**
     * @var string
     * @ORM\Column(name="emai_mail_to", type="string")
     */
    protected $mailTo;

    /**
     * @var string
     * @ORM\Column(name="emai_copy_to", type="string", nullable=true)
     */
    protected $mailCopyTo;

    /**
     * @var string
     * @ORM\Column(name="emai_from_name", type="string", nullable=true)
     */
    protected $fromName;

    /**
     * @var string
     * @ORM\Column(name="emai_mail_from", type="string", nullable=true)
     */
    protected $mailFrom;

    /**
     * @var string
     * @ORM\Column(name="emai_subject", type="string", nullable=true)
     */
    protected $subject;

    /**
     * @var string
     * @ORM\Column(name="emai_content", type="text", nullable=true)
     */
    protected $content;

    /**
     * @var \DateTime
     * @ORM\Column(name="emai_sent_date", type="datetime", nullable=true)
     */
    protected $sentDate;

    /**
     * @var integer
     * @ORM\Column(name="emai_status", type="integer", nullable=true)
     */
    protected $status;

    /**
     * {@inheritDoc}
     */
    public function getContent() {
        return $this->content;
    }

    /**
     * {@inheritDoc}
     */
    public function getFromName() {
        return $this->fromName;
    }

    /**
     * {@inheritDoc}
     */
    public function getGeneratedDate() {
        return $this->generatedDate;
    }

    /**
     * {@inheritDoc}
     */
    public function getMailCopyTo() {
        return $this->mailCopyTo;
    }

    /**
     * {@inheritDoc}
     */
    public function getMailFrom() {
        return $this->mailFrom;
    }

    /**
     * {@inheritDoc}
     */
    public function getMailTo() {
        return $this->mailTo;
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
    public function getStatus() {
        return $this->status;
    }

    /**
     * {@inheritDoc}
     */
    public function getSubject() {
        return $this->subject;
    }

    /**
     * {@inheritDoc}
     */
    public function getTemplate() {
        
    }

    /**
     * @param \DateTime $generatedDate
     */
    function setGeneratedDate(\DateTime $generatedDate) {
        $this->generatedDate = $generatedDate;
    }

    /**
     * @param string $mailTo
     */
    function setMailTo($mailTo) {
        $this->mailTo = $mailTo;
    }

    /**
     * @param string $mailCopyTo
     */
    function setMailCopyTo($mailCopyTo) {
        $this->mailCopyTo = $mailCopyTo;
    }

    /**
     * @param string $fromName
     */
    function setFromName($fromName) {
        $this->fromName = $fromName;
    }

    /**
     * @param string $mailFrom
     */
    function setMailFrom($mailFrom) {
        $this->mailFrom = $mailFrom;
    }

    /**
     * @param string $subject
     */
    function setSubject($subject) {
        $this->subject = $subject;
    }

    /**
     * @param string $content
     */
    function setContent($content) {
        $this->content = $content;
    }

    /**
     * @param \DateTime $sentDate
     */
    function setSentDate(\DateTime $sentDate) {
        $this->sentDate = $sentDate;
    }

    /**
     * @param integer $status
     */
    function setStatus($status) {
        $this->status = $status;
    }

}
