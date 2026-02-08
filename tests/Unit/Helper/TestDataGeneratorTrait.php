<?php

declare(strict_types=1);

namespace App\Tests\Unit\Helper;

use App\Domain\Model\PostModel;
use App\Domain\Model\TagModel;
use App\Domain\Model\UserRegistrationModel;
use App\Infrastructure\Entity\Post;
use App\Infrastructure\Entity\Tag;
use App\Infrastructure\Entity\User;

trait TestDataGeneratorTrait
{
    private function createPostModel(
        string $title = 'Default Title',
        string $content = 'Default Content',
        array $tags = []
    ): PostModel {
        $model = new PostModel();
        $model->setTitle($title);
        $model->setContent($content);
        foreach ($tags as $tag) {
            $model->addTag($tag);
        }
        return $model;
    }

    private function createTagModel(
        int $id = 1,
        string $name = 'Default Tag',
        string $slug = 'default-tag'
    ): TagModel {
        $model = new TagModel();
        $model->setId($id);
        $model->setName($name);
        $model->setSlug($slug);
        return $model;
    }

    private function createPostEntity(
        string $title = 'Default Title',
        string $content = 'Default Content',
        string $slug = 'default-title'
    ): Post {
        $post = new Post($title, $content);
        $post->setSlug($slug);
        return $post;
    }

    private function createTagEntity(
        string $name = 'Default Tag',
        int $id = 1
    ): Tag {
        $tag = new Tag($name);
        $reflection = new \ReflectionClass($tag);
        $property = $reflection->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($tag, $id);
        return $tag;
    }

    private function createUserEntity(
        string $email = 'user@example.com',
        string $password = 'hashed_password',
        array $roles = ['ROLE_USER']
    ): User {
        $user = new User();
        $user->setEmail($email);
        $user->setPassword($password);
        $user->setRoles($roles);
        return $user;
    }

    private function createUserRegistrationModel(
        string $email = 'user@example.com',
        string $password = 'plain_password'
    ): UserRegistrationModel {
        return new UserRegistrationModel($email, $password);
    }
}
