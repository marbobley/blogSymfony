<?php

declare(strict_types=1);

namespace App\Domain\UseCaseInterface;

use App\Domain\Model\TagModel;

interface ListTagsInterface
{
    /**
     * @return TagModel[]
     */
    public function execute(): array;
}
