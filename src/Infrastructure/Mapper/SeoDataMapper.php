<?php

namespace App\Infrastructure\Mapper;

use App\Domain\Enum\ChangeFreq;
use App\Domain\Enum\OgType;
use App\Domain\Enum\RobotsMode;
use App\Domain\Enum\TwitterCard;
use App\Domain\Model\Component\CoreSeo;
use App\Domain\Model\Component\MetaSeo;
use App\Domain\Model\Component\SitemapSeo;
use App\Domain\Model\Component\SocialSeo;
use App\Domain\Model\SeoModel;
use App\Infrastructure\Entity\SeoData;
use App\Infrastructure\MapperInterface\SeoDataMapperInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class SeoDataMapper implements SeoDataMapperInterface
{

    public function toEntity(SeoModel $model): SeoData
    {
        $seoData = new SeoData();
        $seoData->setPageIdentifier($model->getPageIdentifier());

        $this->updateEntity($seoData, $model);

        return $seoData;
    }


    public function toModel(SeoData $entity): SeoModel
    {
        return new SeoModel(
            pageIdentifier: $entity->getPageIdentifier(),
            core: new CoreSeo(
                title: $entity->getCore()->getTitle(),
                metaDescription: $entity->getCore()->getMetaDescription(),
                canonicalUrl: $entity->getCore()->getCanonicalUrl(),
                metaRobots: RobotsMode::tryFrom($entity->getCore()->getMetaRobots()) ?? RobotsMode::INDEX_FOLLOW
            ),
            social: new SocialSeo(
                ogTitle: $entity->getSocial()->getOgTitle(),
                ogDescription: $entity->getSocial()->getOgDescription(),
                ogImage: $entity->getSocial()->getOgImage(),
                ogType: OgType::tryFrom($entity->getSocial()->getOgType()) ?? OgType::WEBSITE,
                twitterCard: TwitterCard::tryFrom($entity->getSocial()->getTwitterCard()) ?? TwitterCard::SUMMARY_LARGE_IMAGE
            ),
            sitemap: new SitemapSeo(
                inSitemap: $entity->getSitemap()->isInSitemap(),
                changefreq: ChangeFreq::tryFrom($entity->getSitemap()->getChangefreq()) ?? ChangeFreq::WEEKLY,
                priority: (float) $entity->getSitemap()->getPriority()
            ),
            meta: new MetaSeo(
                isNoIndex: $entity->getMeta()->isNoIndex(),
                schemaMarkup: $entity->getMeta()->getSchemaMarkup(),
                breadcrumbTitle: $entity->getMeta()->getBreadcrumbTitle()
            )
        );
    }

    public function toEntities(Collection $models): Collection
    {
        $entities = new ArrayCollection();
        foreach ($models as $model) {
            $entities->add($this->toEntity($model));
        }

        return $entities;
    }

    public function toModels(Collection $entities): Collection
    {
        $models = new ArrayCollection();
        foreach ($entities as $entity) {
            $models->add($this->toModel($entity));
        }
        return $models;
    }

    public function updateEntity(SeoData $entity, SeoModel $model): void
    {
        $entity->setPageIdentifier($model->getPageIdentifier());

        $entity->getCore()
            ->setTitle($model->getCore()->getTitle())
            ->setMetaDescription($model->getCore()->getMetaDescription())
            ->setCanonicalUrl($model->getCore()->getCanonicalUrl())
            ->setMetaRobots($model->getCore()->getMetaRobots()->value);

        $entity->getSocial()
            ->setOgTitle($model->getSocial()->getOgTitle())
            ->setOgDescription($model->getSocial()->getOgDescription())
            ->setOgImage($model->getSocial()->getOgImage())
            ->setOgType($model->getSocial()->getOgType()->value)
            ->setTwitterCard($model->getSocial()->getTwitterCard()->value);

        $entity->getSitemap()
            ->setInSitemap($model->getSitemap()->isInSitemap())
            ->setChangefreq($model->getSitemap()->getChangefreq()->value)
            ->setPriority((string) $model->getSitemap()->getPriority());

        $entity->getMeta()
            ->setIsNoIndex($model->getMeta()->isNoIndex())
            ->setSchemaMarkup($model->getMeta()->getSchemaMarkup())
            ->setBreadcrumbTitle($model->getMeta()->getBreadcrumbTitle());
    }
}
