<?php

declare(strict_types=1);

namespace App\Application\UseCase;

use App\Application\Model\TagModel;
use App\Application\Provider\TagProviderInterface;
use App\Application\UseCaseInterface\UpdateTagInterface;

readonly class UpdateTag implements UpdateTagInterface
{
    public function __construct(
        private TagProviderInterface   $tagProvider,
    ) {
    }

    public function execute(int $id, TagModel $tagDTO): TagModel
    {
        return $this->tagProvider->update($id, $tagDTO);
    }
}
