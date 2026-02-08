<?php

namespace App\Infrastructure\Mapper;

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
        $seoData->setTitle($model->getTitle());
        $seoData->setMetaDescription($model->getMetaDescription());
        $seoData->setCanonicalUrl($model->getCanonicalUrl());
        $seoData->setMetaRobots($model->getMetaRobots());
        $seoData->setOgTitle($model->getOgTitle());
        $seoData->setOgDescription($model->getOgDescription());
        $seoData->setOgImage($model->getOgImage());
        $seoData->setOgType($model->getOgType());
        $seoData->setTwitterCard($model->getTwitterCard());
        $seoData->setInSitemap($model->isInSitemap());
        $seoData->setChangefreq($model->getChangefreq());
        $seoData->setPriority((string) $model->getPriority());
        $seoData->setIsNoIndex($model->isNoIndex());
        $seoData->setSchemaMarkup($model->getSchemaMarkup());
        $seoData->setBreadcrumbTitle($model->getBreadcrumbTitle());
        return $seoData;
    }


    public function toModel(SeoData $entity): SeoModel
    {
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

    public function toEntities(Collection $models): Collection
    {
        $entities = new ArrayCollection();
        foreach ($models as $model) {
            $entities->add($this->toEntity($model));
        }

        return $entities;
    }

    public function toModels(Collection $entities) : Collection
    {
        $models = new ArrayCollection();
        foreach ($entities as $entity) {
            $models->add($this->toModel($entity));
        }
        return $models;
    }
}
