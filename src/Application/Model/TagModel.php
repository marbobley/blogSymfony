<?php

declare(strict_types=1);

namespace App\Application\Model;

use Symfony\Component\Validator\Constraints as Assert;

class TagModel
{
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 255)]
    private string $name;
    private string $slug;

    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }
    private int $id;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }
}
