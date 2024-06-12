<?php

namespace App\Entity;

use App\Repository\CourseRepository;
use App\Repository\ModelDiagramTestRepository;
use App\Repository\ModelDiagramSuccessRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=CourseRepository::class)
 */
class Course
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
     * @ORM\Column(type="datetime")
     */
    private $initialDate;

    
    /**
     * @ORM\Column(type="datetime")
     */
    private $fdate;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="courses",cascade={"all"})
     */
    private $members;


    /**
     * @ORM\OneToMany(targetEntity="ModelDiagramTest", mappedBy="course")
     */
    private $models_digrams_test;

     /**
     * @ORM\OneToMany(targetEntity="ModelDiagramSuccess", mappedBy="course")
     */
    private $models_digrams_success;

    public function __construct()
    {
        $this->members = new ArrayCollection();
        $this->models_digrams_test =new ArrayCollection(); 
        $this->models_digrams_success =new ArrayCollection(); 
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
    * @return mixed
    */
    public function getInitialDate():\DateTime
    {
        return $this->initialDate;
    }


    /**
    * @param mixed $initialDate
    */
    public function setInitialDate(\DateTime $initialDate): void
    {
        $this->initialDate = $initialDate;
    }

    
    /**
    * @return mixed
    */
    public function getfdate()
    {
        return $this->fdate;
    }

    /**
    * @param mixed $fdate
    */
    public function setfdate(\DateTime $fdate): void
    {
        $this->fdate = $fdate;
    }

    /**
     * @return Collection<int, User>
     */
    public function getMembers(): Collection
    { 
            return $this->members;
    }

    public function addMember(User $member): self
    {
        if (!$this->members->contains($member)) {
            $this->members[] = $member;
        }

        return $this;
    }

    public function removeMember(User $member): self
    {
        $this->members->removeElement($member);

        return $this;
    }

    /**
     * @return ModelDiagramTest
     */
    public function getModelsDiagramsTest(): ModelDiagramTest
    {
        return $this->models_digrams_test[0];
    }

    public function addModelDiagramTest(ModelDiagramTest $m): self
    {
        //if ($this->models_digrams_test==null) {
            $this->models_digrams_test[0] = $m;
        //}

        return $this;
    }

    public function removeModelDiagramTest(ModelDiagramTest $m): self
    {
        $this->models_digrams_test->removeElement($m);

        return $this;
    }

    /**
     * @return ModelDiagramSuccess
     */
    public function getModelDiagramSuccess(): ModelDiagramSuccess
    {
        return $this->models_digrams_success[0];
    }

    public function addModelDiagramSuccess(ModelDiagramSuccess $m): self
    {
        //if ($this->models_digrams_test==null) {
            $this->models_digrams_success[0] = $m;
        //}

        return $this;
    }

    public function removeModelDiagramSuccess(ModelDiagramSuccess $m): self
    {
        $this->models_digrams_success->removeElement($m);

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }

}
