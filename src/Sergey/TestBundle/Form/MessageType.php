<?php

namespace Sergey\TestBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MessageType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setAction($options['ajax_action_url'])
            ->add('message', 'wysiwyg')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver
            ->setDefaults([
                'data_class' => 'Sergey\TestBundle\Entity\Message'
            ])
            ->setRequired([
                'ajax_action_url',
            ])
            ->setAllowedTypes([
                'ajax_action_url' => 'string'
            ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sergey_testbundle_message';
    }
}
