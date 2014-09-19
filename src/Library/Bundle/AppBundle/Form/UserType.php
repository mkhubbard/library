<?php

namespace Library\Bundle\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;

class UserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $data  = $event->getData();
            $form  = $event->getForm();
            $isNew = (!$data || null === $data->getId()) ;

            $form->add('username');

            $form->add('password', 'repeated', array(
                'type' => 'password',
                'invalid_message' => 'The password fields must match.',
                'options' => array('attr' => array('autocomplete' => 'off', 'class' => 'password-field')),
                'required' => true,
                'first_options'  => array('label' => 'Password'),
                'second_options' => array('label' => 'Repeat Password')
            ));

            $form->add('email');

            $form->add('role', 'choice', array(
                'choices' => array(
                    'ROLE_ADMIN' => 'Administrator',
                    'ROLE_USER' => 'Standard User'
                )
            ));

            $form->add('active', 'checkbox', array(
                'label'     => 'Allow Login?',
                'required'  => false
            ));
        });
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Library\Bundle\AppBundle\Entity\User',
            'validation_groups' => function(FormInterface $form) {
                    $data = $form->getData();
                    if (null === $data->getId()) {
                        return array('Default', 'password');
                    } else {
                        return array('Default');
                    }
            }
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'library_appbundle_user';
    }
}
