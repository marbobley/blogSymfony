<?php
declare(strict_types=1);
namespace App\Domain\Provider;

use App\Domain\Criteria\PostCriteria;
use App\Domain\Model\PostModel;

interface PostProviderInterface
{

    public function save(PostModel $postModel) : PostModel;

    public function delete(int $id) : void;

    public function findById(int $id) : PostModel;

    public function findBySlug(string $slug): PostModel;

    /** @return PostModel[] */
    public function findByCriteria(PostCriteria $criteria): array;

    public function update(int $id, PostModel $postModel): PostModel;
}
