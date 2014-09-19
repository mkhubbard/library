<?php

namespace Library\Bundle\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use Library\Bundle\AppBundle\Entity\Author;
use Library\Bundle\AppBundle\Entity\Book;

/**
 * User Class
 *
 * @ORM\Table(name="author_book")
 * @ORM\Entity
 */
class AuthorBook
{
    /**
     * @var integer Author primary id.
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer Author primary id.
     * @ManyToOne(targetEntity="Author", inversedBy="books", cascade={"persist"})
     * @JoinColumn(name="author_id", referencedColumnName="id")
     */
    private $author;

    /**
     * @var integer Book primary id.
     * @ManyToOne(targetEntity="Book", inversedBy="authors")
     * @JoinColumn(name="book_id", referencedColumnName="id")
     */
    private $book;

    /**
     * @var integer Author role
     * !@ORM\Column(name="role", type="string", length=64)
     */
    private $role;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Author
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @return Book
     */
    public function getBook()
    {
        return $this->book;
    }

    /**
     * @param Author $author
     * @return Author
     */
    public function setAuthor(Author $author = null)
    {
        $this->author = $author;
        return $this->author;
    }

    /**
     * @param Book $book
     * @return Book
     */
    public function setBook(Book $book = null)
    {
        $this->book = $book;
        return $this->book;
    }

    public function setRole($role)
    {
        $this->role = $role;
    }

    public function getRole()
    {
        return $this->role;
    }

}
