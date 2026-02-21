<?php

declare(strict_types=1);

namespace App\Domain\Exception;

use RuntimeException;

use function get_debug_type;
use function is_scalar;
use function sprintf;

class EntityNotFoundException extends RuntimeException implements BlogExceptionInterface
{
    public function __construct(string $entityName, mixed $identifier)
    {
        $id = is_scalar($identifier) || $identifier instanceof \Stringable
            ? (string) $identifier
            : get_debug_type($identifier);
        $message = sprintf('%s avec l\'identifiant "%s" non trouvé(e).', $entityName, $id);
        parent::__construct($message);
    }

    public static function forEntity(string $entityName, mixed $identifier): self
    {
        return new self($entityName, $identifier);
    }
}
