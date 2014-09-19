<?php

namespace Library\Bundle\AppBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Library\Bundle\AppBundle\Entity\Book;
use Library\Bundle\AppBundle\Form\BookType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

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
     * @Route("/new", name="book_add")
     * @Method("GET")
     * @Template("LibraryAppBundle:Book:new.html.twig")
     * @param Request $request
     * @return array
     */
    public function newAction(Request $request)
    {
        $entity = new Book();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing User entity.
     *
     * @Route("/{id}/edit", name="book_edit")
     * @Method("GET")
     * @Template()
     * @param $id
     * @return array
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('LibraryAppBundle:Book')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find book entity.');
        }

        $editForm = $this->createEditForm($entity);
        //$deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'form'        => $editForm->createView(),
            //'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * @Route("/", name="book_create")
     * @Method("POST")
     * @Template("LibraryAppBundle:Book:new.html.twig")
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request)
    {
        $entity = new Book();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('book_index', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Edits an existing User entity.
     *
     * @Route("/{id}", name="book_update")
     * @Method("PUT")
     * @Template("LibraryAppBundle:Book:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('LibraryAppBundle:Book')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Book entity.');
        }

        $originalAuthors = new ArrayCollection();
        foreach($entity->getAuthors() as $author) {
            $originalAuthors->add($author);
        }

        //$deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            foreach($entity->getAuthors() as $author) {
                $check = $em->getRepository('LibraryAppBundle:Author')->loadByNameCanonical($author->getAuthor()->getNameCanonical());
                if (null !== $check) {
                    $author->setAuthor($check);
                }
            }

            foreach ($originalAuthors as $author) {
                if (false === $entity->getAuthors()->contains($author)) {
                    $em->remove($author);
                }
            }


            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('book_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
           // 'delete_form' => $deleteForm->createView(),
        );
    }


    /*
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

        $results = $this->lookupGoogleBooks($form->get('lookup')->getData());

        return array(
            'results' => $results
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
    */

    /**
     * Creates a form to create a Book entity.
     *
     * @param Book $entity The entity
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Book $entity)
    {
        $form = $this->createForm(new BookType(), $entity, array(
            'action' => $this->generateUrl('book_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create', 'attr' => array('class' => 'btn btn-primary')));

        return $form;
    }

    /**
     * Creates a form to edit  a Book entity.
     *
     * @param Book $entity The entity
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Book $entity)
    {
        $form = $this->createForm(new BookType(), $entity, array(
            'action' => $this->generateUrl('book_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Save', 'attr' => array('class' => 'btn btn-primary')));

        return $form;
    }

    /**
     * @param string $lookup
     * @return array
     */
    private function lookupGoogleBooks($lookup)
    {
        $client = new \Google_Client();
        $client->setApplicationName("the-library-1026 ");
        $client->setDeveloperKey();
        $service = new \Google_Service_Books($client);
        //$optParams = array('filter' => 'free-ebooks');
        $optParams = array();
        $results = $service->volumes->listVolumes($lookup, $optParams);
        return $results;
    }
}
