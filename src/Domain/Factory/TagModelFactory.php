<?php

declare(strict_types=1);

namespace App\Domain\Factory;

use App\Domain\Model\TagModel;
use InvalidArgumentException;

class TagModelFactory
{
    private TagModel $model ;
    public function __construct(){
        $this->model = new TagModel();
    }
    /**
     * @throws InvalidArgumentException
     */
    public static function create(int $id, string $name, string $slug): TagModel
    {
        $tag = new TagModel();
        $tag->setName($name);
        $tag->setSlug($slug);
        $tag->setId($id);

        return $tag;
    }
}
