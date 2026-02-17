<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Infrastructure\Entity\Post;
use App\Infrastructure\Entity\Tag;
use App\Infrastructure\Entity\User;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Exception;
use function array_rand;
use function rand;
use function str_repeat;

class AppFixtures extends Fixture
{
    /**
     * @throws Exception
     */
    public function load(ObjectManager $manager): void
    {
        // --- USERS ---
        $admin = new User('admin@blog.com', '$2y$13$D2lg.iFaKCmwbbbu3hKFhOeAyXgpL05Lp9TsDOSrH4XK57SS7x.z6'); // En prod, on encoderait le mot de passe
        $admin->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);

        $user = new User('user@blog.com', '$2y$13$D2lg.iFaKCmwbbbu3hKFhOeAyXgpL05Lp9TsDOSrH4XK57SS7x.z6');
        $manager->persist($user);

        // --- TAGS ---
        $tags = $this->generateTags($manager);

        // --- POSTS ---
        $this->generatePosts($tags, $manager);

        $manager->flush();
    }

    /**
     * @param ObjectManager $manager
     * @return Tag[]
     */
    public function generateTags(ObjectManager $manager): array
    {
        $tags = [];
        $tagNames = ['Symfony', 'PHP', 'Web Development', 'Doctrine', 'Tutorial'];
        foreach ($tagNames as $name) {
            $tag = new Tag($name);
            $manager->persist($tag);
            $tags[] = $tag;
        }
        return $tags;
    }

    /**
     * @param Tag[] $tags
     * @param ObjectManager $manager
     * @return void
     * @throws Exception
     */
    public function generatePosts(array $tags, ObjectManager $manager): void
    {
        for ($i = 1; $i <= 10; $i++) {
            $post = new Post(
                "Article numéro {$i}",
                "Ceci est le contenu de l'article numéro {$i}. Il contient des informations très intéressantes sur le développement Symfony. "
                    . str_repeat(string: 'Lorem ipsum dolor sit amet. ', times: 5),
            );
            $post->setSubTitle("Ceci est le soutitre {$i}");
            $post->setPublished(true);

            $createdAt = new DateTimeImmutable('-' . (11 - $i) . ' days');
            $post->setCreatedAt($createdAt);

            if (($i % 2) === 0) {
                $post->setUpdatedAt($createdAt->modify('+' . rand(min: 1, max: 10) . ' hours'));
            }

            $randomTags =  array_rand(array: $tags, num: 2);
            foreach ($randomTags as $index) {
                $tag = $tags[$index];
                $post->addTag($tag);
            }

            $manager->persist($post);
        }
    }
}
