<?php

namespace App\Entity;

use App\Repository\NActivityRepository;
use App\Repository\ModelDiagramTestRepository;
use App\Entity\ModelDiagramSuccess;
use App\Repository\ModelDiagramSuccessRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity(repositoryClass=NActivityRepository::class)
 */
class NActivity
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
    private $title;

    /**
     * @ORM\Column(type="string", length=1500)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $place;

    /**
     * @var NTA
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\NTA")
     * @ORM\JoinColumn(name="nta_id", referencedColumnName="id", nullable=true)
     */
    private $nta;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ModelDiagramTest", mappedBy="nactivity")
     */
    private $models_diagrams_test;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ModelDiagramSuccess", mappedBy="nactivity")
     */
    private $models_diagrams_success;

    /**
     * @ORM\Column(type="string", length=1500)
     */
    private $tecsol;


    public function __construct()
    {
        $this->models_diagrams_test =new ArrayCollection();
        //$this->models_diagrams_success =new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getTecSol(): ?string
    {
        return $this->tecsol;
    }

    public function setTecSol(string $tecsol): self
    {
        $this->tecsol = $tecsol;

        return $this;
    }

    public function getPlace(): ?string
    {
        return $this->place;
    }

    public function setPlace(string $place): self
    {
        $this->place = $place;

        return $this;
    }

    public function __toString()
    {
        return $this->title;
    }

    /**
     * @return NTA
     */
    public function getNTA()
    {
        return $this->nta;
    }

    /**
     * @param NTA $nta
     * @return NActivity
     */
    public function setNTA(NTA $nta = null)
    {
        $this->nta = $nta;
        return $this;
    }

    /**
     * @return ModelDiagramTest
     */
    public function getModelsDiagramsTest(): ModelDiagramTest
    {
        return $this->models_diagrams_test[0];
    }

    public function addModelDiagramTest(ModelDiagramTest $m): self
    {
        //if ($this->models_digrams_test==null) {
            $this->models_diagrams_test[0] = $m;
        //}

        return $this;
    }

    public function removeModelDiagramTest(ModelDiagramTest $m): self
    {
        $this->models_diagrams_test->removeElement($m);

        return $this;
    }

    /**
     * @return ModelDiagramSuccess
     */
    public function getModelDiagramSuccess(): ModelDiagramSuccess
    {
        return $this->models_diagrams_success[0];
    }

    public function addModelDiagramSuccess(ModelDiagramSuccess $m): self
    {
        //if ($this->models_digrams_test==null) {
            $this->models_diagrams_success[0] = $m;
        //}

        return $this;
    }

    public function removeModelDiagramSuccess(ModelDiagramSuccess $m): self
    {
        $this->models_diagrams_success->removeElement($m);

        return $this;
    }

}
