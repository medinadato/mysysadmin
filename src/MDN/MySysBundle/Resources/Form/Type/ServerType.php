<?php

namespace MDN\MySysBundle\Resources\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ServerType extends AbstractType
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
                ->add('serverId', 'hidden')
                ->add('name', 'text', array(
                ))
                ->add('ip', 'text', array(
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
            'data_class' => 'MDN\MySysBundle\Entity\Server',
//            'inherit_data' => true,
        ));
    }

    /**
     * 
     * @return string
     */
    public function getName()
    {
        return 'server';
    }

}
