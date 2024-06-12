<?php

namespace App\Entity;

use App\Repository\PClassRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PClassRepository::class)
 */
class PClass
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @var Course
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Course")
     * @ORM\JoinColumn(name="course_id", referencedColumnName="id", nullable=false)
     */
    private $course;

   /**
     * @var NActivity
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\NActivity")
     * @ORM\JoinColumn(name="nactivity_id", referencedColumnName="id", nullable=false)
     */
    private $nactivity;


    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity=Atribute::class, inversedBy="pClasses",cascade={"all"})
     */
    private $atribute;

    /**
     * @ORM\ManyToMany(targetEntity=Operation::class, inversedBy="pClasses",cascade={"all"})
     */
    private $operation;

    public function __construct()
    {
        $this->atribute = new ArrayCollection();
        $this->operation = new ArrayCollection();
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

    public function __toString(){

return $this->name;

    }

    /**
     * @return Collection<int, Atribute>
     */
    public function getAtribute(): Collection
    {
        return $this->atribute;
    }

    public function addAtribute(Atribute $atribute): self
    {
        if (!$this->atribute->contains($atribute)) {
            $this->atribute[] = $atribute;
        }

        return $this;
    }

    public function removeAtribute(Atribute $atribute): self
    {
        $this->atribute->removeElement($atribute);

        return $this;
    }

    /**
     * @return Collection<int, Operation>
     */
    public function getOperation(): Collection
    {
        return $this->operation;
    }

    public function addOperation(Operation $operation): self
    {
        if (!$this->operation->contains($operation)) {
            $this->operation[] = $operation;
        }

        return $this;
    }

    public function removeOperation(Operation $operation): self
    {
        $this->operation->removeElement($operation);

        return $this;
    }



    /**
     * @return Course
     */
    public function getCourse()
    {
        return $this->course;
    }

    /**
     * @param Course $course
     * @return PClass
     */
    public function setCourse(Course $course = null)
    {
        $this->course = $course;
        return $this;
    }

    /**
     * @return NActivity
     */
    public function getNActivity()
    {
        return $this->nactivity;
    }

    /**
     * @param NActivity $nactivity
     * @return PClass
     */
    public function setNActivity(NActivity $nactivity = null)
    {
        $this->nactivity = $nactivity;
        return $this;
    }



}