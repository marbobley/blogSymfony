<?php

declare(strict_types=1);

namespace App\Tests\Infrastructure\Form;

use App\Domain\Enum\ChangeFreq;
use App\Domain\Enum\OgType;
use App\Domain\Enum\RobotsMode;
use App\Domain\Enum\TwitterCard;
use App\Domain\Model\SeoModel;
use App\Infrastructure\Form\SeoType;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Validator\Validation;

class SeoTypeTest extends TypeTestCase
{
    protected function getExtensions(): array
    {
        return [
            new ValidatorExtension(Validation::createValidator()),
        ];
    }

    public function testSubmitValidData(): void
    {
        $formData = [
            'pageIdentifier' => 'home',
            'core' => [
                'title' => 'Home Title',
                'metaDescription' => 'Home Description',
                'canonicalUrl' => 'https://example.com',
                'favicon' => 'https://example.com/favicon.ico',
                'metaRobots' => RobotsMode::INDEX_FOLLOW->value,
            ],
            'social' => [
                'ogTitle' => 'OG Title',
                'ogDescription' => 'OG Description',
                'ogImage' => 'https://example.com/image.jpg',
                'ogType' => OgType::WEBSITE->value,
                'twitterCard' => TwitterCard::SUMMARY_LARGE_IMAGE->value,
            ],
            'sitemap' => [
                'inSitemap' => '1',
                'changefreq' => ChangeFreq::DAILY->value,
                'priority' => 0.8,
            ],
            'meta' => [
                'isNoIndex' => '0',
                'breadcrumbTitle' => 'Home',
            ],
        ];

        $form = $this->factory->create(SeoType::class);
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());

        /** @var SeoModel $model */
        $model = $form->getData();
        $this->assertInstanceOf(SeoModel::class, $model);

        $this->assertEquals('home', $model->getPageIdentifier());
        $this->assertEquals('Home Title', $model->getCore()->getTitle());
        $this->assertEquals('https://example.com/favicon.ico', $model->getCore()->getFavicon());
        $this->assertEquals(RobotsMode::INDEX_FOLLOW, $model->getCore()->getMetaRobots());
        $this->assertEquals(ChangeFreq::DAILY, $model->getSitemap()->getChangefreq());
        $this->assertEquals(0.8, $model->getSitemap()->getPriority());
    }

    public function testEmptyDataFunctional(): void
    {
        $form = $this->factory->create(SeoType::class);
        $form->submit([]); // Submission vide pour tester empty_data

        $this->assertTrue($form->isSynchronized());

        /** @var SeoModel $model */
        $model = $form->getData();
        $this->assertInstanceOf(SeoModel::class, $model);
        $this->assertEquals('', $model->getPageIdentifier());
        $this->assertNull($model->getCore()->getTitle());
        $this->assertEquals(RobotsMode::INDEX_FOLLOW, $model->getCore()->getMetaRobots());
    }
}
