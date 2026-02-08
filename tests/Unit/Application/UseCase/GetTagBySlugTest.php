<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\UseCase;

use App\Application\Factory\TagModelFactory;
use App\Application\Model\TagModel;
use App\Application\Provider\TagProviderInterface;
use App\Application\UseCase\GetTagBySlug;
use PHPUnit\Framework\TestCase;

class GetTagBySlugTest extends TestCase
{

    public function testExecuteReturnsTagModel(): void
    {
        $tagProvider = $this->createMock(TagProviderInterface::class);

        $useCase = new GetTagBySlug($tagProvider);
        $tag = TagModelFactory::create(1, 'Symfony' , 'Symfony');

        $tagProvider->expects($this->once())
            ->method('findBySlug');

        $responseDTO = $useCase->execute('Symfony');

        $this->assertInstanceOf(TagModel::class, $responseDTO);
    }
}
