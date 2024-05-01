<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

/*
 * Класс формирующий ответ и серриализирует данные к нужному типу (также можно задавать статус ответа)
 */
class ResponseService
{
    public function __construct(private RequestStack $requestStack,private SerializerInterface $serializer)
    {
    }
    public function formResponse(mixed $data, string|int $status_code = 200,array $groups = []): mixed
    {
        $accept = $this->requestStack->getCurrentRequest()->headers->get('accept');
        if ($accept == 'application/json') {
            $json = $this->serializer->serialize($data, 'json',$groups);
            return new JsonResponse($json,$status_code);
        } else{
            $xml = $this->serializer->serialize($data, 'xml',$groups);
            return new Response($xml,$status_code);
        }
    }
}