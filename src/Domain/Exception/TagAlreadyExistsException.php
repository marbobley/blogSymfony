<?php

declare(strict_types=1);

namespace App\Domain\Exception;

use RuntimeException;
use function sprintf;

class TagAlreadyExistsException extends RuntimeException implements DomainExceptionInterface
{
    public function __construct(string $tagName)
    {
        parent::__construct(sprintf('Le tag "%s" existe déjà.', $tagName));
    }
}
