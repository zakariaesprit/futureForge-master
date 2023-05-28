<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Conversation
 *
 * @ORM\Table(name="conversation", indexes={@ORM\Index(name="idexpediteur", columns={"idexpediteur"}), @ORM\Index(name="idrecipient", columns={"idrecipient"})})
 * @ORM\Entity
 */
class Conversation
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

    /**
     * @var string|null
     *
     * @ORM\Column(name="pseudo1", type="string", length=50, nullable=true, options={"default"="NULL"})
     */
    private $pseudo1 = 'NULL';

    /**
     * @var string|null
     *
     * @ORM\Column(name="pseudo2", type="string", length=50, nullable=true, options={"default"="NULL"})
     */
    private $pseudo2 = 'NULL';

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPseudo1(): ?string
    {
        return $this->pseudo1;
    }

    public function setPseudo1(?string $pseudo1): self
    {
        $this->pseudo1 = $pseudo1;

        return $this;
    }

    public function getPseudo2(): ?string
    {
        return $this->pseudo2;
    }

    public function setPseudo2(?string $pseudo2): self
    {
        $this->pseudo2 = $pseudo2;

        return $this;
    }


}
