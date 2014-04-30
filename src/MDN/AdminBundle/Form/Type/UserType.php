<?php

namespace MDN\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{

    /**
     * 
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->setMethod('post')
                ->add('userId', 'hidden')
                ->add('username', 'email', array('attr' => array(
//                        'maxlength' => 255,
//                        'required' => true,
                    )
                ))
//                ->add('password', 'password', array('attr' => array(
////                        'maxlength' => 255,
////                        'required' => true,
//                    )
//                ))
                ->add('password', 'repeated', array('attr' => array(
                        'type' => 'password',
                        'invalid_message' => 'The password fields must match.',
                        'first_options'  => array('label' => 'Password'),
                        'second_options' => array('label' => 'Repeat Password'),
                    )
                ))
                ->add('enabled', 'choice', array(
                    'label' => 'Enabled',
                    'choices' => array(
                        'Y' => 'Yes',
                        'N' => 'No',
                    ),
//                    'required'    => true,
                    'empty_data' => null,
                ))
                ->add('saveAndAdd', 'submit', array(
                    'attr' => array('formnovalidate' => 'formnovalidate'),
        ));
    }

    /**
     * 
     * @param \Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MDN\AdminBundle\Entity\User',
        ));
    }

    /**
     * 
     * @return string
     */
    public function getName()
    {
        return 'user';
    }

}
