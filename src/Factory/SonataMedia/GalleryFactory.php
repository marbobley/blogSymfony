<?php

namespace App\Factory\SonataMedia;

use App\Infrastructure\Entity\SonataMedia\Gallery;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Gallery>
 */
final class GalleryFactory extends PersistentProxyObjectFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
    }

    #[\Override]
    public static function class(): string
    {
        return Gallery::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    #[\Override]
    protected function defaults(): array|callable
    {
        return [
            'context' => self::faker()->text(64),
            'createdAt' => self::faker()->dateTime(),
            'defaultFormat' => self::faker()->text(255),
            'enabled' => self::faker()->boolean(),
            'name' => self::faker()->text(255),
            'updatedAt' => self::faker()->dateTime(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    #[\Override]
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Gallery $gallery): void {})
        ;
    }
}
