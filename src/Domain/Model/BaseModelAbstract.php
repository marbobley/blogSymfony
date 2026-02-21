<?php

namespace App\Domain\Model;

abstract class BaseModelAbstract
{
    private ?int $id = null;
    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

}
