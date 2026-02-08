<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Infrastructure\Entity\User;

interface UserRepositoryInterface
{
    public function save(User $user): void;

    public function findByEmail(string $email): ?User;

    public function findById(int $id): ?User;

    public function delete(User $user): void;
}
