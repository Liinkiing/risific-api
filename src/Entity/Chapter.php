<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;


/**
 * @ApiResource(
 *     attributes={"order"={"position": "ASC"}}
 * )
 * @ApiFilter(OrderFilter::class, properties={"position"})
 * @ORM\Entity(repositoryClass="App\Repository\ChapterRepository")
 */
class Chapter
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     * @ORM\Column(type="uuid")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"api"})
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"api"})
     * @Gedmo\Slug(fields={"title"})
     */
    private $slug;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"api"})
     */
    private $body;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Risific", inversedBy="chapters")
     * @ORM\JoinColumn(nullable=false)
     */
    private $risific;

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="create")
     * @Groups({"api"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"api"})
     */
    private $position = 1;

    public function getId()
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(?string $body): self
    {
        $this->body = $body;

        return $this;
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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }
}
