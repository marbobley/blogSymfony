<?php
declare(strict_types=1);
namespace App\Application\Provider;

use App\Application\Model\PostModel;

interface TagProviderInterface
{

    public function save(PostModel $postModel) : PostModel;

    public function delete(int $id) : void;

    public function findById(int $id) : PostModel;

    public function findBySlug(string $slug): PostModel;

    public function findByTag(?int $tagId) : array;

    public function update(int $id, PostModel $postModel): PostModel;
}
