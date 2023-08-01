<?php

namespace App\Entity;

use App\Repository\EquipeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EquipeRepository::class)
 */
class Equipe
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
    private $nom_equipe;

    /**
     * @ORM\Column(type="date")
     */
    private $Date_equipe;

    /**
     * @ORM\ManyToOne(targetEntity=Utilisateurs::class, inversedBy="equipesGerer")
     */
    private $manager;

    /**
     * @ORM\OneToMany(targetEntity=Utilisateurs::class, mappedBy="equipe")
     */
    private $membre;

   

    public function __construct()

    {
        $this->Date_equipe = new \Datetime();
        $this->utilisateurs = new ArrayCollection();
        $this->membre = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomEquipe(): ?string
    {
        return $this->nom_equipe;
    }

    public function setNomEquipe(string $nom_equipe): self
    {
        $this->nom_equipe = $nom_equipe;

        return $this;
    }

    public function getDateEquipe(): ?\DateTimeInterface 
    {
        return $this->Date_equipe;
    }

    public function setDateEquipe(\DateTimeInterface $Date_equipe): self
    {
        $this->Date_equipe = $Date_equipe;

        return $this;
    }

    public function getManager(): ?Utilisateurs
    {
        return $this->manager;
    }

    public function setManager(?Utilisateurs $manager): self
    {
        $this->manager = $manager;

        return $this;
    }

    /**
     * @return Collection<int, Utilisateurs>
     */
    public function getMembre(): Collection
    {
        return $this->membre;
    }

    public function addMembre(Utilisateurs $membre): self
    {
        if (!$this->membre->contains($membre)) {
            $this->membre[] = $membre;
            $membre->setEquipe($this);
        }
    
        return $this;
    }
    

    public function removeMembre(Utilisateurs $membre): self
    {
        if ($this->membre->removeElement($membre)) {
            // set the owning side to null (unless already changed)
            if ($membre->getEquipe() === $this) {
                $membre->setEquipe(null);
            }
        }

        return $this;
    }

}
