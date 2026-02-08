<?php

declare(strict_types=1);

namespace App\Domain\Exception;

class EntityNotFoundException extends \RuntimeException implements DomainExceptionInterface
{
    public function __construct(string $entityName, mixed $identifier)
    {
        $message = sprintf('%s avec l\'identifiant "%s" non trouvé(e).', $entityName, (string) $identifier);
        parent::__construct($message);
    }

    public static function forEntity(string $entityName, mixed $identifier): self
    {
        return new self($entityName, $identifier);
    }
}
