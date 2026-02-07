<?php
declare(strict_types=1);
namespace App\Application\Model;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class PostModel
{
    private int $id;

    #[Assert\NotBlank]
    #[Assert\Length(min: 10, max: 255 , minMessage: "Le titre doit faire au moins 10 caractères")]
    private string $title;

    #[Assert\NotBlank]
    private string $content;

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

    public function addTags(Collection $tags): void
    {
        foreach ($tags as $tag) {
            $this->addTag($tag);
        }
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }
}
