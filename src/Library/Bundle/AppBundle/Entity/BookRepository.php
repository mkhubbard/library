<?php
namespace BinaryGrove\AppBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\ORM\NoResultException;

class BookRepository extends EntityRepository
{
    /**
     * Load Book by Id
     *
     * @param $bookId
     * @throws \Exception
     * @internal param int $id
     * @return mixed|\Library\Bundle\AppBundle\Entity\Book
     */
    public function loadById($bookId)
    {
        $query = $this->createQueryBuilder('b')
                ->where('b.id = :id')
                ->setParameter('id', $bookId)
                ->getQuery()
        ;

        try {
            $book = $query->getSingleResult();
        } catch (NoResultException $e) {
            throw new \Exception(sprintf('Unable to find an active LibraryAppBundle:Book object identified by "%s".', $bookId));
        }

        return $book;
    }


    /**
     * Retrieve a list of books.
     *
     * @param $page
     * @return \Doctrine\ORM\Tools\Pagination\Paginator
     */
    /*
    public function getBookList($page)
    {
        $query = $this->createQueryBuilder('b')
            ->orderBy('b.title')
            ->setFirstResult(max(0, $page-1)*10)
            ->setMaxResults(10)
            ->getQuery()
        ;

        $paginator = new Paginator($query, $fetchJoinCollection = true);

        return $paginator;
    }
    */

}
