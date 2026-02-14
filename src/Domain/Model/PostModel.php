<?php
declare(strict_types=1);
namespace App\Domain\Model;

use DateTimeImmutable;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class PostModel
{
    private ?int $id = null;

    #[Assert\NotBlank]
    #[Assert\Length(min: 10, max: 255 , minMessage: "Le titre doit faire au moins 10 caractères")]
    private string $title = '';
    private string $subTitle = '';

    public function getSubTitle(): string
    {
        return $this->subTitle;
    }

    public function setSubTitle(string $subTitle): void
    {
        $this->subTitle = $subTitle;
    }

    #[Assert\NotBlank]
    private string $content = '';
    private string $slug = '';
    private ?DateTimeImmutable $createdAt = null;
    private bool $published = false;

    public function isPublished(): bool
    {
        return $this->published;
    }

    public function setPublished(bool $published): void
    {
        $this->published = $published;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @var Collection<int, TagModel>
     */
    private Collection $tags;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        if (mb_strlen($title) < 10) {
            throw new \InvalidArgumentException("Le titre doit faire au moins 10 caractères");
        }
        $this->title = $title;
    }

    /**
     * @return Collection<int, TagModel>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(TagModel $tag): void
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
        }
    }

    public function removeTag(TagModel $tag): void
    {
        $this->tags->removeElement($tag);
    }

    /**
     * @param Collection<int, TagModel> $tags
     */
    public function addTags(Collection $tags): void
    {
        foreach ($tags as $tag) {
            $this->addTag($tag);
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }
}
