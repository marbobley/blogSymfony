<?php

declare(strict_types=1);

namespace App\Domain\UseCase\Tag;

use App\Domain\Model\TagModel;
use App\Domain\Provider\TagProviderInterface;
use App\Domain\UseCaseInterface\Tag\GetTagInterface;

readonly class GetTag implements GetTagInterface
{
    public function __construct(
        private TagProviderInterface $tagProvider,
    ) {}

    public function execute(int $id): TagModel
    {
        return $this->tagProvider->findById($id);
    }
}
