<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ClientRepository::class)
 */
class Client
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
 
    /**
     * @ORM\Column(type="integer")
     */
    private $tel;

    /**
     * @ORM\ManyToOne(targetEntity=Utilisateurs::class)
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private $user_id;

    /**
     * @ORM\Column(type="string")
     */
    private $adresse;

    /**
     * @ORM\Column(type="string")
     */
    private $nom;


    public function getUserName(): ?Utilisateurs
    {
        return $this->user_id;
    }
    public function setUserName(?int $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }
    public function setName(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }
    public function getName(): ?string
        {
            return $this->nom;
        }
    
    public function setAdresse(?string $adresse): self
    {
        $this->adresse = $adresse;

        return $this; 

    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }
    
    public function getTel(): ?int
    {
        return $this->tel;
    }

    public function setTel(?int $tel): self
    {
        $this->tel= $tel;

        return $this; 
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    
}