<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Cra
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
       /**
     * @ORM\Column(type="date")
     */
    private $date;


/**
 * @ORM\Column(type="string", length=255)
 */

    private $month;

/**
     * @ORM\ManyToOne(targetEntity=CodeProjet::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $codeProjet;

    
    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMonth(): ?String
    {
        return $this->month;
    }

    public function setMonth(string $month): self
    {
        $this->month = $month;

        return $this;
    }



    public function getCodeProjet(): ?CodeProjet
    {
        return $this->codeProjet;
    }

    public function setCodeProjet(?CodeProjet $codeProjet): self
    {
        $this->codeProjet = $codeProjet;

        return $this;
    }
    
}