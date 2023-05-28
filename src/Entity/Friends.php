<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Friends
 *
 * @ORM\Table(name="friends")
 * @ORM\Entity
 */
class Friends
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_friendship", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idFriendship;

    /**
     * @var string
     *
     * @ORM\Column(name="username1", type="string", length=255, nullable=false)
     */
    private $username1;

    /**
     * @var string
     *
     * @ORM\Column(name="username2", type="string", length=255, nullable=false)
     */
    private $username2;

    /**
     * @var int
     *
     * @ORM\Column(name="valide_f", type="integer", nullable=false)
     */
    private $valideF;

    public function getIdFriendship(): ?int
    {
        return $this->idFriendship;
    }

    public function getUsername1(): ?string
    {
        return $this->username1;
    }

    public function setUsername1(string $username1): self
    {
        $this->username1 = $username1;

        return $this;
    }

    public function getUsername2(): ?string
    {
        return $this->username2;
    }

    public function setUsername2(string $username2): self
    {
        $this->username2 = $username2;

        return $this;
    }

    public function getValideF(): ?int
    {
        return $this->valideF;
    }

    public function setValideF(int $valideF): self
    {
        $this->valideF = $valideF;

        return $this;
    }


}
