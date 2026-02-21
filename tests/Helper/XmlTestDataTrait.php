<?php

declare(strict_types=1);

namespace App\Tests\Helper;

use App\Domain\Model\TagModel;
use App\Domain\Model\UserRegistrationModel;
use App\Infrastructure\Entity\User;
use ReflectionClass;

trait XmlTestDataTrait
{
    /**
     * @return TagModel[]
     */
    private function loadTagModelsFromXml(): array
    {
        $filePath = __DIR__ . '/Fixtures/tags.xml';
        $xml = simplexml_load_file($filePath);
        $tags = [];

        foreach ($xml->tag as $tagData) {
            $tag = new TagModel();
            $tag->setId((int) $tagData['id']);
            $tag->setName((string) $tagData->name);
            $tag->setSlug((string) $tagData->slug);
            $tags[] = $tag;
        }

        return $tags;
    }

    /**
     * @return UserRegistrationModel[]
     */
    private function loadUserRegistrationModelsFromXml(): array
    {
        $filePath = __DIR__ . '/Fixtures/users.xml';
        $xml = simplexml_load_file($filePath);
        $users = [];

        foreach ($xml->user as $userData) {
            $users[] = new UserRegistrationModel(
                (string) $userData->email,
                (string) $userData->password
            );
        }

        return $users;
    }

    /**
     * @return User[]
     */
    private function loadUserEntitiesFromXml(string $filePath): array
    {
        $xml = simplexml_load_file($filePath);
        $users = [];

        foreach ($xml->user as $userData) {
            $roles = [];
            if (isset($userData->roles)) {
                foreach ($userData->roles->role as $role) {
                    $roles[] = (string) $role;
                }
            }

            $user = new User(
                (string) $userData->email,
                (string) $userData->password,
                $roles
            );

            if (isset($userData['id'])) {
                $reflection = new ReflectionClass($user);
                $property = $reflection->getProperty('id');
                $property->setValue($user, (int) $userData['id']);
            }

            $users[] = $user;
        }

        return $users;
    }
}
