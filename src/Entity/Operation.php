<?php

namespace App\Entity;

use App\Repository\OperationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OperationRepository::class)
 */
class Operation
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
    private $name;


    /**
     * @var Datatype
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Datatype")
     */
    private $datatype;

    /**
     * @ORM\ManyToMany(targetEntity=PClass::class, mappedBy="operation",cascade={"all"})
     */
    private $pClasses;

    /**
     * @ORM\ManyToMany(targetEntity=StudPClass::class, mappedBy="operation",cascade={"all"})
     */
    private $studPClasses;

 

    public function __construct()
    {
        $this->pClasses = new ArrayCollection();
        $this->studPClasses = new ArrayCollection();
      
    }





    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }



    /**
     * @return Datatype
     */
    public function getDatatype()
    {
        return $this->datatype;
    }


    public function setDatatype(Datatype $datatype = null)
    {
        $this->datatype = $datatype;

        return $this;


    }
    public function __toString(){
        return '+'.$this->name.'('.$this->datatype.')'.':'.$this->datatype;
    }

    /**
     * @return Collection<int, PClass>
     */
    public function getPClasses(): Collection
    {
        return $this->pClasses;
    }

    public function addPClass(PClass $pClass): self
    {
        if (!$this->pClasses->contains($pClass)) {
            $this->pClasses[] = $pClass;
            $pClass->addOperation($this);
        }

        return $this;
    }

    public function removePClass(PClass $pClass): self
    {
        if ($this->pClasses->removeElement($pClass)) {
            $pClass->removeOperation($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, StudPClass>
     */
    public function getStudPClasses(): Collection
    {
        return $this->studPClasses;
    }

    public function addStudPClass(StudPClass $studPClass): self
    {
        if (!$this->studPClasses->contains($studPClass)) {
            $this->studPClasses[] = $studPClass;
            $studPClass->addOperation($this);
        }

        return $this;
    }

    public function removeStudPClass(StudPClass $studPClass): self
    {
        if ($this->studPClasses->removeElement($studPClass)) {
            $studPClass->removeOperation($this);
        }

        return $this;
    }


}