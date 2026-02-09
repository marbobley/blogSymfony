<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\Adapter;

use App\Domain\Exception\EntityNotFoundException;
use App\Domain\Model\Component\CoreSeo;
use App\Domain\Model\Component\MetaSeo;
use App\Domain\Model\Component\SitemapSeo;
use App\Domain\Model\Component\SocialSeo;
use App\Domain\Model\SeoModel;
use App\Infrastructure\Adapter\SeoAdapter;
use App\Infrastructure\Entity\SeoData;
use App\Infrastructure\MapperInterface\SeoDataMapperInterface;
use App\Infrastructure\Repository\SeoDataRepositoryInterface;
use PHPUnit\Framework\TestCase;

class SeoAdapterTest extends TestCase
{
    private SeoDataRepositoryInterface $repository;
    private SeoDataMapperInterface $mapper;
    private SeoAdapter $adapter;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(SeoDataRepositoryInterface::class);
        $this->mapper = $this->createMock(SeoDataMapperInterface::class);
        $this->adapter = new SeoAdapter($this->repository, $this->mapper);
    }

    public function testFindByPageIdentifierReturnsModel(): void
    {
        $identifier = 'home';
        $entity = new SeoData();
        $model = new SeoModel(
            pageIdentifier: $identifier,
            core: new CoreSeo(),
            social: new SocialSeo(),
            sitemap: new SitemapSeo(),
            meta: new MetaSeo()
        );

        $this->repository->expects($this->once())
            ->method('findByPageIdentifier')
            ->with($identifier)
            ->willReturn($entity);

        $this->mapper->expects($this->once())
            ->method('toModel')
            ->with($entity)
            ->willReturn($model);

        $result = $this->adapter->findByPageIdentifier($identifier);

        $this->assertSame($model, $result);
    }

    public function testFindByPageIdentifierReturnsDefaultModelIfNotFound(): void
    {
        $identifier = 'unknown';

        $this->repository->expects($this->once())
            ->method('findByPageIdentifier')
            ->with($identifier)
            ->willReturn(null);

        $result = $this->adapter->findByPageIdentifier($identifier);
        $this->assertInstanceOf(SeoModel::class, $result);
        $this->assertEquals($identifier, $result->getPageIdentifier());
        $this->assertNull($result->getCore()->getTitle());
    }
}
