<?php

declare(strict_types=1);

namespace App\Infrastructure\Twig\Component;

use App\Domain\Model\PostModel;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('PostCard')]
final class PostCard
{
    public PostModel $post;
    public bool $showActions = false;
}
