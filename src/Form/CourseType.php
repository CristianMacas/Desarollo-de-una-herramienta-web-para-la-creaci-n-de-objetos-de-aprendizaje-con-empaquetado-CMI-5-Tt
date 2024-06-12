<?php

namespace App\Form;

use App\Entity\Course;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
//use Symfony\Component\Form\Extension\Core\Type\DatepickerType;

use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\DateTime;


class CourseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextareaType::class, 
                    array('disabled'=>$options['isEdit'],
                          'label' => 'Nombre', 
                          'attr' => array('class' => 'form_control')
                          )
                  )
            //->add('initialDate', DateType::class, array('disabled'=>$options['isEdit'],'label' => 'Fecha Inicio', 'html5' => false,  'input_format' => 'D/M/Y', 'widget' => 'single_text', 'attr' => array('class' => 'form-control', 'placeholder' => 'dd/MM/yyyy')))
            ->add('initialDate', DateTimeType::class, 
                    array('disabled'=>$options['isEdit'],
                          'label' => 'Fecha Inicio', 
                          'html5' => false,  
                          'format' => 'dd/MM/yyyy', 
                          'widget' => 'single_text', 
                          'attr' => array('class' => 'form-control', 'placeholder' => 'dd/MM/yyyy'),
                         )
                 )
            //->add('fdate', DateType::class, array('disabled'=>$options['isEdit'],'label' => 'Fecha Final', 'html5' => true,  'widget' => 'single_text', 'attr' => array('class' => 'form-control', 'placeholder' => 'dd/MM/yyyy')))
            ->add('fdate', DateType::class, 
                    array('disabled'=>$options['isEdit'],
                          'label' => 'Fecha Final', 
                          'html5' => false,  
                          'format' => 'dd/MM/yyyy', 
                          'widget' => 'single_text', 
                          'attr' => array('class' => 'form-control', 'placeholder' => 'dd/MM/yyyy'),
                          )
                  )

            ->add('members', EntityType::class, array(
                'label' => 'Miembros',
                'class' => User::class,
                'expanded' => false,
                'multiple' => true,
                'attr' => array('style' => 'height: 150px; overflow-y: scroll;','class' => 'form_control'),
                'required'=>false,
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault(option:'isEdit',value:false);
        
        $resolver->setDefaults([
            'data_class' => Course::class,
        ]);
    }
}
