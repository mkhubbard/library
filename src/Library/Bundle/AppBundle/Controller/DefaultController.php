<?php

namespace Library\Bundle\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Class DefaultController
 * @package Library\Bundle\AppBundle\Controller
 */
class DefaultController extends Controller
{
    /**
     * @Route("/", name="_index")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        return array('name' => 'Works');
    }
}
