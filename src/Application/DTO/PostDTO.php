<?php
declare(strict_types=1);
namespace App\Application\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class PostDTO
{


    #[Assert\NotBlank]
    #[Assert\Length(min: 10, max: 255 , minMessage: "Le titre doit faire au moins 10 caractères")]
    private string $title;

    #[Assert\NotBlank]
    private string $content;

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
}
