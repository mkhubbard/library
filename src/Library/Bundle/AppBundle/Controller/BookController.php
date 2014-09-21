<?php

namespace Library\Bundle\AppBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Library\Bundle\AppBundle\Entity\Author;
use Library\Bundle\AppBundle\Entity\AuthorBook;
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
            'lookup_form' => $this->createLookupForm()->createView()
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
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'form'        => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }


    /**
     * @Route("/lookup", name="book_lookup")
     * @Method("POST")
     * @Template()
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function lookupAction(Request $request)
    {
        $form = $this->createLookupForm();
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

        if ($request->request->has('book_data')) {
            $this->prefillEntity($entity, $request->request->get('book_data'));
            $form = $this->createCreateForm($entity);
        } else {
            $form = $this->createCreateForm($entity);
            $form->handleRequest($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                foreach($entity->getAuthors() as $author) {
                    $check = $em->getRepository('LibraryAppBundle:Author')->loadByNameCanonical($author->getAuthor()->getNameCanonical());
                    if (null !== $check) {
                        $author->setAuthor($check);
                    }
                }

                $em = $this->getDoctrine()->getManager();
                $em->persist($entity);
                $em->flush();

                return $this->redirect($this->generateUrl('book_index', array('id' => $entity->getId())));
            }
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

        $deleteForm = $this->createDeleteForm($id);
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
            'form'        => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }


    /**
     * Deletes a Book entity.
     *
     * @Route("/{id}", name="book_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('LibraryAppBundle:Book')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find User entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('book'));
    }

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
     * Creates a form to edit a Book entity.
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
     * Creates a form to delete a Book entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('book_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete', 'attr' => array('class' => 'btn btn-danger')))
            ->getForm()
            ;
    }

    protected function createLookupForm()
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('book_lookup'))
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

    /**
     * @param string $lookup
     * @return array
     */
    private function lookupGoogleBooks($lookup)
    {
        $client  = new \Google_Client();
        $service = new \Google_Service_Books($client);
        $params  = array();
        $result  = array();

        $client->setApplicationName("the-library-1026");
        $client->setDeveloperKey($this->container->getParameter('google_api_key'));

        $rawResult = $service->volumes->listVolumes($lookup, $params);

        $result = get_object_vars($rawResult);
        $result['items'] = array();

        foreach($rawResult->items as $rawItem) {
            if ($rawItem->offsetExists('volumeInfo')) {
                $item = get_object_vars($rawItem->volumeInfo);
                $item['industryIdentifiers'] = array();
                $item['imageLinks'] = array();

                foreach($rawItem->volumeInfo->industryIdentifiers as $id) {
                    $item['industryIdentifiers'][] = get_object_vars($id);
                }

                if ($rawItem->volumeInfo->offsetExists('imageLinks')) {
                    $item['imageLinks'] = get_object_vars($rawItem->volumeInfo->imageLinks);
                }

                $result['items'][] = $item;
            }
        }

        return $result;
    }


    /**
     * @param Book $entity
     * @param string $rawData
     */
    private function prefillEntity(Book $entity, $rawData)
    {
        $data = json_decode($rawData);

        if (false !== $data) {

            if (isset($data)) {
                $entity->setTitle($data->title);
                $entity->setDescription($data->description);

                if (!empty($data->authors)) {
                    foreach($data->authors as $name) {
                        $author = new Author();
                        $author->setName($name);

                        $authorBook = new AuthorBook();
                        $authorBook->setAuthor($author);

                        $entity->addAuthor($authorBook);
                    }
                }
            }

            // Identifiers
            if (!empty($data->industryIdentifiers)) {
                foreach($data->industryIdentifiers as $id) {
                    switch(strtoupper($id->type)) {
                        case 'ISBN_10':
                            $entity->setIsbn10($id->identifier);
                            break;

                        case 'ISBN_13':
                            $entity->setIsbn13($id->identifier);
                            break;

                        default:
                            // nothing
                    }
                }
            }

        }
    }
}
