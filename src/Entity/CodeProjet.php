<?php

namespace App\Entity;

use App\Repository\CodeProjetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CodeProjetRepository::class)
 */
class CodeProjet
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
    private $code_projet;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_projet;

    /**
     * @ORM\OneToMany(targetEntity=Utilisateurs::class, mappedBy="code_projet")
     */
    private $user;

    /**
     * @ORM\Column(type="integer")
     */
    private $TVA;

    /**
     * @ORM\Column(type="integer")
     */
    private $TTC;

    public function __construct()
    {
        $this->date_projet = new \Datetime();
        $this->user = new ArrayCollection();
    }

    public function getDateProjet(): ?\DateTimeInterface
    {
        return $this->date_projet;
    }

    public function setDateProjet(\DateTimeInterface $date_projet): self
    {
        $this->date_projet = $date_projet;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodeProjet(): ?string
    {
        return $this->code_projet;
    }

    public function setCodeProjet(string $code_projet): self
    {
        $this->code_projet = $code_projet;

        return $this;
    }

    public function getTTC(): ?int
        {
            return $this->TTC;
        }
    
        public function setTTC(?int $TTC): self
        {
            $this->TTC= $TTC;
    
            return $this; 

        }

        public function getTVA(): ?int
        {
            return $this->TVA;
        }
    
        public function setTVA(?int $TVA): self
        {
            $this->TVA= $TVA;
    
            return $this; 
        }
    /**
     * @return Collection|Utilisateurs[]
     */
    public function getUser(): Collection
    {
        return $this->user;
    }
}