<?php

namespace Library\Bundle\AppBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\Common\DataFixtures;
use Symfony\Bridge\Doctrine\DataFixtures\ContainerAwareLoader as DataFixturesLoader;

class ExtendedWebTestCase extends WebTestCase
{

    /** @var Client */
    protected $client;

    public function setUp()
    {
        $this->client = static::createClient();

        /*
        $container = $this->client->getContainer();
        $em = $container->get('doctrine.orm.entity_manager');

        $schemaTool = new SchemaTool($em);

        $mdf = $em->getMetadataFactory();

        $classes = $mdf->getAllMetadata();

        $schemaTool->dropDatabase();
        $schemaTool->createSchema($classes);

        $loader = new DataFixturesLoader($container);
        $loader->loadFromDirectory(__DIR__ . "/../DataFixtures");
        $purger = new \Doctrine\Common\DataFixtures\Purger\ORMPurger($em);
        $executor = new \Doctrine\Common\DataFixtures\Executor\ORMExecutor($em, $purger);
        $executor->execute($loader->getFixtures());
        */
    }

    public function tearDown()
    {
        unset($this->client);
    }


    protected function runConsole($command, Array $options = array())
    {
        /*
        $kernel = $this->container->get('kernel');

        $application = new \Symfony\Bundle\FrameworkBundle\Console\Application($kernel);
        $application->setAutoExit(false);

        $options["-e"] = $kernel->getEnvironment();
        $options["-q"] = null;
        $options = array_merge($options, array('command' => $command));

        $result = $application->run(new \Symfony\Component\Console\Input\ArrayInput($options));

        unset($application);

        return $result;
        */
    }

}