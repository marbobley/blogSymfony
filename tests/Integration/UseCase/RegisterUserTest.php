<?php

declare(strict_types=1);

namespace App\Tests\Integration\UseCase;

use App\Domain\Model\UserRegistrationModel;
use App\Domain\UseCaseInterface\RegisterUserInterface;
use App\Infrastructure\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class RegisterUserTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;
    private RegisterUserInterface $registerUser;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $this->entityManager = $container->get(EntityManagerInterface::class);
        $this->registerUser = $container->get(RegisterUserInterface::class);

        $this->cleanup();
    }

    private function cleanup(): void
    {
        $this->entityManager->createQuery('DELETE FROM App\Infrastructure\Entity\User')->execute();
    }

    public function testRegisterUser(): void
    {
        $dto = new UserRegistrationModel('test@example.com', 'plain_password');

        $result = $this->registerUser->execute($dto);

        $this->assertInstanceOf(User::class, $result);
        $this->assertEquals('test@example.com', $result->getEmail());

        $dbUser = $this->entityManager->getRepository(User::class)->findOneBy(['email' => 'test@example.com']);
        $this->assertNotNull($dbUser);
        $this->assertNotEquals('plain_password', $dbUser->getPassword());
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
    }
}
