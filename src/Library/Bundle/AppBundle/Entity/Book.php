<?php

namespace Library\Bundle\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\Common\Collections\ArrayCollection;
use Library\Bundle\AppBundle\Util\Canonicalizer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User Class
 *
 * @ORM\Table(name="book")
 * @ORM\Entity(repositoryClass="Library\Bundle\AppBundle\Entity\BookRepository")
 */
class Book
{
    /**
     * @var integer Book primary id.
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string Book title.
     * @ORM\Column(name="title", type="string", length=128)
     * @Assert\NotBlank
     * @Assert\Length(
     *      max = 128,
     *      maxMessage = "book_title_max_length"
     * )
     */
    private $title;

    /**
     * @var string Canonicalized version of the book title.
     * @ORM\Column(name="title_canonical", type="string", length=128)
     */
    private $titleCanonical;

    /**
     * @var string Book description text.
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var string Book title.
     * @ORM\Column(name="isbn10", type="string", length=10)
     * @Assert\Isbn(
     *     type = "isbn10",
     *     message = "isbn10_message"
     * )
     */
    private $isbn10;

    /**
     * @var string Book title.
     * @ORM\Column(name="isbn13", type="string", length=13)
     * @Assert\Isbn(
     *     type = "isbn13",
     *     message = "isbn13_message"
     * )
     */
    private $isbn13;

    /**
     * @var string Library of Congress number.
     * @ORM\Column(name="lcc_number", type="string", length=24)
     * @Assert\Length(
     *      max = 24,
     *      maxMessage = "lcc_max_length"
     * )
     */
    private $lccNumber;

    /**
     * @OneToMany(targetEntity="AuthorBook", mappedBy="book", cascade={"persist", "remove"})
     * @Assert\Count(
     *      min = 1,
     *      minMessage = "author_min_count"
     * )
     */
    private $authors;

    public function __construct()
    {
        $this->id = null;
        $this->title = '';
        $this->titleCanonical = '';
        $this->description = '';
        $this->isbn10 = '';
        $this->isbn13 = '';
        $this->lccNumber = '';
        $this->authors = new ArrayCollection();
    }

    /**
     * Retrieve the unique id for this book.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return ArrayCollection
     */
    public function getAuthors()
    {
        return $this->authors;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
        $this->titleCanonical = Canonicalizer::canonicalize($this->getTitle());
    }

    /**
     * @return string
     */
    public function getTitleCanonical()
    {
        return $this->titleCanonical;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getIsbn10()
    {
        return $this->isbn10;
    }

    /**
     * @param string $isbn10
     */
    public function setIsbn10($isbn10)
    {
        $this->isbn10 = $isbn10;
    }

    /**
     * @return string
     */
    public function getIsbn13()
    {
        return $this->isbn13;
    }

    /**
     * @param string $isbn13
     */
    public function setIsbn13($isbn13)
    {
        $this->isbn13 = $isbn13;
    }

    /**
     * @return string
     */
    public function getLccNumber()
    {
        return $this->lccNumber;
    }

    /**
     * @param string $lccNumber
     */
    public function setLccNumber($lccNumber)
    {
        $this->lccNumber = $lccNumber;
    }

    /**
     * @param AuthorBook $author
     */
    public function addAuthor(AuthorBook $author)
    {
        $author->setBook($this);
        $this->authors->add($author);
    }

    /**
     * @param AuthorBook $author
     */
    public function removeAuthor(AuthorBook $author)
    {
        $author->setBook(null);
        $this->authors->removeElement($author);
    }
}
