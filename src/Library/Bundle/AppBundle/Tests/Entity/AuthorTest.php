<?php

namespace Library\Bundle\AppBundle\Tests\Entity;

use Library\Bundle\AppBundle\Entity\Author;

class AuthorTest extends \PHPUnit_Framework_TestCase
{
    public function testNotNull()
    {
        $author = new Author();
        $this->assertNotNull($author->getName());
        $this->assertNotNull($author->getBooks());
    }

    public function testTitle()
    {
        $author = new Author();

        $author->setName("AuthorName");
        $this->assertEquals("AuthorName", $author->getName());

        $author->setName(" AuthorName ");
        $this->assertEquals("AuthorName", $author->getName());
    }

    public function testBooks()
    {
        $author = new Author();

        $this->assertInstanceOf('\Doctrine\Common\Collections\ArrayCollection', $author->getBooks());
    }
}
