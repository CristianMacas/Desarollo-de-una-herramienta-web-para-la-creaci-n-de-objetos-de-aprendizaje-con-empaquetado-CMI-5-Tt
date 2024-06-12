<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email',EmailType::class,array('label'=>'Correo electrónico','attr'=>array('class'=>'form-control')))
            ->add('roles', ChoiceType::class, [ 'disabled'=>$options['isEdit'],
                'attr' => array('class' => 'custom-select'),
                'required' => true,
                'multiple' => false,
                'expanded' => false,
                'choices' =>
                    [
                        'User' => 'ROLE_USER',
                        'Admin' => 'ROLE_ADMIN',
                    ],


            ])
            ->add('password',PasswordType::class,array('label'=>'Clave','attr'=>array('class'=>'form-control')))
            ->add('name',null,array('label'=>'Nombre','attr'=>array('class'=>'form-control')));

            $builder->get('roles')
            ->addModelTransformer(new CallbackTransformer(
                function ($rolesArray) {
                    return count($rolesArray) ? $rolesArray[0] : $rolesArray[1];
                },
                function ($rolesString) {
                    return [$rolesString];
                }

            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault(option:'isEdit',value:false);
        
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
