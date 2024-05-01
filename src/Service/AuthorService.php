<?php

namespace App\Service;

use App\Entity\Author;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AuthorService
{
    private EntityRepository $repository;
    public function __construct(private EntityManagerInterface $entityManager,
                                private ValidatorInterface $validator)
    {
        $this->repository=$this->entityManager->getRepository(Author::class);
    }
    public function getAuthor(int|string $id): Author
    {
        $author = $this->repository->find($id);
        if (!$author){
            throw new \Exception("There is no author with that id:$id");
        }
        return $author;
    }
    public function getAllAuthors()
    {
        return $this->repository->findAll();
    }
    /**
     * @throws \Exception
     */
    public function getAuthors(Array $ids): ArrayCollection
    {
        $authors = new ArrayCollection();
        foreach ($ids as $id) {
            if (!is_numeric($id)){
               throw new \Exception("author_id must be an integer");
            }
            $author = $this->getAuthor($id);
            $authors->add($author);
        }
        return $authors;
    }
    public function deleteAuthorsWithoutBooks()
    {
        return $this->repository->deleteAuthorsWithNoBooks();
    }
    public function getBooks(Request $request)
    {
        $author_id=$request->get('author_id');
        $author = $this->getAuthor($author_id);
        return $author->getBooks();
    }
    public function authorDelete(int|string $id)
    {
        $author=$this->getAuthor($id);
        $this->entityManager->remove($author);
        $this->entityManager->flush();
        return "Author $id was deleted";
    }
    /*
     * Создает автора или возвращает существующего
     */
    public function createAuthor(Request $request)
    {
        $author = new Author();
        $author->setName($request->get('name'));
        $author->setSurname($request->get('surname'));
        $errors = $this->validator->validate($author,null,['create']);
        if (count($errors)>0){
            $errorMessage = '';
            foreach ($errors as $error){
                $errorMessage.='Book: '.$error->getPropertyPath().':'.$error->getMessage();
            }
            throw new \Exception($errorMessage);
        }
        $existed_author = $this->getAuthorByCriteria($author);
        if ($existed_author){
            return $existed_author;
        } else {
            $this->entityManager->persist($author);
            $this->entityManager->flush();
            return $author;
        }
    }
    public function getAuthorByCriteria(Author $author)
    {
        return $this->repository->findOneBy(['name'=>$author->getName(),'surname'=>$author->getSurname()]);
    }
}