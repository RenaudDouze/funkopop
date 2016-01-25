<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

/**
 * ImageFormType
 */
class ImageFormType extends AbstractType
{
    /**
     * Configure options
     *
     * @param  OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Image',
        ));
    }

    /**
     * Build form
     *
     * @param  FormBuilderInterface $builder
     * @param  array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('path', FileType::class);
        $builder->add('title', null, array(
            'required' => false,
        ));
        $builder->add('description', null, array(
            'required' => false,
        ));
    }
}