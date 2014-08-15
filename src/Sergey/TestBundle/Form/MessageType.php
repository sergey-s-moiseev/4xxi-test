<?php

namespace Sergey\TestBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToStringTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
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
            ->add('message', 'wysiwyg');
        if (!is_null($options['edit_id']))
        {
            $builder->add('id', 'hidden', [
                'mapped' => false,
                'data' => $options['edit_id']
            ]);
        }
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver
            ->setDefaults([
                'data_class' => 'Sergey\TestBundle\Entity\Message',
                'edit_id' => null
            ])
            ->setRequired([
                'ajax_action_url',
                'edit_id'
            ])
            ->setAllowedTypes([
                'ajax_action_url' => 'string',
                'edit_id' => ['int', 'null']
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
