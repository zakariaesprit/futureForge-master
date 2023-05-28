<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Reclamation
 *
 * @ORM\Table(name="reclamation", indexes={@ORM\Index(name="id", columns={"id_u"})})
 * @ORM\Entity
 */
class Reclamation
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_R", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idR;

    /**
     * @var string
     *
     * @ORM\Column(name="TypeR", type="string", length=255, nullable=false)
     */
    private $typer;

    /**
     * @var string
     *
     * @ORM\Column(name="DescriptionR", type="string", length=255, nullable=false)
     */
    private $descriptionr;

    /**
     * @var string
     *
     * @ORM\Column(name="Objet", type="string", length=255, nullable=false)
     */
    private $objet;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DateR", type="datetime", nullable=false)
     */
    private $dater;

    /**
     * @var string
     *
     * @ORM\Column(name="etat", type="string", length=255, nullable=false)
     * @Assert\Choice(choices={"en attente", "en cours", "résolue"}, message="L'état de la réclamation doit être 'en attente', 'en cours' ou 'résolue'.")

     */
    private $etat;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_u", referencedColumnName="id")
     * })
     */
    private $idU;

    public function getIdR(): ?int
    {
        return $this->idR;
    }

    public function getTyper(): ?string
    {
        return $this->typer;
    }

    public function setTyper(string $typer): self
    {
        $this->typer = $typer;

        return $this;
    }

    public function getDescriptionr(): ?string
    {
        return $this->descriptionr;
    }

    public function setDescriptionr(string $descriptionr): self
    {
        $this->descriptionr = $descriptionr;

        return $this;
    }

    public function getObjet(): ?string
    {
        return $this->objet;
    }

    public function setObjet(string $objet): self
    {
        $this->objet = $objet;

        return $this;
    }

    public function getDater(): ?\DateTimeInterface
    {
        return $this->dater;
    }

    public function setDater(\DateTimeInterface $dater): self
    {
        $this->dater = $dater;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getIdU(): ?User
    {
        return $this->idU;
    }

    public function setIdU(?User $idU): self
    {
        $this->idU = $idU;

        return $this;
    }


}
