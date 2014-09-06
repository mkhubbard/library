<?php

namespace Library\Bundle\AppBundle\Tests\Entity;

use Library\Bundle\AppBundle\Entity\User;

class UserTest extends \PHPUnit_Framework_TestCase
{

    public function testSalt()
    {
        $user = new User();

        $this->assertNull($user->getSalt());
    }

    public function testUsername()
    {
        $user = new User();

        $user->setUsername("TestUsername");
        $this->assertEquals("TestUsername", $user->getUsername());

        $user->setUsername(" TestUsername ");
        $this->assertEquals("TestUsername", $user->getUsername());

    }

    public function testPassword()
    {
        $user = new User();
        $user->setPassword('TestPassword');

        $this->assertEquals('TestPassword', $user->getPassword());
    }

    public function testEmail()
    {
        $user = new user();

        $user->setEmail('test@example.com');
        $this->assertEquals('test@example.com', $user->getEmail());

        $user->setEmail(' test@example.com ');
        $this->assertEquals('test@example.com', $user->getEmail());
    }

    public function testIsActive()
    {
        $user = new User();

        $this->assertTrue($user->isEnabled());

        $user->setActive(false);
        $this->assertFalse($user->isEnabled());
    }

    public function testAccountExpiration()
    {
        $user = new User();
        $this->assertTrue($user->isAccountNonExpired());
        $this->assertTrue($user->isAccountNonLocked());
        $this->assertTrue($user->isCredentialsNonExpired());
    }

    public function testRole()
    {
        $user = new User();

        // Default role
        $this->assertEquals($user->getRole(), 'ROLE_USER');
        $this->assertEquals($user->getRoles(), array('ROLE_USER'));

        // Change role
        $user->setRole('ROLE_ADMIN');
        $this->assertEquals($user->getRole(), 'ROLE_ADMIN');
        $this->assertEquals($user->getRoles(), array('ROLE_ADMIN'));

        // Invalid role
        $this->setExpectedException('InvalidArgumentException');
        $user->setRole('ROLE_INVALID');

    }

}
