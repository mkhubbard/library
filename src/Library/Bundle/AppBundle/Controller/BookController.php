<?php

namespace Library\Bundle\AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Class BookController
 * @package Library\Bundle\AppBundle\Controller
 * @Route("/book")
 */
class BookController extends Controller
{
    /**
     * @Route("/", name="book_index")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        return array(
            'lookup_form' => $this->createBookLookupForm()->createView()
        );
    }

    /**
     * @Route("/", name="book_create")
     * @Method("POST")
     * @Template("LibraryAppBundle:Book:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $form = $this->createBookLookupForm();
        $form->handleRequest($request);

        if (!$form->isValid()) {
            foreach($form->getErrors(true) as $error) {
                $this->get('session')->getFlashBag()->add(
                    'error',
                    $error->getMessage()
                );
            }

            return $this->redirect($this->generateUrl('book_index'));
        }

        return array(
            'lookup' => $form->get('lookup')->getData()
        );
    }

    protected function createBookLookupForm()
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('book_create'))
            ->setMethod('POST')
            ->add('lookup', 'text', array(
                'label' => 'ISBN or Title',
                'required' => true,
                'constraints' => array(
                    new NotBlank(),
                    new Length(array('min' => 3)),
                ),
            ))
            ->add('submit', 'submit', array('label' => 'Lookup', 'attr' => array('class' => 'btn btn-primary')))
            ->getForm();
    }
}
