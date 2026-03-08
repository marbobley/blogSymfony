<?php

declare(strict_types=1);

namespace App\Domain\Model;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use InvalidArgumentException;
use Symfony\Component\Validator\Constraints as Assert;
use function mb_strlen;

class PostModel extends BaseModelAbstract
{
    #[Assert\NotBlank]
    #[Assert\Length(min: 10, max: 255, minMessage: 'Le titre doit faire au moins 10 caractères')]
    private string $title = '';
    private string $subTitle = '';
    #[Assert\NotBlank]
    private string $content = '';

    public const DEFAULT_CONTENT = <<<HTML
<article class="post-content">
    <header class="post-content-header">
        <h1 class="post-content-title">Titre de l'article</h1>
    </header>
    <section class="post-content-section">
        <p class="post-content-paragraph">Introduction ou premier paragraphe de l'article...</p>
    </section>
    <section class="post-content-section">
        <h2 class="post-content-subtitle">Sous-titre de section</h2>
        <p class="post-content-paragraph">Contenu de la section...</p>
    </section>
</article>
HTML;

    private string $slug = '';
    private ?DateTimeImmutable $createdAt = null;
    private ?DateTimeImmutable $updatedAt = null;
    public bool $published = false;

    /**
     * @var Collection<int, TagModel>
     */
    private Collection $tags;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
    }

    public function getSubTitle(): string
    {
        return $this->subTitle;
    }

    public function setSubTitle(string $subTitle): void
    {
        $this->subTitle = $subTitle;
    }

    public function isPublished(): bool
    {
        return $this->published;
    }

    public function publish(): void
    {
        $this->published = true;
    }

    public function unpublish(): void
    {
        $this->published = false;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?DateTimeImmutable $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function setSlug(?string $slug): void
    {
        $this->slug = $slug;
    }

    public function getSlug(): string
    {
        return $this->slug;
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

    /**
     * @throws InvalidArgumentException
     */
    public function setTitle(string $title): void
    {
        if (mb_strlen($title) < 10) {
            throw new InvalidArgumentException('Le titre doit faire au moins 10 caractères');
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
}
