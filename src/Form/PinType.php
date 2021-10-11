<?php

namespace App\Form;

use App\Entity\Pin;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PinType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        //$pin = $options['data']; 
        //dd($pin && $pin->getId());// Check if it's the edit form or the create form

        $builder
            ->add('title')
            ->add('description')
            ->add('imageFile', VichImageType::class, [
                'label' => "Image (JPG or PNG file)",
                'required' => false,
                'allow_delete' => true,
                'delete_label' => 'Delete ?',
                'download_uri' => false,
                'imagine_pattern' => 'squared_thumbnail_small'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Pin::class,
        ]);
    }
}
