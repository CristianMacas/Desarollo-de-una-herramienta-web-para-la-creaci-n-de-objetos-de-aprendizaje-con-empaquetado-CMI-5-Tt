<?php

namespace App\Form;

use App\Entity\PClass;
use App\Entity\Atribute;
use App\Entity\Operation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class PClassType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('course',null,array('label'=>'Curso')) 
            ->add('nactivity',null,array('label'=>'Actividad'))       
            ->add('name',TextType::class,array('label'=>'Nombre Clase','attr'=>array('class'=>'form_control')))
           ->add('atribute', CollectionType::class, array(
            'label'=>'Atributo',
               'entry_type' => AtributeType::class,
               'entry_options' => ['label' => false],
               'allow_add' => true,
               'allow_delete' => true,
               'by_reference' => false,
           ))
           ->add('operation', CollectionType::class, array(
            'label'=>'Operación',
               'entry_type' => OperationType::class,
               'entry_options' => ['label' => false],
               'allow_add' => true,
               'allow_delete' => true,
               'by_reference' => false,
           ))

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PClass::class,
        ]);
    }
}