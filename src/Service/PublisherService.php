<?php

namespace App\Service;

use App\Entity\Book;
use App\Entity\Publisher;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PublisherService
{
    private EntityRepository $repository;
    public function __construct(private EntityManagerInterface $entityManager,
                                private ValidatorInterface $validator)
    {
        $this->repository=$entityManager->getRepository(Publisher::class);
    }
    public function createPublisher(Request $request): Publisher
    {
        $publisher = new Publisher();
        $publisher->setName($request->get('name'));
        $publisher->setAddress($request->get('address'));
        $errors = $this->validator->validate($publisher,null,['create']);
        if (count($errors)>0){
            $errorMessage = '';
            foreach ($errors as $error){
                $errorMessage.='Book: '.$error->getPropertyPath().':'.$error->getMessage();
            }
            throw new \Exception($errorMessage);
        }
        $this->entityManager->persist($publisher);
        $this->entityManager->flush();
        return $publisher;
    }

    /**
     * @throws \Exception
     */
    public function getPublisher(int|string $id): Publisher
    {
        $publisher = $this->repository->find($id);
        if (!$publisher){
            throw new \Exception("There is no publisher with that id:$id");
        }
        return $publisher;
    }
    public function updatePublisher(Request $request,int|string $id): Publisher
    {
        $publisher = $this->getPublisher($id);
        $name = $request->get('name');
        $address = $request->get('address');
        if ($name){
            $publisher->setName($name);
        }
        if ($address){
            $publisher->setAddress($address);
        }
        $this->validator->validate($publisher,null,['update']);
        $this->entityManager->persist($publisher);
        $this->entityManager->flush();
        return $publisher;
    }
    public function deletePublisher(int|string $id)
    {
        $publisher = $this->getPublisher($id);
        $books = $publisher->getBooks();
        foreach ($books as $book){
            $this->entityManager->remove($book);
        }
        $this->entityManager->remove($publisher);
        $this->entityManager->flush();
        return "Publisher $id has been deleted";
    }
}