<?php

declare(strict_types=1);

namespace App\Infrastructure\Entity;

use App\Infrastructure\Entity\Component\CoreSeoData;
use App\Infrastructure\Entity\Component\MetaSeoData;
use App\Infrastructure\Entity\Component\SitemapSeoData;
use App\Infrastructure\Entity\Component\SocialSeoData;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'seo_data')]
class SeoData
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    private string $pageIdentifier;

    #[ORM\Embedded(class: CoreSeoData::class)]
    private CoreSeoData $core;

    #[ORM\Embedded(class: SocialSeoData::class)]
    private SocialSeoData $social;

    #[ORM\Embedded(class: SitemapSeoData::class)]
    private SitemapSeoData $sitemap;

    #[ORM\Embedded(class: MetaSeoData::class)]
    private MetaSeoData $meta;

    public function __construct()
    {
        $this->core = new CoreSeoData();
        $this->social = new SocialSeoData();
        $this->sitemap = new SitemapSeoData();
        $this->meta = new MetaSeoData();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPageIdentifier(): string
    {
        return $this->pageIdentifier;
    }

    public function setPageIdentifier(string $pageIdentifier): self
    {
        $this->pageIdentifier = $pageIdentifier;
        return $this;
    }

    public function getCore(): CoreSeoData
    {
        return $this->core;
    }

    public function setCore(CoreSeoData $core): self
    {
        $this->core = $core;
        return $this;
    }

    public function getSocial(): SocialSeoData
    {
        return $this->social;
    }

    public function setSocial(SocialSeoData $social): self
    {
        $this->social = $social;
        return $this;
    }

    public function getSitemap(): SitemapSeoData
    {
        return $this->sitemap;
    }

    public function setSitemap(SitemapSeoData $sitemap): self
    {
        $this->sitemap = $sitemap;
        return $this;
    }

    public function getMeta(): MetaSeoData
    {
        return $this->meta;
    }

    public function setMeta(MetaSeoData $meta): self
    {
        $this->meta = $meta;
        return $this;
    }
}
