<?php

namespace App\Service;

use App\Entity\Author;
use App\Entity\Book;

use App\Entity\Publisher;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BooksService
{
    private EntityRepository $repository;
    public function __construct(private EntityManagerInterface $entityManager,
                                private ValidatorInterface $validator)
    {
        $this->repository=$this->entityManager->getRepository(Book::class);
    }
    public function getBookByCriteria(Book $book)
    {
        return $this->repository->findOneBy(['name'=>$book->getName(),'year'=>$book->getYear()]);
    }
    public function getBooks()
    {
        return $this->repository->findAll();
    }
    public function getBook(int|string $id)
    {
        $book = $this->repository->find($id);
        if (!$book){
            throw new \Exception("There is no book with that id:$id");
        }
        return $book;
    }
    public function bookDelete(string|int $id)
    {
        $book = $this->getBook($id);
        $this->entityManager->remove($book);
        $this->entityManager->flush();
        return "Book $id was deleted";
    }
    /**
     * @throws \Exception
     * Создает новую книгу если книги с идентичными данными небыло в базе данных, удаляет связи с авторами и
     * создает новые указанные в запросе (поскольку это создание то решил сделать так)
     */
    public function createBook(Request $request, ArrayCollection $authors, Publisher $publisher = null)
    {
        $book = new Book();
        $book->setName($request->get('name'));
        $book->setYear($request->get('year'));
//        Проверяю наличие книги для исключение дублей
        if ($publisher) {
            $book->addPublisher($publisher);
        }
        $errors = $this->validator->validate($book,null,['create']);
//        Обработка ошибок валидации
        if (count($errors)>0){
            $errorMessage = '';
            foreach ($errors as $error){
                $errorMessage.='Book: '.$error->getPropertyPath().':'.$error->getMessage();
            }
            throw new \Exception($errorMessage);
        }
        $existed_book = $this->getBookByCriteria($book);
//        На текущий момент в книгу передаются все авторы этой книги в виде массива каждый раз и если книга уже существовала с таким же названием и годом издания то сохраняются новые авторы и издатели
        if ($existed_book){
            $book = $existed_book;
        }
        if ($publisher) {
            $book->addPublisher($publisher);
        }
        $book->removeAllAuthors();
        $book->setAuthors($authors);
        $this->entityManager->persist($book);
        $this->entityManager->flush();
        return $book;
    }
}