<?php

declare(strict_types=1);

namespace App\Infrastructure\EventListener;

use App\Domain\Exception\DomainExceptionInterface;
use App\Domain\Exception\EntityNotFoundException;
use App\Domain\Exception\TagAlreadyExistsException;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Twig\Environment;
use function str_contains;

#[AsEventListener(event: 'kernel.exception')]
class ExceptionListener
{
    public function __construct(
        private readonly Environment $twig,
    ) {}

    /**
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     * @throws \InvalidArgumentException
     */
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $request = $event->getRequest();

        // Si ce n'est pas une exception de notre Domaine, on laisse Symfony gérer
        if (!$exception instanceof DomainExceptionInterface) {
            return;
        }

        $statusCode = match (true) {
            $exception instanceof EntityNotFoundException => Response::HTTP_NOT_FOUND,
            $exception instanceof TagAlreadyExistsException => Response::HTTP_CONFLICT,
            default => Response::HTTP_BAD_REQUEST,
        };

        // Détection du format attendu
        $acceptHeader = (string) $request->headers->get('Accept', '');
        if (str_contains($acceptHeader, 'application/json') || $request->isXmlHttpRequest()) {
            $responseData = [
                'error' => [
                    'message' => $exception->getMessage(),
                    'code' => $statusCode,
                ],
            ];
            $event->setResponse(new JsonResponse($responseData, $statusCode));

            return;
        }

        // Rendu HTML via Twig
        $template = match ($statusCode) {
            Response::HTTP_NOT_FOUND => '@Twig/Exception/error404.html.twig',
            default => '@Twig/Exception/error.html.twig',
        };

        $event->setResponse(new Response($this->twig->render($template, [
            'status_code' => $statusCode,
            'status_text' => Response::$statusTexts[$statusCode] ?? 'Unknown Error',
            'exception' => $exception,
        ]), $statusCode));
    }
}
