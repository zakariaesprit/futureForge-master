<?php

namespace App\Entity;

use App\Repository\EvenementsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Categories;
use App\Entity\Evenements;

/**
 * @ORM\Entity(repositoryClass=EvenementsRepository::class)
 */
class Evenements
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     *  @Assert\NotBlank(message="Le nom de l'événement ne peut pas être vide")
     * @Assert\Length(max=255, maxMessage="Le nom de l'événement doit faire au maximum {{ limit }} caractères")
     */
    
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     *  @Assert\NotBlank(message="Le type de l'événement ne peut pas être vide")
     * @Assert\Length(max=255, maxMessage="Le type de l'événement doit faire au maximum {{ limit }} caractères")
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="La description de l'événement ne peut pas être vide")
     * @Assert\Length(max=255, maxMessage="La description de l'événement doit faire au maximum {{ limit }} caractères")
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(message="La date de l'événement ne peut pas être vide")
     * @Assert\GreaterThanOrEqual("today", message="La date de l'événement doit être égale ou supérieure à aujourd'hui")
     */
    private $date;

      /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Categories")
     * @ORM\JoinColumn(name="categorie_id", referencedColumnName="id")
     * @Assert\NotNull(message="La catégorie de l'événement ne peut pas être vide")
    
     */
    private $Categories_id;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

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

    public function getCategoriesId(): ?Categories
    {
        return $this->Categories_id;
    }

    public function setCategoriesId(?Categories $Categories_id): self
    {
        $this->Categories_id = $Categories_id;

        return $this;
    }
}
