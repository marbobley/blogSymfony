<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence;

use App\Domain\Model\SeoModel;
use App\Infrastructure\Entity\SeoData;
use App\Infrastructure\Entity\Tag;
use App\Infrastructure\Repository\SeoDataRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @extends ServiceEntityRepository<SeoData>
 */
class DoctrineSeoDataRepository extends AbstractDoctrineRepository implements SeoDataRepositoryInterface
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, SeoData::class);
    }

    public function findByPageIdentifier(string $identifier)
    {
        /** @var SeoData|null $entity */
       return $this->entityManager->getRepository(SeoData::class)->findOneBy(['pageIdentifier' => $identifier]);
    }
}
