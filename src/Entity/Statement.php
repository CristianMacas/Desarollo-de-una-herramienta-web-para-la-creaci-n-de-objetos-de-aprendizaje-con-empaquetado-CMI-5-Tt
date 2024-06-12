<?php

namespace App\Entity;

use App\Repository\StatementRepository;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;

/**
 * @ORM\Entity(repositoryClass=StatementRepository::class)
 */
class Statement
{
    /**
     * @var \Ramsey\Uuid\UuidInterface
     *
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UuidGenerator::class)
     */
    protected $id;

    /**
     * @ORM\Column(type="object")
     */
    protected $actor;

    /**
     * @ORM\Column(type="object")
     */
    protected $verb;

    /**
     * @ORM\Column(type="object")
     */
    private $object;
    /**
     * @ORM\Column(type="object",nullable=true)
     */
    protected $result;

    /**
     * @ORM\Column(type="object", nullable=true)
     */
    protected $context;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $timestamp;

    /**
     * @ORM\Column(type="object", nullable=true)
     */
    private $authority;

    /**
     * @ORM\Column(type="object", nullable=true)
     */
    private $attachments;

    public function getId()
    {
        return $this->id;
    }

    public function getActor()
    {
        return $this->actor;
    }

    public function setActor($actor)
    {
        $this->actor = $actor;

        return $this;
    }

    public function getVerb()
    {
        return $this->verb;
    }

    public function setVerb($verb)
    {
        $this->verb = $verb;

        return $this;
    }

    public function getResult()
    {
        return $this->result;
    }

    public function setResult($result)
    {
        $this->result = $result;

        return $this;
    }

    public function getContext()
    {
        return $this->context;
    }

    public function setContext($context)
    {
        $this->context = $context;

        return $this;
    }

    public function getObject()
    {
        return $this->object;
    }

    public function setObject($object)
    {
        $this->object = $object;

        return $this;
    }

    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @ORM\PreUpdate
     * @throws \Exception
     * @param $timestamp
     */
    public function setTimestamp($timestamp)
    {
        
        $this->timestamp = $timestamp;
        return $this;
    }

    public function getAuthority()
    {
        return $this->authority;
    }

    public function setAuthority($authority)
    {
        $this->authority = $authority;

        return $this;
    }

    public function getAttachments()
    {
        return $this->attachments;
    }

    public function setAttachments($attachments)
    {
        $this->attachments = $attachments;

        return $this;
    }

}
