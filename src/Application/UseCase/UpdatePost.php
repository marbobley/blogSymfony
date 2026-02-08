<?php

declare(strict_types=1);

namespace App\Application\UseCase;

use App\Application\Model\PostModel;
use App\Application\Provider\PostProviderInterface;
use App\Application\UseCaseInterface\UpdatePostInterface;

readonly class UpdatePost implements UpdatePostInterface
{
    public function __construct(
        private PostProviderInterface   $postProvider
    ) {
    }

    public function execute(int $id, PostModel $postModel): PostModel
    {
        return $this->postProvider->update($id, $postModel);
    }
}
