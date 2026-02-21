<?php

declare(strict_types=1);

namespace App\Domain\UseCase\Tag;

use App\Domain\Provider\TagProviderInterface;
use App\Domain\UseCaseInterface\Tag\DeleteTagInterface;

readonly class DeleteTag implements DeleteTagInterface
{
    public function __construct(
        private TagProviderInterface $tagProvider,
    ) {}

    public function execute(int $id): void
    {
        $this->tagProvider->delete($id);
    }
}
