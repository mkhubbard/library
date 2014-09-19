<?php

namespace Library\Bundle\AppBundle\Tests\Entity;

use Library\Bundle\AppBundle\Entity\Book;

class BookTest extends \PHPUnit_Framework_TestCase
{
    public function testNull()
    {
        $book = new Book();

        $this->assertNull($book->getId());

        $this->assertNotNull($book->getAuthors());
        $this->assertNotNull($book->getTitle());
        $this->assertNotNull($book->getDescription());
        $this->assertNotNull($book->getIsbn10());
        $this->assertNotNull($book->getIsbn13());
        $this->assertNotNull($book->getLccNumber());
    }

    public function testAuthors()
    {
        $book = new Book();
        $this->assertInstanceOf('\Doctrine\Common\Collections\ArrayCollection', $book->getAuthors());
    }

    public function testTitle()
    {
        $book = new Book();

        $book->setTitle("TestTitle");
        $this->assertEquals("TestTitle", $book->getTitle());
        $this->assertEquals("testtitle", $book->getTitleCanonical());

        $book->setTitle(" TestTitle With Translations ");
        $this->assertEquals("testtitle_with_translations", $book->getTitleCanonical());
    }

    public function testTitleCanonical()
    {
        $book = new Book();

        $book->setTitle(" TestTitle ");
        $this->assertEquals("testtitle", $book->getTitleCanonical());

        $book->setTitle("TestTitle With Translations ~!@#$");
        $this->assertEquals("testtitle_with_translations______", $book->getTitleCanonical());
    }

    public function testDescription()
    {
        $book = new Book();

        $book->setTitle("TestDescription");
        $this->assertEquals("TestDescription", $book->getTitle());
    }

    public function testIsbn10()
    {
        $book = new Book();

        $book->setIsbn10("TestIsbn10");
        $this->assertEquals("TestIsbn10", $book->getIsbn10());
    }
    
    public function testIsbn13()
    {
        $book = new Book();

        $book->setIsbn13("TestIsbn13");
        $this->assertEquals("TestIsbn13", $book->getIsbn13());
    }    
    
    public function testLccNumber()
    {
        $book = new Book();

        $book->setLccNumber("TestLccNumber");
        $this->assertEquals("TestLccNumber", $book->getLccNumber());
    }
}
