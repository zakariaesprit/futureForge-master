<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * ReservationBus
 *
 * @ORM\Table(name="reservation_bus")
 * @ORM\Entity
 * @UniqueEntity(fields={"nom", "prenom"}, message="The combination of nom and prenom must be unique")
 */
class ReservationBus
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_reservation_bus", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idReservationBus;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Regex(
     *     pattern="/^[a-zA-Z]+$/",
     *     message="The string must contain only letters"
     * )
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Regex(
     *     pattern="/^[a-zA-Z]+$/",
     *     message="The string must contain only letters"
     * )
     */
    private $prenom;

    /**
     * @var int
     *
     * @ORM\Column(name="num_place", type="integer", nullable=false)
     * @Assert\NotBlank()
     * @Assert\Range(min=1, max=54)
     */
    private $numPlace;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", length=255, nullable=false)
     * @Assert\NotBlank()
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="destination", type="string", length=255, nullable=false)
     * @Assert\NotBlank()
     */
    private $destination;

    public function getIdReservationBus(): ?int
    {
        return $this->idReservationBus;
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

    public function getNumPlace(): ?int
    {
        return $this->numPlace;
    }

    public function setNumPlace(int $numPlace): self
    {
        $this->numPlace = $numPlace;

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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getDestination(): ?string
    {
        return $this->destination;
    }

    public function setDestination(string $destination): self
    {
        $this->destination = $destination;

        return $this;
    }


}
