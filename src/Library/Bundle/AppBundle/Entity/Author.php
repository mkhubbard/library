<?php

namespace Library\Bundle\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * User Class
 *
 * @ORM\Table(name="author")
 * @ORM\Entity(repositoryClass="Library\Bundle\AppBundle\Entity\AuthorRepository")
 */
class Author
{
    /**
     * @var integer Author primary id.
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string Author name
     * @ORM\Column(name="name", type="string", length=64)
     *
     */
    private $name;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="AuthorBook", mappedBy="author")
     */
    private $books;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->name = '';
        $this->books = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = trim($name);
    }

    /**
     * @return ArrayCollection
     */
    public function getBooks()
    {
        return $this->books;
    }
}
