<?php
namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class DayDetails
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\ManyToOne(targetEntity=Cra::class)
     * @ORM\JoinColumn(name="cra_id", referencedColumnName="id", nullable=false)
     */
    private $cra;
    /**
     * @ORM\ManyToOne(targetEntity=CodeProjet::class)
     * @ORM\JoinColumn(name="proj_id", referencedColumnName="id", nullable=false)
     */
    private $proj;
   /**
     * @ORM\Column(type="string")
     */
    private $daysworked;
   /**
     * @ORM\Column(type="date")
     */
    private $day;
   /**
     * @ORM\Column(type="string")
     */
    private $work;
   /**
     * @ORM\Column(type="string")
     */
    private $hours;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCRAId(): ?Cra
    {
        return $this->cra;
    }

    public function setCRAId(?Cra $cra): self
    {
        $this->cra = $cra;

        return $this;
    }

    public function getProjetID(): ?CodeProjet
    {
        return $this->proj;
    }

    public function setProjetID(?CodeProjet $proj): self
    {
        $this->proj = $proj;

        return $this;
    }

    public function getHours(): ?String
    {
        return $this->hours;
    }

    public function setHours(string $hours): self
    {
        $this->hours = $hours;

        return $this;
    }

    public function getWorkDone(): ?string
    {
        return $this->work;
    }

    public function setWorkDone(?string $work): self
    {
        $this->work = $work;

        return $this;
    }

    public function getDaysWorked(): ?string
    {
        return $this->daysworked;
    }

    public function setDaysWorked(?string $daysworked): self
    {
        $this->daysworked = $daysworked;

        return $this;
    }
    
    public function getDay(): ?DateTime
    {
        return $this->day;
    }

    public function setDay(?DateTime $day): self
    {
        $this->day = $day;

        return $this;
    }
}