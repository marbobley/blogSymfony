<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\Form;

use App\Domain\Model\UserRegistrationModel;
use App\Infrastructure\Form\RegistrationType;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Validator\Validation;

class RegistrationTypeTest extends TypeTestCase
{
    protected function getExtensions(): array
    {
        $validator = Validation::createValidator();

        return [
            new ValidatorExtension($validator),
        ];
    }

    public function testSubmitValidData(): void
    {
        $formData = [
            'email' => 'test@example.com',
            'plainPassword' => [
                'first' => 'password123',
                'second' => 'password123',
            ],
        ];

        $form = $this->factory->create(RegistrationType::class);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());

        /** @var UserRegistrationModel $model */
        $model = $form->getData();
        $this->assertInstanceOf(UserRegistrationModel::class, $model);
        $this->assertSame('test@example.com', $model->email);
        $this->assertSame('password123', $model->plainPassword);

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}
