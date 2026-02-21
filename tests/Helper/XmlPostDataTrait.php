<?php

declare(strict_types=1);

namespace App\Tests\Helper;

use App\Domain\Model\PostModel;
use App\Domain\Model\TagModel;
use DateTimeImmutable;
use Exception;

trait XmlPostDataTrait
{
    /**
     * @return PostModel[]
     * @throws Exception
     */
    public function loadPostModelsFromXml(string $filePath): array
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
            $post->setUpdatedAt(new DateTimeImmutable((string) $postData->updatedAt));

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
}
