<?php

declare(strict_types=1);

namespace App\Application\UseCase;

use App\Application\Provider\TagProviderInterface;
use App\Application\UseCaseInterface\DeleteTagInterface;

readonly class DeleteTag implements DeleteTagInterface
{
    public function __construct(
        private TagProviderInterface $tagProvider
    ) {
    }

    public function execute(int $id): void
    {
        $this->tagProvider->delete($id);
    }
}
