<?php

namespace App\Form;

use App\Entity\StudPClass;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class StudPClassType extends AbstractType
{

    public function __construct(TokenStorageInterface $token){
       $this->token=$token;

    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
 $this->userAction=$options['user_Action'];

        $builder
            ->add('name',TextType::class,array('label'=>'Nombre'))
            ->add('action',ChoiceType::class,array('label'=>'Acción',
            'choices'=>$this->userAction
            
            ))
            ->add('course',null,array('label'=>'Curso'))
            ->add('nactivity',null,array('label'=>'Actividad'))
     
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
            'data_class' => StudPClass::class,
            'user_Action'=>[$this->token->getToken()->getUser()->getName()]
        ]);
    }
}
