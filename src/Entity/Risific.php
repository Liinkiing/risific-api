<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RisificRepository")
 */
class Risific
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     * @ORM\Column(type="uuid")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="create")
     * @Groups({"api"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="string", length=191)
     * @Groups({"api"})
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=191)
     * @Gedmo\Slug(fields={"title"})
     * @Groups({"api"})
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Chapter", mappedBy="risific", orphanRemoval=true, cascade={"persist"})
     * @ORM\OrderBy({"position": "ASC"})
     * @Groups({"api"})
     */
    private $chapters;

    /**
     * @ORM\Column(type="integer")
     */
    private $chaptersCount = 0;

    public function __construct()
    {
        $this->chapters = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
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

    /**
     * @return Collection|Chapter[]
     */
    public function getChapters(): Collection
    {
        return $this->chapters;
    }

    public function addChapter(Chapter $chapter): self
    {
        if (!$this->chapters->contains($chapter)) {
            $this->chapters[] = $chapter;
            $this->increaseChaptersCount();
            $chapter->setRisific($this);
        }

        return $this;
    }

    public function removeChapter(Chapter $chapter): self
    {
        if ($this->chapters->contains($chapter)) {
            $this->chapters->removeElement($chapter);
            $this->decreaseChaptersCount();
            // set the owning side to null (unless already changed)
            if ($chapter->getRisific() === $this) {
                $chapter->setRisific(null);
            }
        }

        return $this;
    }

    public function getChaptersCount(): ?int
    {
        return $this->chaptersCount;
    }

    public function setChaptersCount(int $chaptersCount): self
    {
        $this->chaptersCount = $chaptersCount;

        return $this;
    }

    public function increaseChaptersCount(): self
    {
        $this->chaptersCount++;

        return $this;
    }

    public function decreaseChaptersCount(): self
    {
        $this->chaptersCount--;

        return $this;
    }

}
