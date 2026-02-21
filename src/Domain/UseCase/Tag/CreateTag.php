<?php

declare(strict_types=1);

namespace App\Domain\UseCase\Tag;

use App\Domain\Model\TagModel;
use App\Domain\Provider\TagProviderInterface;
use App\Domain\UseCaseInterface\Tag\CreateTagInterface;

readonly class CreateTag implements CreateTagInterface
{
    public function __construct(
        private TagProviderInterface $tagProvider,
    ) {}

    public function execute(TagModel $tagDTO): TagModel
    {
        return $this->tagProvider->save($tagDTO->getName());
    }
}
