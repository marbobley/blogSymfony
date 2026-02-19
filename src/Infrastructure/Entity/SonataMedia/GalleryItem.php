<?php

declare(strict_types=1);

namespace App\Infrastructure\Entity\SonataMedia;

use Doctrine\ORM\Mapping as ORM;
use Sonata\MediaBundle\Entity\BaseGalleryItem;

#[ORM\Entity]
#[ORM\Table(name: 'media__gallery_item')]
class GalleryItem extends BaseGalleryItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    protected int $id;

    public function getId(): ?int
    {
        return $this->id;
    }
}
