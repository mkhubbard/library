<?php

namespace Library\Bundle\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * User Class
 *
 * @ORM\Table(name="book")
 * @ORM\Entity(repositoryClass="Library\Bundle\AppBundle\Entity\BookRepository")
 */
class Book
{
    /**
     * @var integer Book primary ID.
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * @OneToMany(targetEntity="AuthorBook", mappedBy="book")
     */
    //private $authors;

    /**
     * @var string Book title.
     * @ORM\Column(name="title", type="string", length=128)
     */
    private $title;

    /**
     * @var string Publisher description text.
     * @ORM\Column(name="publisher_text", type="string", length=128)
     */
    private $publisherText;


    /**
     * @var string Library of Congress number.
     * @ORM\Column(name="lcc_number", type="string", length=24)
     */
    private $lccNumber;

    /**
     * @var string ISBNDB.COM Book ID
     * @ORM\Column(name="isbndb_book_id", type="string", length=64)
     */
    private $isbnDbBookId;

    /**
     * @var string ISBNDB.COM Publisher ID
     * @ORM\Column(name="isbndb_publisher_id", type="string", length=64)
     */
    private $isbnDbPublisherId;


    public function __construct()
    {
        $this->publisherText = '';
        $this->lccNumber = '';
        $this->isbnDbBookId = '';
        $this->isbnDbPublisherId = '';
        $this->authors = new ArrayCollection();
        $this->owners = new ArrayCollection();
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
     * @return ArrayCollection
     */
    public function getOwners()
    {
        return $this->getOwners();
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = trim($title);
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $publisherText
     */
    public function setPublisherText($publisherText)
    {
        $this->publisherText = trim($publisherText);
    }

    /**
     * @return string
     */
    public function getPublisherText()
    {
        return $this->publisherText;
    }

    /**
     * @param string $lccNumber
     */
    public function setLccNumber($lccNumber)
    {
        $this->lccNumber = trim($lccNumber);
    }

    /**
     * @return string
     */
    public function getLccNumber()
    {
        return $this->lccNumber;
    }

    /**
     * @param string $isbnDbBookId
     */
    public function setIsbnDbBookId($isbnDbBookId)
    {
        $this->isbnDbBookId = trim($isbnDbBookId);
    }

    /**
     * @return string
     */
    public function getIsbnDbBookId()
    {
        return $this->isbnDbBookId;
    }

    /**
     * @param string $isbnDbPublisherId
     */
    public function setIsbnDbPublisherId($isbnDbPublisherId)
    {
        $this->isbnDbPublisherId = trim($isbnDbPublisherId);
    }

    /**
     * @return string
     */
    public function getIsbnDbPublisherId()
    {
        return $this->isbnDbPublisherId;
    }

}
