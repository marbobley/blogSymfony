<?php

declare(strict_types=1);

namespace App\Tests\Helper;

use App\Domain\Model\TagModel;
use App\Infrastructure\Entity\User;

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
     * @return User[]
     */
    private function loadUserRegistrationModelsFromXml(): array
    {
        $filePath = __DIR__ . '/Fixtures/users.xml';
        $xml = simplexml_load_file($filePath);
        $users = [];

        foreach ($xml->user as $userData) {
            $users[] = new User(
                (string) $userData->email,
                (string) $userData->password
            );
        }

        return $users;
    }
}
