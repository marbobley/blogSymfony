<?php

declare(strict_types=1);

namespace App\Domain\Builder;

use App\Domain\Model\LikeModel;
use DateTimeImmutable;

class LikeModelBuilder
{
    private LikeModel $model;

    public function __construct()
    {
        $this->model = new LikeModel();
    }

    public function setId(?int $id): LikeModelBuilder
    {
        $this->model->setId($id);
        return $this;
    }

    public function setIdPost(?int $idPost): LikeModelBuilder
    {
        $this->model->setPostId($idPost);
        return $this;
    }

    public function setUserId(?int $userId): LikeModelBuilder
    {
        $this->model->setUserId($userId);
        return $this;
    }

    public function setCreatedAt(?DateTimeImmutable $createdAt): LikeModelBuilder
    {
        $this->model->setCreatedAt($createdAt);
        return $this;
    }

    public function build(): LikeModel
    {
        return $this->model;
    }
}
