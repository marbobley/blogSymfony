<?php

declare(strict_types=1);

namespace App\Domain\Factory;

use App\Domain\Model\TagModel;

class TagModelFactory
{
    public static function create(int $id, string $name, string $slug): TagModel
    {
        $tag = new TagModel();
        $tag->setName($name);
        $tag->setSlug($slug);
        $tag->setId($id);

        return $tag;
    }
}
