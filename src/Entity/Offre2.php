<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\Offre2Repository;
/**
 * Offre2
 *
 * @ORM\Table(name="offre2")
 * @ORM\Entity
 */
class Offre2
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=50, nullable=false)
     * @Assert\NotBlank()
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=50, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Length(min=10, max=30)
     */
    private $description;

    /**
     * @var float
     *
     * @ORM\Column(name="reduction", type="float", precision=10, scale=0, nullable=false)
     * @Assert\NotBlank()
     */
    private $reduction;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=50, nullable=false)
     * @Assert\NotBlank()
     */
    private $type;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateD", type="datetime", nullable=false)
     * @Assert\NotBlank()
     * @Assert\LessThanOrEqual(propertyPath="datef")
     */
    private $dated;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateF", type="date", nullable=false)
     * @Assert\NotBlank()
     * @Assert\GreaterThanOrEqual(propertyPath="dated")
     */
    private $datef;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getReduction(): ?float
    {
        return $this->reduction;
    }

    public function setReduction(float $reduction): self
    {
        $this->reduction = $reduction;

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

    public function getDated(): ?\DateTimeInterface
    {
        return $this->dated;
    }

    public function setDated(\DateTimeInterface $dated): self
    {
        $this->dated = $dated;

        return $this;
    }

    public function getDatef(): ?\DateTimeInterface
    {
        return $this->datef;
    }

    public function setDatef(?\DateTimeInterface $datef): self
    {
        $this->datef = $datef;

        return $this;
    }

    public function __toString()
    {
        return (string) $this->nom . ' | ' . $this->type;
    }


}
