<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\EventListener;

use App\Domain\Exception\EntityNotFoundException;
use App\Infrastructure\EventListener\ExceptionListener;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Twig\Environment;

class ExceptionListenerTest extends TestCase
{
    private Environment $twig;
    private ExceptionListener $listener;

    protected function setUp(): void
    {
        $this->twig = $this->createMock(Environment::class);
        $this->listener = new ExceptionListener($this->twig);
    }

    public function testOnKernelExceptionIgnoresNonDomainException(): void
    {
        $event = $this->createExceptionEvent(new \Exception('Test'));

        $this->listener->onKernelException($event);

        $this->assertNull($event->getResponse());
    }

    public function testOnKernelExceptionReturnsJsonResponse(): void
    {
        $exception = EntityNotFoundException::forEntity('Post', 1);
        $request = new Request();
        $request->headers->set('Accept', 'application/json');

        $event = $this->createExceptionEvent($exception, $request);

        $this->listener->onKernelException($event);

        $response = $event->getResponse();
        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(Response::HTTP_NOT_FOUND, $response->getStatusCode());
        $this->assertStringContainsString('application/json', $response->headers->get('Content-Type'));
    }

    public function testOnKernelExceptionReturnsHtmlResponse(): void
    {
        $exception = EntityNotFoundException::forEntity('Post', 1);
        $request = new Request();

        $this->twig->expects($this->once())
            ->method('render')
            ->with('@Twig/Exception/error404.html.twig', $this->isType('array'))
            ->willReturn('<html>Error</html>');

        $event = $this->createExceptionEvent($exception, $request);

        $this->listener->onKernelException($event);

        $response = $event->getResponse();
        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(Response::HTTP_NOT_FOUND, $response->getStatusCode());
        $this->assertSame('<html>Error</html>', $response->getContent());
    }

    private function createExceptionEvent(\Throwable $exception, Request $request = null): ExceptionEvent
    {
        return new ExceptionEvent(
            $this->createMock(HttpKernelInterface::class),
            $request ?? new Request(),
            HttpKernelInterface::MAIN_REQUEST,
            $exception
        );
    }
}
