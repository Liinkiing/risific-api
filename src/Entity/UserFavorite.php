<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserFavoriteRepository")
 */
class UserFavorite
{

    /**
     * @ORM\Id @ORM\ManyToOne(targetEntity="App\Entity\Risific")
     * @ORM\JoinColumn(nullable=false)
     */
    private $risific;

    /**
     * @ORM\Id @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="favorites")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    private $favoritedAt;

    public function __construct(User $user, Risific $risific)
    {
        $this->user = $user;
        $this->risific = $risific;
    }

    public function getRisific(): ?Risific
    {
        return $this->risific;
    }

    public function setRisific(?Risific $risific): self
    {
        $this->risific = $risific;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getFavoritedAt(): ?\DateTimeInterface
    {
        return $this->favoritedAt;
    }

    public function setFavoritedAt(\DateTimeInterface $favoritedAt): self
    {
        $this->favoritedAt = $favoritedAt;

        return $this;
    }
}
