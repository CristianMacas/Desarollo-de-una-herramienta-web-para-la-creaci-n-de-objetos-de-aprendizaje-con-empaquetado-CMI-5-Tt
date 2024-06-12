<?php

namespace App\Form;

use App\Entity\Atribute;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class AtributeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class,array('label'=>'Nombre','attr'=>array('class'=>'form_control')))
            ->add('datatype',null,array('label'=>'Tipo','attr'=>array('class'=>'form_control')))
       
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Atribute::class,
        ]);
    }
}