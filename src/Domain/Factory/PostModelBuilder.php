<?php

declare(strict_types=1);

namespace App\Domain\Factory;

use App\Domain\Model\PostModel;
use App\Domain\Model\StatutArticle;
use App\Domain\Model\TagModel;
use DateTimeImmutable;
use Doctrine\Common\Collections\Collection;
use InvalidArgumentException;

class PostModelBuilder
{
    private PostModel $model;

    public function __construct()
    {
        $this->model = new PostModel();
    }

    public function setId(int $id): PostModelBuilder
    {
        $this->model->setId($id);
        return $this;
    }

    public function build(): PostModel
    {
        return $this->model;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function setTitle(string $title): PostModelBuilder
    {
        $this->model->setTitle($title);
        return $this;
    }

    public function setSubTitle(string $subTitle): PostModelBuilder
    {
        $this->model->setSubTitle($subTitle);
        return $this;
    }

    public function setContent(string $content): PostModelBuilder
    {
        $this->model->setContent($content);
        return $this;
    }

    public function setSlug(?string $slug): PostModelBuilder
    {
        $this->model->setSlug($slug);
        return $this;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): PostModelBuilder
    {
        $this->model->setCreatedAt($createdAt);
        return $this;
    }

    public function setUpdatedAt(?DateTimeImmutable $updatedAt): PostModelBuilder
    {
        $this->model->setUpdatedAt($updatedAt);
        return $this;
    }

    public function setPublished(StatutArticle $statutArticle): PostModelBuilder
    {
        StatutArticle::PUBLISHED === $statutArticle ? $this->model->publish() : $this->model->unpublish();
        return $this;
    }

    /**
     * @param Collection<int, TagModel> $tags
     */
    public function setTags(Collection $tags): PostModelBuilder
    {
        $this->model->addTags($tags);
        return $this;
    }
}
