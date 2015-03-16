<?php

namespace MDN\MySysBundle\Resources\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DomainType extends AbstractType
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
                ->add('domainId', 'hidden')
                ->add('server', 'entity', array(
                    'class' => 'MDNMySysBundle:Server',
                    'property' => 'combinedName',
                    'multiple' => false,
                ))
                ->add('url', 'text', array(
                    'required' => true,
                ))
                ->add('rootPath', 'text', array(
                    'required' => true,
                ))
                ->add('hostConfPath', 'text', array(
                    'required' => false,
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
            'data_class' => 'MDN\MySysBundle\Entity\Domain',
//            'inherit_data' => true,
        ));
    }

    /**
     * 
     * @return string
     */
    public function getName()
    {
        return 'domain';
    }

}
