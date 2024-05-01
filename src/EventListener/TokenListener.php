<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

// класс проверяющий соответствие токена
class TokenListener
{
    private $validToken;

    public function __construct(string $validToken)
    {
        $this->validToken = $validToken;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();
        $token = $request->headers->get('authorization');
        if ($token !== $this->validToken) {
            throw new AccessDeniedException('Invalid token');
        }
    }
}
