<?php

namespace App\Entity;

use App\Repository\ModelDiagramSuccessRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ModelDiagramSuccessRepository::class)
 */
class ModelDiagramSuccess
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id; 

    /**
     * @ORM\ManytoOne(targetEntity="Course", inversedBy="models_diagrams_success")
     * @ORM\JoinColumn(name="course_id", referencedColumnName="id")
     */  
    private $course;

    /**
     * @ORM\ManyToOne(targetEntity="NActivity", inversedBy="models_diagrams_success")
     * @ORM\JoinColumn(name="nactivity_id", referencedColumnName="id")
     */
    private $nactivity;

    /**
     * @ORM\Column(type="json")
     */
    private $data = [];

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $archive;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getData(): ?array
    {
        $data = $this->data;
        
        $data[] = '';
//dando formato al json como array  ordenado
        return array_unique($data);
    }

    public function setData(array $data): self
    {
        $this->data = $data;

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
     * @return ModelDiagramSuccess
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
     * @return ModelDiagramSuccess
     */
    public function setNActivity(NActivity $nactivity = null)
    {
        $this->nactivity = $nactivity;
        return $this;
    }

    public function getArchive()
    {
        return $this->archive;
    }

    public function setArchive($archive)
    {
        $this->archive = $archive;

        return $this;
    }

}
