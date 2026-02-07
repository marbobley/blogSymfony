<?php

declare(strict_types=1);

namespace App\Domain\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity]
#[ORM\Table(name: "post")]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $title;

    #[Gedmo\Slug(fields: ['title'])]
    #[ORM\Column(length: 255, unique: true, nullable: true)]
    private ?string $slug = null;

    #[ORM\Column(type: "text")]
    private string $content;

    #[ORM\Column]
    private \DateTimeImmutable $createdAt;

    /**
     * @var Collection<int, Tag>
     */
    #[ORM\ManyToMany(targetEntity: Tag::class, inversedBy: "posts", cascade: ["persist"])]
    #[ORM\JoinTable(name: "post_tag")]
    private Collection $tags;

    public function __construct(string $title, string $content)
    {
        if (mb_strlen($title) > 255) {
            throw new \InvalidArgumentException('Le titre ne peut pas dépasser 255 caractères.');
        }

        $this->title = $title;
        $this->content = $content;
        $this->createdAt = new \DateTimeImmutable();
        $this->tags = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @return Collection<int, Tag>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): void
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
        }
    }

    public function removeTag(Tag $tag): void
    {
        $this->tags->removeElement($tag);
    }

    public function update(string $title, string $content): void
    {
        if (mb_strlen($title) > 255) {
            throw new \InvalidArgumentException('Le titre ne peut pas dépasser 255 caractères.');
        }

        $this->title = $title;
        $this->content = $content;
    }
}
