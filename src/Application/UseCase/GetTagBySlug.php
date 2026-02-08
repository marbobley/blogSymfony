<?php

declare(strict_types=1);

namespace App\Application\UseCase;

use App\Application\Model\TagModel;
use App\Application\Provider\TagProviderInterface;
use App\Application\UseCaseInterface\GetTagBySlugInterface;

readonly class GetTagBySlug implements GetTagBySlugInterface
{
    public function __construct(
        private TagProviderInterface   $tagProvider
    ) {
    }

    public function execute(string $slug): TagModel
    {
        return $this->tagProvider->findBySlug($slug);
    }
}
