<?php

declare(strict_types=1);

namespace App\Domain\UseCase;

use App\Domain\Model\PostModel;
use App\Domain\Provider\PostProviderInterface;
use App\Domain\UseCaseInterface\UpdatePostInterface;

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
