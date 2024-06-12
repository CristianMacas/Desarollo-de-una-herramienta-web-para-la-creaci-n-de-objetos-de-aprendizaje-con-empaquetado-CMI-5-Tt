<?php

namespace App\Form;

use App\Entity\SuccessCode;
use App\Entity\Codeline;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class SuccessCodeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('course',null,array('label'=>'Curso')) 
        ->add('nactivity',null,array('label'=>'Actividad')) 
            ->add('codeline')

            ->add('codeline', CollectionType::class, array(
                'label'=>'Atributo',
                   'entry_type' => CodelineType::class,
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
            'data_class' => SuccessCode::class,
        ]);
    }
}
