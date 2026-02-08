<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence;

use App\Domain\Model\SeoModel;
use App\Domain\Provider\SeoProviderInterface;
use App\Infrastructure\Entity\SeoData;
use Doctrine\ORM\EntityManagerInterface;

final readonly class DoctrineSeoProvider implements SeoProviderInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    public function findByPageIdentifier(string $identifier): ?SeoModel
    {
        /** @var SeoData|null $entity */
        $entity = $this->entityManager->getRepository(SeoData::class)->findOneBy(['pageIdentifier' => $identifier]);

        if (null === $entity) {
            return null;
        }

        return new SeoModel(
            pageIdentifier: $entity->getPageIdentifier(),
            title: $entity->getTitle(),
            metaDescription: $entity->getMetaDescription(),
            canonicalUrl: $entity->getCanonicalUrl(),
            metaRobots: $entity->getMetaRobots(),
            ogTitle: $entity->getOgTitle(),
            ogDescription: $entity->getOgDescription(),
            ogImage: $entity->getOgImage(),
            ogType: $entity->getOgType(),
            twitterCard: $entity->getTwitterCard(),
            inSitemap: $entity->isInSitemap(),
            changefreq: $entity->getChangefreq(),
            priority: (float) $entity->getPriority(),
            isNoIndex: $entity->isNoIndex(),
            schemaMarkup: $entity->getSchemaMarkup(),
            breadcrumbTitle: $entity->getBreadcrumbTitle()
        );
    }
}
