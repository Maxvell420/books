<?php

namespace App\Repository;

use App\Entity\Author;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Author>
 *
 * @method Author|null find($id, $lockMode = null, $lockVersion = null)
 * @method Author|null findOneBy(array $criteria, array $orderBy = null)
 * @method Author[]    findAll()
 * @method Author[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuthorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Author::class);
    }
    public function deleteAuthorsWithNoBooks()
    {
        $authors = $this->createQueryBuilder('a')
            ->leftJoin('a.books', 'b')
            ->where('b.name IS NULL')
            ->getQuery()->execute();
        foreach ($authors as $author) {
            $this->getEntityManager()->remove($author);
        }
        $this->getEntityManager()->flush();
    }
}
