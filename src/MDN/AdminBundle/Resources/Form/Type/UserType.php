<?php

namespace MDN\AdminBundle\Resources\Form\Type;

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
                ->setAttributes(array('class' => 'myform'))
                ->add('userId', 'hidden')
                ->add('username', 'email', array(
                    'attr' => array("autocomplete" => "off",),
                ))
                ->add('password', 'repeated', array(
                    'type' => 'password',
                    'options' => array(
                        'attr' => array(
                            'class' => 'password-field',
                            'min' => 6,
                            'max' => 255,
                        ),
                    ),
                    'required' => true,
                    'invalid_message' => 'The password fields must match.',
                    'first_options' => array('label' => 'Password'),
                    'second_options' => array('label' => 'Repeat Password'),
                ))
                ->add('role', 'entity', array(
                    'class' => 'MDNAdminBundle:Role',
                    'query_builder' => function($repository) { return $repository->createQueryBuilder('r')->where('r.deletedAt IS NULL')->orderBy('r.roleId', 'ASC'); },
                    'property' => 'name',
                    'multiple' => true,
                ))
                ->add('saveAndAdd', 'submit', array(
//                    'attr' => array('formnovalidate' => 'formnovalidate'),
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
//            'inherit_data' => true,
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
