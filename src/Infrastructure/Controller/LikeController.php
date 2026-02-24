<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller;

use App\Domain\UseCaseInterface\Like\TogglePostLikeInterface;
use App\Infrastructure\Entity\User;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class LikeController extends AbstractController
{
    /**
     * @throws LogicException
     */
    #[Route('/post/{id}/like', name: 'app_post_like', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function toggle(int $id, TogglePostLikeInterface $togglePostLike): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        $togglePostLike->execute($id, (int) $user->getId());

        return new JsonResponse(['status' => 'success']);
    }
}
