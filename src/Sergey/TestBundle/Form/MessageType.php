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
        if ($options['is_edit']) {
            $builder->add('id', 'hidden')
                    ->add(
                        $builder->create('created', 'hidden')
                            ->addViewTransformer(new DateTimeToStringTransformer())
                    );
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
                'is_edit' => false
            ])
            ->setRequired([
                'is_edit',
                'ajax_action_url',
            ])
            ->setAllowedTypes([
                'is_edit' => 'bool',
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
