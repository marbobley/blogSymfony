<?php

declare(strict_types=1);

namespace App\Tests\Integration\UseCase;

use App\Domain\Model\TagModel;
use App\Domain\UseCaseInterface\CreateTagInterface;
use App\Domain\UseCaseInterface\DeleteTagInterface;
use App\Domain\UseCaseInterface\GetTagBySlugInterface;
use App\Domain\UseCaseInterface\GetTagInterface;
use App\Domain\UseCaseInterface\ListTagsInterface;
use App\Domain\UseCaseInterface\UpdateTagInterface;
use App\Infrastructure\Entity\Tag;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TagUseCaseTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;
    private CreateTagInterface $createTag;
    private ListTagsInterface $listTags;
    private UpdateTagInterface $updateTag;
    private DeleteTagInterface $deleteTag;
    private GetTagInterface $getTag;
    private GetTagBySlugInterface $getTagBySlug;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $this->entityManager = $container->get(EntityManagerInterface::class);
        $this->createTag = $container->get(CreateTagInterface::class);
        $this->listTags = $container->get(ListTagsInterface::class);
        $this->updateTag = $container->get(UpdateTagInterface::class);
        $this->deleteTag = $container->get(DeleteTagInterface::class);
        $this->getTag = $container->get(GetTagInterface::class);
        $this->getTagBySlug = $container->get(GetTagBySlugInterface::class);

        $this->cleanup();
    }

    private function cleanup(): void
    {
        $this->entityManager->createQuery('DELETE FROM App\Infrastructure\Entity\Post')->execute();
        $this->entityManager->createQuery('DELETE FROM App\Infrastructure\Entity\Tag')->execute();
    }

    public function testCreateTag(): void
    {
        $tagModel = new TagModel();
        $tagModel->setName('Symfony');

        $result = $this->createTag->execute($tagModel);

        $this->assertNotNull($result->getId());
        $this->assertEquals('Symfony', $result->getName());

        $dbTag = $this->entityManager->find(Tag::class, $result->getId());
        $this->assertNotNull($dbTag);
        $this->assertEquals('Symfony', $dbTag->getName());
    }

    public function testListTags(): void
    {
        $tag1 = new Tag('PHP');
        $this->entityManager->persist($tag1);
        $tag2 = new Tag('Doctrine');
        $this->entityManager->persist($tag2);
        $this->entityManager->flush();

        $result = $this->listTags->execute();

        $this->assertCount(2, $result);
    }

    public function testUpdateTag(): void
    {
        $tag = new Tag('Ancien Nom');
        $this->entityManager->persist($tag);
        $this->entityManager->flush();

        $updateModel = new TagModel();
        $updateModel->setName('Nouveau Nom');

        $result = $this->updateTag->execute($tag->getId(), $updateModel);

        $this->assertEquals('Nouveau Nom', $result->getName());

        $this->entityManager->clear();
        $dbTag = $this->entityManager->find(Tag::class, $tag->getId());
        $this->assertEquals('Nouveau Nom', $dbTag->getName());
    }

    public function testGetTag(): void
    {
        $tag = new Tag('Tag à trouver');
        $this->entityManager->persist($tag);
        $this->entityManager->flush();

        $result = $this->getTag->execute($tag->getId());

        $this->assertEquals('Tag à trouver', $result->getName());
        $this->assertEquals($tag->getId(), $result->getId());
    }

    public function testGetTagBySlug(): void
    {
        $tag = new Tag('Mon Beau Tag');
        $tag->setSlug('mon-beau-tag');
        $this->entityManager->persist($tag);
        $this->entityManager->flush();

        $result = $this->getTagBySlug->execute('mon-beau-tag');

        $this->assertEquals('Mon Beau Tag', $result->getName());
        $this->assertEquals('mon-beau-tag', $result->getSlug());
    }

    public function testDeleteTag(): void
    {
        $tag = new Tag('A supprimer');
        $this->entityManager->persist($tag);
        $this->entityManager->flush();

        $id = $tag->getId();
        $this->deleteTag->execute($id);

        $this->entityManager->clear();
        $dbTag = $this->entityManager->find(Tag::class, $id);
        $this->assertNull($dbTag);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
    }
}
