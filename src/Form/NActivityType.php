<?php

namespace App\Form;

use App\Entity\NActivity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NActivityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, array('label' => 'Título', 'attr' => array('class' => 'form-control')))

            ->add('description', TextareaType::class, array('label' => 'Descripción', 'attr' => array('class' => 'form-control')))
            ->add('place', TextType::class, array('label' => 'Mensaje de felicitación', 'attr' => array('class' => 'form-control')))
            ->add('nta', null, array('label' => 'Tipo Actividad', "row_attr" => [
                "class" => "d-none",
            ]))
            ->add('tecsol',TextareaType::class,array('label' => 'Solución Tecnica', 'attr' => array('class' => 'form-control')))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => NActivity::class,
        ]);
    }
}
