<?php
namespace Sergey\TestBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;

class WYSIWYGType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'wysiwyg';
    }

    /**
     * {@inheritDoc}
     */
    public function getParent()
    {
        return 'textarea';
    }

    /**
     * {@inheritDoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['attr']['class'] = trim((isset($view->vars['attr']['class']) ? $view->vars['attr']['class'] : '') . ' tinymce');
    }
}