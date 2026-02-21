<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\UseCase;

use App\Domain\Provider\TagProviderInterface;
use App\Domain\UseCase\Tag\DeleteTag;
use App\Infrastructure\Entity\Tag;
use PHPUnit\Framework\TestCase;

class DeleteTagTest extends TestCase
{
    public function testExecuteDeletesTag(): void
    {
        $tagProvider = $this->createMock(TagProviderInterface::class);
        $useCase = new DeleteTag($tagProvider);
        $tag = new Tag('Symfony');

        $tagProvider->expects($this->once())
            ->method('delete');

        $useCase->execute(1);
    }
}
