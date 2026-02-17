<?php

declare(strict_types=1);

namespace App\Tests\Unit\Helper;

use App\Domain\Model\PostModel;
use App\Domain\Model\TagModel;
use App\Domain\Model\UserRegistrationModel;
use App\Infrastructure\Entity\User;
use DateTimeImmutable;
use SimpleXMLElement;

trait XmlTestDataTrait
{
    /**
     * @return PostModel[]
     * @throws \Exception
     */
    private function loadPostModelsFromXml(string $filePath): array
    {
        $xml = simplexml_load_file($filePath);
        $posts = [];

        foreach ($xml->post as $postData) {
            $post = new PostModel();
            $post->setId((int) $postData['id']);
            $post->setTitle((string) $postData->title);
            $post->setSubTitle((string) $postData->subtitle);
            $post->setContent((string) $postData->content);
            $post->setSlug((string) $postData->slug);
            (string) $postData->published === 'true' ? $post->publish() : $post->unpublish();
            $post->setCreatedAt(new DateTimeImmutable((string) $postData->createdAt));

            if (isset($postData->tags)) {
                foreach ($postData->tags->tag as $tagData) {
                    $tag = new TagModel();
                    $tag->setId((int) $tagData['id']);
                    $tag->setName((string) $tagData->name);
                    $tag->setSlug((string) $tagData->slug);
                    $post->addTag($tag);
                }
            }

            $posts[] = $post;
        }

        return $posts;
    }

    /**
     * @return TagModel[]
     */
    private function loadTagModelsFromXml(string $filePath): array
    {
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
    private function loadUserRegistrationModelsFromXml(string $filePath): array
    {
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
                $reflection = new \ReflectionClass($user);
                $property = $reflection->getProperty('id');
                $property->setAccessible(true);
                $property->setValue($user, (int) $userData['id']);
            }

            $users[] = $user;
        }

        return $users;
    }
}
