<?php

declare(strict_types=1);

namespace App\Application\UseCaseInterface;

use App\Application\Model\TagResponseModel;

interface ListTagsInterface
{
    /**
     * @return TagResponseModel[]
     */
    public function execute(): array;
}
