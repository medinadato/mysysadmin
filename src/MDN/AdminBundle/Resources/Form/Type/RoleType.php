<?php

namespace MDN\AdminBundle\Resources\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RoleType extends AbstractType
{

    /**
     * 
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->setMethod('post')
                ->add('roleId', 'hidden')
                ->add('code', 'text', array('attr' => array(
                        'maxlength' => 255,
                        'required' => true,
                    )
                ))
                ->add('name', 'text', array('attr' => array(
                        'maxlength' => 255,
                        'required' => true,
                    )
                ))
                ->add('enabled', 'choice', array(
                    'label' => 'Enabled',
                    'choices' => array(
                        'Y' => 'Yes',
                        'N' => 'No',
                    ),
                    'required'    => true,
                    'empty_data'  => null,
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
            'data_class' => 'MDN\AdminBundle\Entity\Role',
        ));
    }

    /**
     * 
     * @return string
     */
    public function getName()
    {
        return 'role';
    }

}
