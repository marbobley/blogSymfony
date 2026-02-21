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

    public function setName(string $name): TagModelFactory{
        $this->model->setName($name);
        return $this;
    }
    public function setSlug(string $slug): TagModelFactory{
        $this->model->setSlug($slug);
        return $this;
    }

    public function setId(int $id): TagModelFactory
    {
        $this->model->setId($id);
        return $this;
    }

    public function build(): TagModel
    {
        return $this->model;
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
