<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Domain\Service\PasswordBlogHasherInterface;
use App\Infrastructure\Entity\Post;
use App\Infrastructure\Entity\PostLike;
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
    public function __construct(
        private readonly PasswordBlogHasherInterface $passwordHasher
    )
    {
    }

    /**
     * @throws Exception
     */
    public function load(ObjectManager $manager): void
    {
        $admin = $this->generateUser('admin@blog.com', ['ROLE_ADMIN']);

        $user1 = $this->generateUser('user1@blog.com', ['ROLE_USER']);
        $user2 = $this->generateUser('user2@blog.com', ['ROLE_USER']);
        $user3 = $this->generateUser('user3@blog.com', ['ROLE_USER']);
        $user4 = $this->generateUser('user4@blog.com', ['ROLE_USER']);
        $user5 = $this->generateUser('user5@blog.com', ['ROLE_USER']);
        $user6 = $this->generateUser('user6@blog.com', ['ROLE_USER']);
        $user7 = $this->generateUser('user7@blog.com', ['ROLE_USER']);
        $manager->persist($admin);
        $manager->persist($user1);
        $manager->persist($user2);
        $manager->persist($user3);
        $manager->persist($user4);
        $manager->persist($user5);
        $manager->persist($user6);
        $manager->persist($user7);

        // --- TAGS ---
        $tags = $this->generateTags($manager);

        // --- POSTS ---
        $posts = $this->generatePosts($tags, $manager);

        $like = new PostLike();
        $like->setPost($posts[0]);
        $like->setUser($user1);
        $like->setCreatedAt(new DateTimeImmutable());
        $manager->persist($like);

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
     * @return Post[]
     * @throws Exception
     */
    public function generatePosts(array $tags, ObjectManager $manager): array
    {
        $posts = [];
        for ($i = 1; $i <= 10; $i++) {
            $post = new Post(
                "Article numéro $i",
                "Ceci est le contenu de l'article numéro $i. Il contient des informations très intéressantes sur le développement Symfony. "
                    . str_repeat(string: 'Lorem ipsum dolor sit amet. ', times: 5),
            );
            $post->setSubTitle("Ceci est le soutitre $i");
            $post->publish();

            $createdAt = new DateTimeImmutable('-' . (11 - $i) . ' days');
            $post->setCreatedAt($createdAt);

            if (($i % 2) === 0) {
                $post->setUpdatedAt($createdAt->modify('+' . rand(min: 1, max: 10) . ' hours'));
            }

            $randomTags = array_rand(array: $tags, num: 2);
            foreach ($randomTags as $index) {
                $tag = $tags[$index];
                $post->addTag($tag);
                $posts[] = $post;
            }
            $manager->persist($post);

        }
        return $posts;
    }

    private function generateUser(string $email, array $roles) : User
    {
        $password = $this->passwordHasher->hash($email, 'password');
        $user = new User(); // En prod, on encoderait le mot de passe
        $user->setEmail($email);
        $user->setPassword($password);
        $user->setRoles($roles);
        return $user;
    }
}
