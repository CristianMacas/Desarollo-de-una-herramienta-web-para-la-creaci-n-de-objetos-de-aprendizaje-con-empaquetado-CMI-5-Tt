<?php

namespace App\Entity;

use App\Repository\CodelineRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CodelineRepository::class)
 */
class Codeline
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $entry;

    /**
     * @ORM\ManyToMany(targetEntity=SuccessCode::class, mappedBy="codeline",cascade={"all"})
     */
    private $successCodes;

    /**
     * @ORM\ManyToMany(targetEntity=EvalCode::class, mappedBy="codeline",cascade={"all"})
     */
    private $evalCodes;

    public function __construct()
    {
        $this->successCodes = new ArrayCollection();
        $this->evalCodes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEntry(): ?string
    {
        return $this->entry;
    }

    public function setEntry(string $entry): self
    {
        $this->entry = $entry;

        return $this;
    }

    public function __toString()
    {
        
return $this->entry;

    }

    /**
     * @return Collection<int, SuccessCode>
     */
    public function getSuccessCodes(): Collection
    {
        return $this->successCodes;
    }

    public function addSuccessCode(SuccessCode $successCode): self
    {
        if (!$this->successCodes->contains($successCode)) {
            $this->successCodes[] = $successCode;
            $successCode->addCodeline($this);
        }

        return $this;
    }

    public function removeSuccessCode(SuccessCode $successCode): self
    {
        if ($this->successCodes->removeElement($successCode)) {
            $successCode->removeCodeline($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, EvalCode>
     */
    public function getEvalCodes(): Collection
    {
        return $this->evalCodes;
    }

    public function addEvalCode(EvalCode $evalCode): self
    {
        if (!$this->evalCodes->contains($evalCode)) {
            $this->evalCodes[] = $evalCode;
            $evalCode->addCodeline($this);
        }

        return $this;
    }

    public function removeEvalCode(EvalCode $evalCode): self
    {
        if ($this->evalCodes->removeElement($evalCode)) {
            $evalCode->removeCodeline($this);
        }

        return $this;
    }
}
