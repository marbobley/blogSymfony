<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\Form;

use App\Domain\Model\TagModel;
use App\Infrastructure\Form\TagType;
use Symfony\Component\Form\Test\TypeTestCase;

class TagTypeTest extends TypeTestCase
{
    public function testSubmitValidData(): void
    {
        $formData = [
            'name' => 'Test Tag',
        ];

        $model = new TagModel();
        $form = $this->factory->create(TagType::class, $model);

        $expected = new TagModel();
        $expected->setName('Test Tag');

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertSame($expected->getName(), $model->getName());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}
