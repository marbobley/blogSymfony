<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\Form;

use App\Application\Model\PostModel;
use App\Application\Model\TagModel;
use App\Application\UseCaseInterface\ListTagsInterface;
use App\Infrastructure\Form\PostType;
use Symfony\Component\Form\Test\TypeTestCase;
use PHPUnit\Framework\MockObject\MockObject;

class PostTypeTest extends TypeTestCase
{
    private MockObject&ListTagsInterface $listTags;

    protected function setUp(): void
    {
        $this->listTags = $this->createMock(ListTagsInterface::class);
        parent::setUp();
    }

    protected function getExtensions(): array
    {
        $type = new PostType($this->listTags);

        return [
            new \Symfony\Component\Form\PreloadedExtension([$type], []),
        ];
    }

    public function testSubmitValidData(): void
    {
        $tag1 = new TagModel(1, 'Tag 1', 'tag-1');
        $tag2 = new TagModel(2, 'Tag 2', 'tag-2');

        $this->listTags->method('execute')->willReturn([$tag1, $tag2]);

        $formData = [
            'title' => 'Test Title Long Enough',
            'content' => 'Test Content',
            'tags' => ['1'],
        ];

        $model = new PostModel();
        $form = $this->factory->create(PostType::class, $model);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertCount(1, $model->getTags());
        $this->assertEquals('Tag 1', $model->getTags()[0]->getName());
    }
}
