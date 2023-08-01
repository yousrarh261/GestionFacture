<?php

namespace App\Entity;

use App\Repository\UtilisateursRepository;
use App\Entity\Messages;
use App\Entity\Equipe;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use App\Entity\Ticket;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UtilisateursRepository::class)
 * @UniqueEntity(fields={"email"}, message="Cet email existe déjà")
 */
class Utilisateurs implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @var string
     * @ORM\Column(name="email", type="string", length=255)
     * @Assert\Email(
     *     message = "Vous devez renseigner une adresse e-mail valide",
     *     mode = "strict"
     * )
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = ["ROLE_USER"];
  /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $resetPasswordToken;
    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;



    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Ce champ ne peut pas être vide")
     * @Assert\Length(max=255, maxMessage="Ce champ ne peut pas dépasser {{ limit }} caractères")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $activation_token;


    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $enabled;

   /**
     * @ORM\Column(type="boolean")
     */
    private bool $isVerified;

 
    /**
     * @ORM\Column(type="boolean")
     */
    private $isValidByAdmin;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $photo;

    /**
     * @ORM\ManyToOne(targetEntity=Equipe::class, inversedBy="managers")
     */
    private $equipesGerer;

 
/**
 * @ORM\ManyToOne(targetEntity=Equipe::class, inversedBy="membres")
 */
private $equipe;






    public function __construct()
    
    {
        $this->tickets = new ArrayCollection();
        $this->ticketsClient = new ArrayCollection();
        $this->uploadedFiles = new ArrayCollection();
        $this->isVerified = false;
        $this->isValidByAdmin =false; 
        $this->responsables = new ArrayCollection();

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEquipe(?Equipe $equipe): self
    {
        $this->equipe = $equipe;
        return $this;
    }

    public function getEquipe(): ?Equipe
    {
        return $this->equipe;
    }


    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    public function getUsername()
    {
        return $this->email;
    }

    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getActivationToken(): ?string
    {
        return $this->activation_token;
    }

    public function setActivationToken(?string $activation_token): self
    {
        $this->activation_token = $activation_token;

        return $this;
    }

    public function getResetToken(): ?string
    {
        return $this->reset_token;
    }

    public function setResetToken(?string $reset_token): self
    {
        $this->reset_token = $reset_token;

        return $this;
    }

    public function isEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(?bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function getIsVerified(): ?bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    /**
     * @return Collection|Ticket[]
     */
    public function getTicketsClient(): Collection
    {
        return $this->ticketsClient;
    }

 



    public function getIsValidByAdmin(): ?bool
    {
        return $this->isValidByAdmin;
    }

    public function setIsValidByAdmin(?bool $isValidByAdmin): self
    {
        $this->isValidByAdmin = $isValidByAdmin;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getEquipesGerer(): ?Equipe
    {
        return $this->equipesGerer;
    }

    public function setEquipesGerer(?Equipe $equipesGerer): self
    {
        $this->equipesGerer = $equipesGerer;

        return $this;
    }

    /**
     * @return Collection|UploadFile[]
     */
    public function getUploadedFiles(): Collection
    {
        return $this->uploadedFiles;
    }




   

   


}