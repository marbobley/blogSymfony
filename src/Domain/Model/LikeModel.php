<?php

declare(strict_types=1);

namespace App\Domain\Model;

use DateTimeImmutable;

class LikeModel extends BaseModelAbstract
{
    private ?int $postId = null;
    private ?int $userId = null;
    private ?DateTimeImmutable $createdAt = null;

    public function getPostId(): ?int
    {
        return $this->postId;
    }

    public function setPostId(?int $postId): void
    {
        $this->postId = $postId;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(?int $userId): void
    {
        $this->userId = $userId;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}
