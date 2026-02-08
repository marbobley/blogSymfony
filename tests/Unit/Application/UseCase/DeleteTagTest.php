<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\UseCase;

use App\Application\Provider\TagProviderInterface;
use App\Application\UseCase\DeleteTag;
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
