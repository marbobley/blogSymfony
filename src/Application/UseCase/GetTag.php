<?php

declare(strict_types=1);

namespace App\Application\UseCase;

use App\Application\Model\TagModel;
use App\Application\Provider\TagProviderInterface;
use App\Application\UseCaseInterface\GetTagInterface;

readonly class GetTag implements GetTagInterface
{
    public function __construct(
        private TagProviderInterface $tagProvider
    ) {
    }

    public function execute(int $id): TagModel
    {
        return $this->tagProvider->findById($id);
    }
}
