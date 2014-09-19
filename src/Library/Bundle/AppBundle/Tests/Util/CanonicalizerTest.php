<?php

namespace Library\Bundle\AppBundle\Tests\Entity;

use Library\Bundle\AppBundle\Util\Canonicalizer;

class StandardCanonicalizerTest extends \PHPUnit_Framework_TestCase
{
    public function testCanonicalizer()
    {
        $set = array(
            array(
                'input'  => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890_',
                'output' => 'abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyz1234567890_'
            ),
            array(
                'input'  => ' Simple Test ',
                'output' => 'simple_test'
            ),
            array(
                'input'  => '`~!@#$%^&*()-_=+\|[]{};:\'",<.>/?',
                'output' => '________________________________'
            )
        );

        foreach($set as $test) {
            $this->assertEquals($test['output'], Canonicalizer::canonicalize($test['input']));
        }
    }
}
