<?php

namespace App\DataFixtures;

use App\Infrastructure\Entity\Post;
use App\Infrastructure\Entity\Tag;
use App\Infrastructure\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // --- USERS ---
        $admin = new User('admin@blog.com', '$2y$13$D2lg.iFaKCmwbbbu3hKFhOeAyXgpL05Lp9TsDOSrH4XK57SS7x.z6'); // En prod, on encoderait le mot de passe
        $admin->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);

        $user = new User('user@blog.com', '$2y$13$D2lg.iFaKCmwbbbu3hKFhOeAyXgpL05Lp9TsDOSrH4XK57SS7x.z6');
        $manager->persist($user);

        // --- TAGS ---
        $tags = [];
        $tagNames = ['Symfony', 'PHP', 'Web Development', 'Doctrine', 'Tutorial'];
        foreach ($tagNames as $name) {
            $tag = new Tag($name);
            $manager->persist($tag);
            $tags[] = $tag;
        }

        // --- POSTS ---
        for ($i = 1; $i <= 10; $i++) {
            $post = new Post(
                "Article numéro $i",
                "Ceci est le contenu de l'article numéro $i. Il contient des informations très intéressantes sur le développement Symfony."
            );

            // Ajout de quelques tags aléatoires
            $randomTags = (array) array_rand($tags, 2);
            foreach ($randomTags as $index) {
                $post->addTag($tags[$index]);
            }

            $manager->persist($post);

        }

        $manager->flush();
    }
}
