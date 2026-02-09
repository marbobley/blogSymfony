<?php

namespace App\DataFixtures;

use App\Infrastructure\Entity\Post;
use App\Infrastructure\Entity\SeoData;
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

            // --- SEO DATA pour chaque Post ---
            $seoData = new SeoData();
            $seoData->setPageIdentifier("post_" . $i);

            $seoData->getCore()
                ->setTitle("SEO Title for Post $i")
                ->setMetaDescription("Meta description for post $i to improve SEO rankings.")
                ->setCanonicalUrl("https://example.com/blog/article-$i")
                ->setMetaRobots("index, follow");

            $seoData->getSitemap()
                ->setInSitemap(true)
                ->setChangefreq("monthly")
                ->setPriority("0.8");

            $seoData->getMeta()
                ->setIsNoIndex(false)
                ->setBreadcrumbTitle("Article $i");

            $manager->persist($seoData);
        }

        // --- SEO DATA pour les pages fixes ---
        $homeSeo = new SeoData();
        $homeSeo->setPageIdentifier('home');

        $homeSeo->getCore()
            ->setTitle('Bienvenue sur mon Blog Symfony')
            ->setMetaDescription('Découvrez des articles passionnants sur Symfony et PHP.')
            ->setCanonicalUrl('https://example.com/');

        $homeSeo->getSitemap()
            ->setInSitemap(true)
            ->setPriority('1.0');

        $homeSeo->getMeta()
            ->setSchemaMarkup([
                '@context' => 'https://schema.org',
                '@type' => 'WebSite',
                'name' => 'Les Chroniques du Code',
                'url' => 'https://example.com/'
            ]);
        $manager->persist($homeSeo);

        $manager->flush();
    }
}
