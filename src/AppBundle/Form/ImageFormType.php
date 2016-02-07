<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $image = $event->getData();
            $form = $event->getForm();

            $required = (! $image);

            $form->add('path', FileType::class, array(
                'label' => 'Image/Photo (1560 x 2080 max)',
                'data_class' => null,
                'mapped' => true,
                'required' => $required,
            ));
        });

        $builder->add('title', null, array(
            'required' => false,
            'label' => 'Titre',
            'empty_data' => '',
        ));
        $builder->add('tags', null, array(
            'required' => false,
            'label' => 'Tags',
            'empty_data' => '',
        ));
        $builder->add('description', null, array(
            'required' => false,
            'label' => 'Description',
            'empty_data' => '',
        ));
    }
}