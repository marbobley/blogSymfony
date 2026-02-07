<?php

declare(strict_types=1);

namespace App\Application\UseCaseInterface;


use App\Application\Model\TagModel;

interface ListTagsInterface
{
    /**
     * @return TagModel[]
     */
    public function execute(): array;
}
