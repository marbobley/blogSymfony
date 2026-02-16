<?php

declare(strict_types=1);

namespace App\Domain\UseCase;

use App\Domain\Provider\TagProviderInterface;
use App\Domain\UseCaseInterface\DeleteTagInterface;

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
