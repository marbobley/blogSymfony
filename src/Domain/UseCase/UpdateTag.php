<?php

declare(strict_types=1);

namespace App\Domain\UseCase;

use App\Domain\Model\TagModel;
use App\Domain\Provider\TagProviderInterface;
use App\Domain\UseCaseInterface\UpdateTagInterface;

readonly class UpdateTag implements UpdateTagInterface
{
    public function __construct(
        private TagProviderInterface $tagProvider,
    ) {}

    public function execute(int $id, TagModel $tagDTO): TagModel
    {
        return $this->tagProvider->update($id, $tagDTO);
    }
}
