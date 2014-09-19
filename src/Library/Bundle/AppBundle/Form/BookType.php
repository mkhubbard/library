<?php

namespace Library\Bundle\AppBundle\Form;

use Library\Bundle\AppBundle\Form\DataTransformer\EmptyStringTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $emptyStringTransformer = new EmptyStringTransformer();

        $builder->add('title', 'text');

        $builder->add(
             $builder->create('description', 'textarea', array(
                'required'      => false,
                'empty_data'    => ''
            ))->addModelTransformer($emptyStringTransformer)
        );

        $builder->add(
            $builder->create('isbn10', 'text', array(
                'required'      => false,
                'empty_data'    => ''
            ))->addModelTransformer($emptyStringTransformer)
        );

        $builder->add(
            $builder->create('isbn13', 'text', array(
                'required'      => false,
                'empty_data'    => ''
            ))->addModelTransformer($emptyStringTransformer)
        );

        $builder->add('authors', 'collection', array(
            'type'          => new AuthorBookType(),
            'allow_add'     => true,
            'allow_delete'  => true,
            'delete_empty'  => true,
            'by_reference'  => false
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Library\Bundle\AppBundle\Entity\Book',
        ));
    }

    public function getName()
    {
        return 'library_appbundle_book';
    }
}
