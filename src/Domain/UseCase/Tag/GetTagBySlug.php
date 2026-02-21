<?php

declare(strict_types=1);

namespace App\Domain\UseCase\Tag;

use App\Domain\Model\TagModel;
use App\Domain\Provider\TagProviderInterface;
use App\Domain\UseCaseInterface\Tag\GetTagBySlugInterface;

readonly class GetTagBySlug implements GetTagBySlugInterface
{
    public function __construct(
        private TagProviderInterface $tagProvider,
    ) {}

    public function execute(string $slug): TagModel
    {
        return $this->tagProvider->findBySlug($slug);
    }
}
