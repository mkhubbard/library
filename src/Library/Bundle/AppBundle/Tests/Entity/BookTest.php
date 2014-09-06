<?php

namespace Library\Bundle\AppBundle\Tests\Entity;

use Library\Bundle\AppBundle\Entity\Book;

class BookTest extends \PHPUnit_Framework_TestCase
{
    public function testNotNull()
    {
        $book = new Book();
        $this->assertNotNull($book->getAuthors());
        $this->assertNotNull($book->getPublisherText());
        $this->assertNotNull($book->getLccNumber());
        $this->assertNotNull($book->getIsbnDbBookId());
        $this->assertNotNull($book->getIsbnDbPublisherId());
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

        $book->setTitle(" TestTitle ");
        $this->assertEquals("TestTitle", $book->getTitle());
    }

    public function testPublisherTest()
    {
        $book = new Book();

        $book->setPublisherText("TestPublisherText");
        $this->assertEquals("TestPublisherText", $book->getPublisherText());

        $book->setPublisherText(" TestPublisherText ");
        $this->assertEquals("TestPublisherText", $book->getPublisherText());
    }    
    
    public function testLccNumberTest()
    {
        $book = new Book();

        $book->setLccNumber("TestLccNumber");
        $this->assertEquals("TestLccNumber", $book->getLccNumber());

        $book->setLccNumber(" TestLccNumber ");
        $this->assertEquals("TestLccNumber", $book->getLccNumber());
    }    
    
    public function testIsbnDbBookIdTest()
    {
        $book = new Book();

        $book->setIsbnDbBookId("TestIsbnDbBookId");
        $this->assertEquals("TestIsbnDbBookId", $book->getIsbnDbBookId());

        $book->setIsbnDbBookId(" TestIsbnDbBookId ");
        $this->assertEquals("TestIsbnDbBookId", $book->getIsbnDbBookId());
    }   
    
    public function testIsbnDbPublisherIdTest()
    {
        $book = new Book();

        $book->setIsbnDbPublisherId("TestIsbnDbPublisherId");
        $this->assertEquals("TestIsbnDbPublisherId", $book->getIsbnDbPublisherId());

        $book->setIsbnDbPublisherId(" TestIsbnDbPublisherId ");
        $this->assertEquals("TestIsbnDbPublisherId", $book->getIsbnDbPublisherId());
    }
}
