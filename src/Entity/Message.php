<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Message
 *
 * @ORM\Table(name="message", indexes={@ORM\Index(name="fk_idrecipient", columns={"idrecipient"}), @ORM\Index(name="fk_idconversation", columns={"idconversation"}), @ORM\Index(name="fk_idexpediteur", columns={"idexpediteur"})})
 * @ORM\Entity
 */
class Message
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="date", type="datetime", nullable=true)
     */
    private $date = 'NULL';

    /**
     * @var string|null
     *
     * @ORM\Column(name="etat", type="string", length=20, nullable=true, options={"default"="NULL"})
     */
    private $etat = 'NULL';

    /**
     * @var string
     *
     * @ORM\Column(name="type_m", type="string", length=20, nullable=false)
     */
    private $typeM;

    /**
     * @var string
     *
     * @ORM\Column(name="contenu", type="string", length=600, nullable=false)
     */
    private $contenu;

    /**
     * @var int|null
     *
     * @ORM\Column(name="idconversation", type="integer", nullable=true, options={"default"="NULL"})
     */
    private $idconversation = NULL;

    /**
     * @var int
     *
     * @ORM\Column(name="idexpediteur", type="integer", nullable=false)
     */
    private $idexpediteur;

    /**
     * @var int
     *
     * @ORM\Column(name="idrecipient", type="integer", nullable=false)
     */
    private $idrecipient;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(?string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getTypeM(): ?string
    {
        return $this->typeM;
    }

    public function setTypeM(string $typeM): self
    {
        $this->typeM = $typeM;

        return $this;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): self
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getIdconversation(): ?int
    {
        return $this->idconversation;
    }

    public function setIdconversation(?int $idconversation): self
    {
        $this->idconversation = $idconversation;

        return $this;
    }

    public function getIdexpediteur(): ?int
    {
        return $this->idexpediteur;
    }

    public function setIdexpediteur(int $idexpediteur): self
    {
        $this->idexpediteur = $idexpediteur;

        return $this;
    }

    public function getIdrecipient(): ?int
    {
        return $this->idrecipient;
    }

    public function setIdrecipient(int $idrecipient): self
    {
        $this->idrecipient = $idrecipient;

        return $this;
    }


}
