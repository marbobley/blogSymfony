<?php

declare(strict_types=1);

namespace App\Application\UseCase;

use App\Application\Model\TagModel;
use App\Application\Provider\TagProviderInterface;
use App\Application\UseCaseInterface\CreateTagInterface;

readonly class CreateTag implements CreateTagInterface
{
    public function __construct(
        private TagProviderInterface $tagProvider
    ) {
    }

    public function execute(TagModel $tagDTO): TagModel
    {
        return $this->tagProvider->save($tagDTO->getName());
    }
}
