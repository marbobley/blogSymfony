<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\Form;

use App\Domain\Model\PostModel;
use App\Domain\Model\TagModel;
use App\Domain\UseCaseInterface\Tag\ListTagsInterface;
use App\Infrastructure\Form\PostType;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;

class PostTypeTest extends TypeTestCase
{
    private $listTags;

    protected function setUp(): void
    {
        $this->listTags = $this->createMock(ListTagsInterface::class);
        parent::setUp();
    }

    protected function getExtensions(): array
    {
        $type = new PostType($this->listTags);

        return [
            new PreloadedExtension([$type], []),
        ];
    }

    public function testSubmitValidData(): void
    {
        $tag1 = new TagModel();
        $tag1->setId(1);
        $tag1->setName('Tag 1');

        $tag2 = new TagModel();
        $tag2->setId(2);
        $tag2->setName('Tag 2');

        $this->listTags->method('execute')->willReturn([$tag1, $tag2]);

        $formData = [
            'title' => 'Test Post Title',
            'sub_title' => 'Test Sub Title',
            'content' => 'Test Post Content',
            'tags' => ['1', '2'],
        ];

        $model = new PostModel();
        $form = $this->factory->create(PostType::class, $model);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals('Test Post Title', $model->getTitle());
        $this->assertEquals('Test Post Content', $model->getContent());
        $this->assertCount(2, $model->getTags());
        $this->assertSame($tag1, $model->getTags()[0]);
        $this->assertSame($tag2, $model->getTags()[1]);
    }

    public function testEditWithExistingTags(): void
    {
        $tag1 = new TagModel();
        $tag1->setId(1);
        $tag1->setName('Tag 1');

        $this->listTags->method('execute')->willReturn([$tag1]);

        $model = new PostModel();
        $model->setTitle('Existing Title');
        $model->setContent('Existing Content');
        $model->addTag($tag1);

        // This is where it should fail based on the issue description
        $form = $this->factory->create(PostType::class, $model);

        $view = $form->createView();
        $this->assertNotNull($view);
    }
}
