<?php

namespace Library\Bundle\AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{

    public function testCompleteScenario()
    {
        // Create a new client to browse the application
        $client = static::createClient();

        // Create a new entry in the database
        $crawler = $client->request('GET', '/en/user/', array(), array(), array(
            'PHP_AUTH_USER' => 'administrator',
            'PHP_AUTH_PW'   => 'default'
        ));

        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for GET /user/");
        $crawler = $client->click($crawler->selectLink('Create a new user')->link());

        // Fill in the form and submit it
        $form = $crawler->selectButton('Create')->form(array(
            'library_bundle_appbundle_user[username]' => 'Test',
            'library_bundle_appbundle_user[password][first]' => 'TestPassword',
            'library_bundle_appbundle_user[password][second]' => 'TestPassword',
            'library_bundle_appbundle_user[email]' => 'foo@example.com',
            'library_bundle_appbundle_user[role]' => 'ROLE_USER',
            'library_bundle_appbundle_user[active]' => true
        ));

        $client->submit($form);
        $crawler = $client->followRedirect();

        // Check data in the show view
        $this->assertGreaterThan(0, $crawler->filter('td:contains("Test")')->count(), 'Missing element td:contains("Test")');

        // Edit the entity
        $crawler = $client->click($crawler->selectLink('Edit')->link());

        $form = $crawler->selectButton('Update')->form(array(
            'library_bundle_appbundle_user[username]' => 'Foo',
            'library_bundle_appbundle_user[password][first]' => 'FooPassword',
            'library_bundle_appbundle_user[password][second]' => 'FooPassword',
            'library_bundle_appbundle_user[email]' => 'foo@example.com',
            'library_bundle_appbundle_user[role]' => 'ROLE_USER',
            'library_bundle_appbundle_user[active]' => true
        ));

        $client->submit($form);
        $crawler = $client->followRedirect();

        // Check the element contains an attribute with value equals "Foo"
        $this->assertGreaterThan(0, $crawler->filter('[value="Foo"]')->count(), 'Missing element [value="Foo"]');

        // Delete the entity
        $client->submit($crawler->selectButton('Delete')->form());
        $crawler = $client->followRedirect();

        // Check the entity has been delete on the list
        $this->assertNotRegExp('/Foo/', $client->getResponse()->getContent());
    }

}
