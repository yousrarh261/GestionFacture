<?php

namespace App\Entity;

use App\Repository\CodeProjetRepository;
use App\Entity\CodeProjet;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=App\Repository\CalendrierRepository::class)
 */
class Calendrier{
    
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    
    /**
     * @ORM\ManyToOne(targetEntity=CodeProjet::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $codeProjet;
    
    /** 
     * @ORM\Column(type="float", nullable=true)
     */
    private $demiJournees;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $daysEntered;

    /**
     * @ORM\OneToMany(targetEntity=Utilisateurs::class, mappedBy="categorie",cascade={"persist", "remove"})
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $user;

    public function __construct()
    {
        $this->user = new ArrayCollection();
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function setCodeProjet(?CodeProjet $codeProjet): self
    {
        $this->codeProjet = $codeProjet;

        return $this;
    }

    public function getCodeProjet(): ?CodeProjet
    {
        return $this->codeProjet;
    }

    public function getDaysEntered(): ?int
    {
        return $this->daysEntered;
    }

    public function setDaysEntered(?int $daysEntered): self
    {
        $this->daysEntered = $daysEntered;

        return $this;
    }

    /**
     * @return Collection|Utilisateurs[]
     */
    public function getUtilisateurs(): Collection
    {
        return $this->utilisateurs;
    }
  
    public function getDemiJournees(): ?float
    {
        return $this->demiJournees;
    }

    public function setDemiJournees(?float $demiJournees): self
    {
        $this->demiJournees = $demiJournees;

        return $this;
    }
}
