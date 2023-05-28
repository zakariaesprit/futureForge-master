<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * ReservationCovoiturage
 *
 * @ORM\Table(name="reservation_covoiturage")
 * @ORM\Entity
 * @UniqueEntity(fields={"nom", "prenom"}, message="The combination of nom and prenom must be unique")
 */
class ReservationCovoiturage
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_reservation", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idReservation;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=false)
     * @Assert\NotBlank()
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255, nullable=false)
     * @Assert\NotBlank()
     */
    private $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="pnt_rencontre", type="string", length=255, nullable=false)
     * @Assert\NotBlank()
     */
    private $pntRencontre;

    /**
     * @var string
     *
     * @ORM\Column(name="distination", type="string", length=255, nullable=false)
     * @Assert\NotBlank()
     */
    private $distination;

    /**
     * @var int
     *
     * @ORM\Column(name="nbr_place", type="integer", nullable=false)
     * @Assert\NotBlank()
     * @Assert\Range(min=1, max=4)
     */
    private $nbrPlace;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", length=30, nullable=false)
     * @Assert\NotBlank()
     */
    private $date;

    public function getIdReservation(): ?int
    {
        return $this->idReservation;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getPntRencontre(): ?string
    {
        return $this->pntRencontre;
    }

    public function setPntRencontre(string $pntRencontre): self
    {
        $this->pntRencontre = $pntRencontre;

        return $this;
    }

    public function getDistination(): ?string
    {
        return $this->distination;
    }

    public function setDistination(string $distination): self
    {
        $this->distination = $distination;

        return $this;
    }

    public function getNbrPlace(): ?int
    {
        return $this->nbrPlace;
    }

    public function setNbrPlace(int $nbrPlace): self
    {
        $this->nbrPlace = $nbrPlace;

        return $this;
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


}
