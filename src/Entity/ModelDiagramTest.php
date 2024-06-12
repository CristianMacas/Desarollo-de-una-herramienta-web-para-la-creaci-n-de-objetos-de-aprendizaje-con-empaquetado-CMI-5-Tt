<?php

namespace App\Entity;

use App\Repository\ModelDiagramTestRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ModelDiagramTestRepository::class)
 */
class ModelDiagramTest
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
    private $action;


    /**
     * @ORM\ManytoOne(targetEntity="Course", inversedBy="models_digrams_test")
     * @ORM\JoinColumn(name="course_id", referencedColumnName="id")
     */    
    private $course;

   /**
     * @ORM\ManyToOne(targetEntity="NActivity", inversedBy="models_digrams_test")
     * @ORM\JoinColumn(name="nactivity_id", referencedColumnName="id")
     */
    private $nactivity;


    /**
     * @ORM\Column(type="json")
     */
    private $data = [];
    
    
    /**
     * @ORM\Column(type="datetime")
     */
    private $Fecha;


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
     * @return ModelDiagramTest
     */
    public function setCourse(Course $course)
    {

        $this->course = $course;
        $course->addModelDiagramTest($this);
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
     * @return ModelDiagramTest
     */
    public function setNActivity(NActivity $nactivity)
    {
        $this->nactivity = $nactivity;
        $nactivity->addModelDiagramTest($this);
        return $this;
    }


    public function getAction(): ?string
    {
        return $this->action;
    }
    
    public function setAction(string $action): self
    {
        $this->action = $action;
    
        return $this;
    }

    /**
    * @return mixed
    */
    public function getFecha():\DateTime
    {
        return $this->Fecha;
    }


    /**
    * @param mixed $initialDate
    */
    public function setFecha(): void
    {
        date_default_timezone_set('US/Central');
        $this->Fecha = new \DateTime(date("Y-m-d H:i:s"));
    }

}
