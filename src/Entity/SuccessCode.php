<?php

namespace App\Entity;

use App\Repository\SuccessCodeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SuccessCodeRepository::class)
 */
class SuccessCode
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
     * @ORM\ManyToMany(targetEntity=Codeline::class, inversedBy="successCodes",cascade={"all"})
     */
    private $codeline;

    public function __construct()
    {
        $this->codeline = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Codeline>
     */
    public function getCodeline(): Collection
    {
        return $this->codeline;
    }

    public function addCodeline(Codeline $codeline): self
    {
        if (!$this->codeline->contains($codeline)) {
            $this->codeline[] = $codeline;
        }

        return $this;
    }

    public function removeCodeline(Codeline $codeline): self
    {
        $this->codeline->removeElement($codeline);

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
