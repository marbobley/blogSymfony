<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Factory;

use App\Domain\Factory\UserRegistrationDTOFactory;
use App\Domain\Model\UserRegistrationModel;
use PHPUnit\Framework\TestCase;

class UserRegistrationDTOFactoryTest extends TestCase
{
    public function testCreate(): void
    {
        $email = 'test@example.com';
        $password = 'password123';

        $model = UserRegistrationDTOFactory::create($email, $password);

        $this->assertInstanceOf(UserRegistrationModel::class, $model);
        $this->assertSame($email, $model->email);
        $this->assertSame($password, $model->plainPassword);
    }
}
