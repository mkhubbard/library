<?php
namespace Library\Bundle\AppBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\ORM\NoResultException;

class UserRepository extends EntityRepository implements UserProviderInterface
{

    //<editor-fold desc="UserProviderInteface (Authentication)">
    /**
     * Satisfy UserProviderInterface which allows this repository to be used by Symfony for authentication.
     *
     * @param string $username
     * @return mixed|\Symfony\Component\Security\Core\User\UserInterface
     * @throws \Symfony\Component\Security\Core\Exception\UsernameNotFoundException
     */
    public function loadUserByUsername($username)
    {
        $query = $this->createQueryBuilder('u')
                ->where('u.username = :username OR u.email = :email')
                ->setParameter('username', $username)
                ->setParameter('email', $username)
                ->getQuery()
        ;

        try {
            $user = $query->getSingleResult();
        } catch (NoResultException $e) {
            throw new UsernameNotFoundException(sprintf('Unable to find an active BinaryGroveUserBundle:User object identified by "%s".', $username), 0, $e);
        }

        return $user;
    }


    /**
     * Satisfy UserProviderInterface which allows this repository to be used by Symfony for authentication.
     *
     * @param UserInterface $user
     * @return mixed|\Symfony\Component\Security\Core\User\UserInterface
     * @throws \Symfony\Component\Security\Core\Exception\UnsupportedUserException
     */
    public function refreshUser(UserInterface $user)
    {
        $class = get_class($user);
        if (!$this->supportsClass($class)) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $class));
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    /**
     * Satisfy UserProviderInterface which allows this repository to be used by Symfony for authentication.
     *
     * @param object $class
     * @return bool
     */
    public function supportsClass($class)
    {
        return ($this->getEntityName() === $class || is_subclass_of($class, $this->getEntityName()));
    }
    //</editor-fold>

    /**
     * Retrieve a list of users.
     *
     * @return \Doctrine\ORM\Tools\Pagination\Paginator
     */
    public function getUserList()
    {
        $query = $this->createQueryBuilder('u')
            ->orderBy('u.username')
            ->where("u.id > 1")
            ->getQuery()
        ;

        $paginator = new Paginator($query, $fetchJoinCollection = true);

        return $paginator;
    }

}
