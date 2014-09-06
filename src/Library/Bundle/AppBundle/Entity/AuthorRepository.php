<?php
namespace Library\Bundle\AppBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;

class AuthorRepository extends EntityRepository
{

    /**
     * Load Book by Id
     *
     * @param $authorId
     * @throws \Exception
     * @internal param int $id
     * @return mixed|\Library\Bundle\AppBundle\Entity\Author
     */
    public function loadById($authorId)
    {
        $query = $this->createQueryBuilder('a')
                ->where('a.id = :id')
                ->setParameter('id', $authorId)
                ->getQuery()
        ;

        try {
            $author = $query->getSingleResult();
        } catch (NoResultException $e) {
            throw new \Exception(sprintf('Unable to find an active LibraryAppBundle:Author object identified by "%s".', $authorId));
        }

        return $author;
    }
}
