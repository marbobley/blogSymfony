<?php

namespace App\Tests\Helper;

use App\Domain\Factory\PostModelBuilder;
use App\Domain\Model\PostModel;

trait BuilderPostTrait
{

    /**
     * @return PostModel
     */
    public function buildSimplePostModel(): PostModel
    {
        $postBuilder = new PostModelBuilder();
        return $postBuilder->setId(1)->build();
    }

}
