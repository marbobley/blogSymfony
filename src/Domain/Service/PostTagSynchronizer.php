<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Application\Model\PostModel;
use App\Domain\Model\Post;
use App\Domain\Model\Tag;
use App\Domain\Repository\TagRepositoryInterface;
use Doctrine\Common\Collections\ArrayCollection;

class PostTagSynchronizer
{
    public function __construct(
        private readonly TagRepositoryInterface $tagRepository
    ) {
    }

    public function synchronize(Post $post, PostModel $postDTO): void
    {
        $newTags = new ArrayCollection();

        foreach ($postDTO->getTags() as $tagDTO) {
            $tagName = $tagDTO->getName();
            $tag = $this->tagRepository->findByName($tagName);

            if (!$tag instanceof Tag) {
                $tag = new Tag($tagName);
            }

            $newTags->add($tag);
        }

        // Remove tags not in DTO
        foreach ($post->getTags() as $existingTag) {
            if (!$newTags->contains($existingTag)) {
                $post->removeTag($existingTag);
            }
        }

        // Add new tags
        foreach ($newTags as $newTag) {
            $post->addTag($newTag);
        }
    }
}
