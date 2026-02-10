<?php

declare(strict_types=1);

namespace App\Tests\Infrastructure\Form;

use App\Domain\Enum\ChangeFreq;
use App\Domain\Enum\OgType;
use App\Domain\Enum\RobotsMode;
use App\Domain\Enum\TwitterCard;
use App\Domain\Model\Component\CoreSeo;
use App\Domain\Model\Component\MetaSeo;
use App\Domain\Model\Component\SitemapSeo;
use App\Domain\Model\Component\SocialSeo;
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

    public function testDataPopulatedFromModel(): void
    {
        $existingModel = new SeoModel(
            pageIdentifier: 'edit_page',
            core: new CoreSeo(
                title: 'Existing Title',
                metaDescription: 'Existing Description',
                metaRobots: RobotsMode::NOINDEX_NOFOLLOW
            ),
            social: new SocialSeo(ogTitle: 'Existing OG'),
            sitemap: new SitemapSeo(priority: 0.3),
            meta: new MetaSeo(isNoIndex: true)
        );

        $form = $this->factory->create(SeoType::class, $existingModel);

        $view = $form->createView();

        $this->assertEquals('edit_page', $view->children['pageIdentifier']->vars['value']);
        $this->assertEquals('Existing Title', $view->children['core']->children['title']->vars['value']);
        $this->assertEquals('Existing Description', $view->children['core']->children['metaDescription']->vars['value']);
        $this->assertEquals(RobotsMode::NOINDEX_NOFOLLOW->value, $view->children['core']->children['metaRobots']->vars['value']);
        $this->assertEquals('Existing OG', $view->children['social']->children['ogTitle']->vars['value']);
        $this->assertEquals(0.3, $view->children['sitemap']->children['priority']->vars['value']);
        $this->assertEquals('1', $view->children['meta']->children['isNoIndex']->vars['value']);
    }

    public function testSubmitUpdateData(): void
    {
        $existingModel = new SeoModel(
            pageIdentifier: 'edit_page',
            core: new CoreSeo(
                title: 'Old Title',
                metaDescription: 'Old Description',
                metaRobots: RobotsMode::INDEX_FOLLOW
            ),
            social: new SocialSeo(ogTitle: 'Old OG'),
            sitemap: new SitemapSeo(priority: 0.5),
            meta: new MetaSeo(isNoIndex: false)
        );

        $formData = [
            'core' => [
                'title' => 'New Title',
                'metaDescription' => 'New Description',
                'metaRobots' => RobotsMode::NOINDEX_NOFOLLOW->value,
            ],
            'social' => [
                'ogTitle' => 'New OG',
            ],
            'sitemap' => [
                'priority' => 0.9,
            ],
            'meta' => [
                'isNoIndex' => '1',
            ],
        ];

        // On an edit form, pageIdentifier is disabled but still present in model
        $form = $this->factory->create(SeoType::class, $existingModel, ['is_edit' => true]);
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());

        /** @var SeoModel $model */
        $model = $form->getData();
        $this->assertInstanceOf(SeoModel::class, $model);

        $this->assertEquals('edit_page', $model->getPageIdentifier());
        $this->assertEquals('New Title', $model->getCore()->getTitle());
        $this->assertEquals('New Description', $model->getCore()->getMetaDescription());
        $this->assertEquals(RobotsMode::NOINDEX_NOFOLLOW, $model->getCore()->getMetaRobots());
        $this->assertEquals('New OG', $model->getSocial()->getOgTitle());
        $this->assertEquals(0.9, $model->getSitemap()->getPriority());
        $this->assertTrue($model->getMeta()->isNoIndex());
    }
}
