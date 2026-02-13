<?php
declare(strict_types=1);
namespace App\Domain\Provider;

use App\Domain\Model\PostModel;

interface PostProviderInterface
{

    public function save(PostModel $postModel) : PostModel;

    public function delete(int $id) : void;

    public function findById(int $id) : PostModel;

    public function findBySlug(string $slug): PostModel;

    /** @return PostModel[] */
    public function findByTag(?int $tagId) : array;

    /** @return PostModel[] */
    public function findPublished(?int $tagId = null): array;

    public function update(int $id, PostModel $postModel): PostModel;
}
