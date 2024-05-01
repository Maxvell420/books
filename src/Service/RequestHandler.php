<?php

namespace App\Service;

use App\Service\AuthorService;
use App\Service\BooksService;
use App\Service\PublisherService;
use PHPUnit\Util\Exception;
use Symfony\Component\HttpFoundation\Request;
/*
 * Класс вызывающий различные сервисы основываясь на запросах
 */
class RequestHandler
{
    public function __construct(private AuthorService $authorService,
                                private BooksService $booksService,
                                private PublisherService $publisherService)
    {}
    public function createBookHandler(Request $request)
    {
        $this->validateRequest($request);

        $author_ids=$request->get('authors');
        $publisher_id=$request->get('publisher_id');

        $publisher = $publisher_id?$this->publisherService->getPublisher($publisher_id):null;
        $authors = $author_ids?$this->authorService->getAuthors($author_ids):throw new \Exception('place authors ids in request');

        return $this->booksService->createBook($request,$authors,$publisher);
    }
    public function authorCreateHandler(Request $request)
    {
        return $this->authorService->createAuthor($request);
    }
    public function getBooksHandler()
    {
        return $this->booksService->getBooks();
    }
    public function authorDeleteHandler(Request $request)
    {
        $this->validateRequest($request);
        $author_id=$request->get('author_id')??throw new Exception('place author_id in request');
        return $this->authorService->authorDelete($author_id);
    }
    public function bookDeleteHandler(Request $request)
    {
        $this->validateRequest($request);
        $book_id= $request->get('book_id')??throw new Exception('place book_id in request');
        return $this->booksService->bookDelete($book_id);
    }
    public function publisherDeleteHandler(Request $request)
    {
        $this->validateRequest($request);
        $publisher_id= $request->get('publisher_id')??throw new Exception('place publisher_id in request');
        return $this->publisherService->deletePublisher($publisher_id);
    }
    public function publisherUpdateHandler(Request $request)
    {
        $this->validateRequest($request);
        $publisher_id= $request->get('publisher_id')??throw new Exception('place publisher_id in request');
        return $this->publisherService->updatePublisher($request,$publisher_id);
    }
//    Валидирует реквесты на соответствие типам данных
    private function validateRequest(Request $request)
    {
        $author_ids=$request->get('authors');
        if ($author_ids and !is_array($author_ids)){
            throw new \Exception("authors parameter must be an array like authors[]=id");
        }
        $publisher_id=$request->get('publisher_id');
        if ($publisher_id and !is_numeric($publisher_id)){
            throw new \Exception("publisher parameter must be an integer");
        }
        $author_id=$request->get('author_id');
        if ($author_id and !is_numeric($author_id)){
            throw new \Exception("authors parameter must be an integer");
        }
        $book_id=$request->get('book_id');
        if ($book_id and !is_numeric($book_id)){
            throw new \Exception("book parameter must be an integer");
        }
        $publisher_id=$request->get('publisher_id');
        if ($publisher_id and !is_numeric($publisher_id)){
            throw new \Exception("publisher parameter must be an integer");
        }
    }
}