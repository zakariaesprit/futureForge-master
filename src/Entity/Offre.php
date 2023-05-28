<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Offre
 *
 * @ORM\Table(name="offre", indexes={@ORM\Index(name="secondaryKeyEtudiant", columns={"id_user"})})
 * @ORM\Entity
 */
class Offre
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_offre", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idOffre;

    /**
     * @var string
     *
     * @ORM\Column(name="image_vehicule", type="string", length=255, nullable=false)
     */
    private $imageVehicule;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom_chauff", type="string", length=255, nullable=false)
     * @Assert\NotBlank()
     */
    private $prenomChauff;

    /**
     * @var string
     *
     * @ORM\Column(name="num_chauff", type="string", length=255, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 8,
     *      max = 8,
     *      minMessage = "The field must be exactly {{ limit }} digits long",
     *      maxMessage = "The field must be exactly {{ limit }} digits long"
     * )
     */
    private $numChauff;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_offre", type="datetime", nullable=false)
     * @Assert\NotBlank()
     */
    private $dateOffre;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="heure", type="time", nullable=false)
     * @Assert\NotBlank()
     */
    private $heure;

    /**
     * @var int
     *
     * @ORM\Column(name="prix_offre", type="integer", nullable=false)
     * @Assert\NotBlank()
     * @Assert\Range(min=5, max=30)
     */
    private $prixOffre;

    /**
     * @var string
     *
     * @ORM\Column(name="depart", type="string", length=255, nullable=false)
     * @Assert\NotBlank()
     */
    private $depart;

    /**
     * @var string
     *
     * @ORM\Column(name="destination", type="string", length=255, nullable=false)
     * @Assert\NotBlank()
     */
    private $destination;

    /**
     * @var int
     *
     * @ORM\Column(name="places_dispo", type="integer", nullable=false)
     * @Assert\NotBlank()
     * @Assert\Range(min=1, max=4)
     */
    private $placesDispo;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_user", referencedColumnName="id")
     * })
     */
    private $idUser;

    public function getIdOffre(): ?int
    {
        return $this->idOffre;
    }

    public function getImageVehicule(): ?string
    {
        return $this->imageVehicule;
    }

    public function setImageVehicule(string $imageVehicule): self
    {
        $this->imageVehicule = $imageVehicule;

        return $this;
    }

    public function getPrenomChauff(): ?string
    {
        return $this->prenomChauff;
    }

    public function setPrenomChauff(string $prenomChauff): self
    {
        $this->prenomChauff = $prenomChauff;

        return $this;
    }

    public function getNumChauff(): ?string
    {
        return $this->numChauff;
    }

    public function setNumChauff(string $numChauff): self
    {
        $this->numChauff = $numChauff;

        return $this;
    }

    public function getDateOffre(): ?\DateTimeInterface
    {
        return $this->dateOffre;
    }

    public function setDateOffre(\DateTimeInterface $dateOffre): self
    {
        $this->dateOffre = $dateOffre;

        return $this;
    }

    public function getHeure(): ?\DateTimeInterface
    {
        return $this->heure;
    }

    public function setHeure(\DateTimeInterface $heure): self
    {
        $this->heure = $heure;

        return $this;
    }

    public function getPrixOffre(): ?int
    {
        return $this->prixOffre;
    }

    public function setPrixOffre(int $prixOffre): self
    {
        $this->prixOffre = $prixOffre;

        return $this;
    }

    public function getDepart(): ?string
    {
        return $this->depart;
    }

    public function setDepart(string $depart): self
    {
        $this->depart = $depart;

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

    public function getPlacesDispo(): ?int
    {
        return $this->placesDispo;
    }

    public function setPlacesDispo(int $placesDispo): self
    {
        $this->placesDispo = $placesDispo;

        return $this;
    }

    public function getIdUser(): ?User
    {
        return $this->idUser;
    }

   public function setIdUser(?User $idUser): self
   {
      $this->idUser = $idUser;

        return $this;
    }

    public function __toString()
    {
        return (string) $this->prenomChauff . " | " . $this->numChauff;
    }


}
