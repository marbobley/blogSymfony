<?php

declare(strict_types=1);

namespace App\Application\UseCase;

use App\Application\Provider\TagProviderInterface;
use App\Application\UseCaseInterface\ListTagsInterface;

readonly class ListTags implements ListTagsInterface
{
    public function __construct(
        private TagProviderInterface $tagProvider
    ) {
    }

    public function execute(): array
    {
        return $this->tagProvider->findAll();
    }
}
