<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Model;

use App\Domain\Model\Post;
use PHPUnit\Framework\TestCase;

class PostTest extends TestCase
{
    public function testConstructorThrowsExceptionWhenTitleIsTooLong(): void
    {
        $longTitle = str_repeat('a', 256);
        $content = 'Contenu de test';

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Le titre ne peut pas dépasser 255 caractères.');

        new Post($longTitle, $content);
    }
}
