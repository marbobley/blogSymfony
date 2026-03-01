<?php

namespace App\Factory\SonataMedia;

use App\Infrastructure\Entity\SonataMedia\Media;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Media>
 */
final class MediaFactory extends PersistentProxyObjectFactory
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
        return Media::class;
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
            'cdnIsFlushable' => self::faker()->boolean(),
            'createdAt' => self::faker()->dateTime(),
            'enabled' => self::faker()->boolean(),
            'name' => self::faker()->text(255),
            'providerName' => self::faker()->text(255),
            'providerReference' => self::faker()->text(255),
            'providerStatus' => self::faker()->randomNumber(),
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
            // ->afterInstantiate(function(Media $media): void {})
        ;
    }
}
