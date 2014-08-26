<?php

namespace Library\Bundle\AppBundle\Tests\Controller;

use Library\Bundle\AppBundle\Tests\ExtendedWebTestCase;
use Symfony\Component\Security\Core\SecurityContext;

class SecurityControllerTest extends ExtendedWebTestCase
{
    public function testIndex()
    {
        $this->client->request('GET', '/');

        $this->assertTrue($this->client->getResponse()->isRedirect('http://localhost/en/login'));
    }

    public function testFailedLogin()
    {
        $crawler = $this->client->request('get', '/en/login')->selectButton('login');
        $form = $crawler->form();

        $form['_username'] = 'administrator';
        $form['_password'] = 'wrong';
        $this->client->submit($form);

        /** @var SecurityContext $security */
        $security = self::$kernel->getContainer()->get('security.context');
        $this->assertTrue(($security->getToken() === null), 'Logged in user is not authenticated.');
    }

    public function testUsernameLogin()
    {
        $crawler = $this->client->request('get', '/en/login')->selectButton('login');
        $form = $crawler->form();

        $form['_username'] = 'administrator';
        $form['_password'] = 'default';
        $this->client->submit($form);

        /** @var SecurityContext $security */
        $security = self::$kernel->getContainer()->get('security.context');
        $this->assertTrue(($security->getToken() !== null) && $security->getToken()->isAuthenticated(), 'Logged in user is authenticated.');
        $this->assertTrue($security->isGranted('ROLE_ADMIN'), 'Logged in user has ROLE_ADMIN');
    }

    public function testEmailLogin()
    {
        $crawler = $this->client->request('get', '/en/login')->selectButton('login');
        $form = $crawler->form();

        $form['_username'] = 'administrator@example.com';
        $form['_password'] = 'default';
        $this->client->submit($form);

        /** @var SecurityContext $security */
        $security = self::$kernel->getContainer()->get('security.context');
        $this->assertTrue(($security->getToken() !== null) && $security->getToken()->isAuthenticated(), 'Logged in user is authenticated.');
        $this->assertTrue($security->isGranted('ROLE_ADMIN'), 'Logged in user has ROLE_ADMIN');
    }
}