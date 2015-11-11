<?php

namespace Kijho\MailerBundle\Model;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 */
class Template implements TemplateInterface {

    /**
     * Constantes para los posibles estados del template
     */
    const STATUS_DISABLED = 0;
    const STATUS_ENABLED = 1;

    /**
     * @var string
     * @ORM\Column(name="temp_name", type="string")
     * @Assert\NotBlank()
     */
    protected $name;

    /**
     * @var integer
     * @ORM\Column(name="temp_status", type="integer")
     * @Assert\NotBlank()
     */
    protected $status;

    /**
     * @var string
     * @ORM\Column(name="temp_from_name", type="string", nullable=true)
     */
    protected $fromName;

    /**
     * @var string
     * @ORM\Column(name="temp_from_mail", type="string", nullable=true)
     */
    protected $fromMail;

    /**
     * @var string
     * @ORM\Column(name="temp_copy_to", type="string", nullable=true)
     */
    protected $copyTo;

    /**
     * @var string
     * @ORM\Column(name="temp_subject", type="string", nullable=true)
     */
    protected $subject;

    /**
     * @var string
     * @ORM\Column(name="temp_content_message", type="text", nullable=true)
     */
    protected $contentMessage;

    /**
     * @var \DateTime
     * @ORM\Column(name="temp_creation_date", type="datetime")
     */
    protected $creationDate;

    public function __construct() {
        
    }

    /**
     * @param string $name
     */
    function setName($name) {
        $this->name = $name;
    }

    /**
     * @param integer $status
     */
    function setStatus($status) {
        $this->status = $status;
    }

    /**
     * @param string $fromName
     */
    function setFromName($fromName) {
        $this->fromName = $fromName;
    }

    /**
     * @param string $fromMail
     */
    function setFromMail($fromMail) {
        $this->fromMail = $fromMail;
    }

    /**
     * @param string $copyTo
     */
    function setCopyTo($copyTo) {
        $this->copyTo = $copyTo;
    }

    /**
     * @param string $subject
     */
    function setSubject($subject) {
        $this->subject = $subject;
    }

    /**
     * @param string $contentMessage
     */
    function setContentMessage($contentMessage) {
        $this->contentMessage = $contentMessage;
    }

    /**
     * @param \DateTime $creationDate
     */
    function setCreationDate(\DateTime $creationDate) {
        $this->creationDate = $creationDate;
    }

    /**
     * {@inheritDoc}
     */
    public function getContentMessage() {
        return $this->contentMessage;
    }

    /**
     * {@inheritDoc}
     */
    public function getCopyTo() {
        return $this->copyTo;
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
    public function getFromMail() {
        return $this->fromMail;
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
    public function getLayout() {
        
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
     * Permite obtener en modo texto el estado del template
     * @param integer|null $status
     * @return string
     */
    public function getStatusDescription($status = null) {
        if (!$status) {
            $status = $this->getStatus();
        }

        $description = '';
        switch ($status) {
            case self::STATUS_DISABLED:
                $description = 'kijho_mailer.global.disabled';
                break;
            case self::STATUS_ENABLED:
                $description = 'kijho_mailer.global.enabled';
                break;
            default:
                break;
        }
        return $description;
    }
    
    public function __toString() {
        return $this->getName();
    }

}
