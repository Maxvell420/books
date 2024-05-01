<?php

namespace App\Controller;

use App\Entity\Publisher;
use App\Service\RequestHandler;
use App\Service\ResponseService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/*
 *  Контроллер вызывающий обработчик запросов, при получении результата или поимке исключения преобразует его в
 *  в xml/json и отправляет ответ
 */
class ApiController extends AbstractController
{
    public function __construct(private RequestHandler $requestHandler,
                                private ResponseService $responseService)
    {}

    #[Route('createBook',methods: 'PUT')]
    public function createBook(Request $request)
    {
        try {
            $book = $this->requestHandler->createBookHandler($request);
            return $this->responseService->formResponse($book,200,['groups'=>'books']);
        } catch (\Exception $e) {
            return new JsonResponse($e->getMessage());
        }
    }
    #[Route('getBooks',methods: 'GET')]
    public function getBooks()
    {
        try {
            $books = $this->requestHandler->getBooksHandler();
            return $this->responseService->formResponse($books,200,['groups'=>'books']);
        } catch (\Exception $e) {
            return new JsonResponse($e->getMessage());
        }
    }
    #[Route('authorCreate',methods: 'PUT')]
    public function authorCreate(Request $request)
    {
        try{
            $author = $this->requestHandler->authorCreateHandler($request);
            return $this->responseService->formResponse($author,200,['groups'=>'author']);
        } catch (\Exception $e) {
            return new JsonResponse($e->getMessage());
        }
    }
    #[Route('authorDelete',methods: 'DELETE')]
    public function authorDelete(Request $request){
        try {
            $message = $this->requestHandler->authorDeleteHandler($request);
            return $this->responseService->formResponse($message);
        } catch (\Exception $e) {
            return new JsonResponse($e->getMessage());
        }
    }
    #[Route('bookDelete',methods: 'DELETE')]
    public function bookDelete(Request $request)
    {
        try {
            $message = $this->requestHandler->bookDeleteHandler($request);
            return $this->responseService->formResponse($message);
        } catch (\Exception $e) {
            return new JsonResponse($e->getMessage());
        }
    }
    #[Route('publisherDelete',methods: 'DELETE')]
    public function publisherDelete(Request $request)
    {
        try {
            $message = $this->requestHandler->publisherDeleteHandler($request);
            return $this->responseService->formResponse($message);
        } catch (\Exception $e) {
            return new JsonResponse($e->getMessage());
        }
    }
    #[Route('publisherUpdate',methods: 'PATCH')]
    public function publisherUpdate(Request $request)
    {
        try {
            $message = $this->requestHandler->publisherUpdateHandler($request);
            return $this->responseService->formResponse($message,200,['groups'=>'publisher']);
        } catch (\Exception $e) {
            return new JsonResponse($e->getMessage());
        }
    }
}