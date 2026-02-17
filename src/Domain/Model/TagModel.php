<?php

declare(strict_types=1);

namespace App\Domain\Model;

use InvalidArgumentException;
use Symfony\Component\Validator\Constraints as Assert;

class TagModel
{
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 255)]
    private string $name = '';
    private string $slug = '';
    private ?int $id = null;

    public function __construct(string $name = '', string $slug = '', ?int $id = null)
    {
        $this->name = $name;
        $this->slug = $slug;
        $this->id = $id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function setName(string $name): void
    {
        if (mb_strlen($name) < 2) {
            throw new InvalidArgumentException('Le nom du tag doit faire au moins 2 caractères');
        }
        $this->name = $name;
    }
}
