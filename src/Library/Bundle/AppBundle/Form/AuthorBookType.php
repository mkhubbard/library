<?php

namespace Library\Bundle\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AuthorBookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //$builder->add('role');
        $builder->add('author', new AuthorType());
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'         => 'Library\Bundle\AppBundle\Entity\AuthorBook',
            'cascade_validation' => true
        ));
    }

    public function getName()
    {
        return 'library_appbundle_authorbook';
    }
}
