<?php
declare(strict_types=1);
namespace App\Application\Provider;

use App\Application\Model\PostModel;

interface PostProviderInterface
{

    public function save(PostModel $postModel) : PostModel;

    public function delete(int $id) : void;

    public function findById(int $id) : PostModel;

    public function findBySlug(string $slug): PostModel;
}
