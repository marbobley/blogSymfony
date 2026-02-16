<?php

declare(strict_types=1);

namespace App\Domain\UseCase;

use App\Domain\Provider\TagProviderInterface;
use App\Domain\UseCaseInterface\ListTagsInterface;

readonly class ListTags implements ListTagsInterface
{
    public function __construct(
        private TagProviderInterface $tagProvider,
    ) {}

    public function execute(): array
    {
        return $this->tagProvider->findAll();
    }
}
