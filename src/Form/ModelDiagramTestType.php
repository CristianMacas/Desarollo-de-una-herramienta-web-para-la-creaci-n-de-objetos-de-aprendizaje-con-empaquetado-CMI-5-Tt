<?php

namespace App\Form;

use App\Entity\ModelDiagramTest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ModelDiagramTestType extends AbstractType
{

    public function __construct(TokenStorageInterface $token)
    {
        $this->token = $token;

    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->userAction = $options['user_Action'];
        $builder
            ->add('action', ChoiceType::class, array('label' => '*',
                'choices' => $this->userAction,

            ))
            ->add('data', null, array('label' => 'Data', 'attr' => array('class' => 'form-control')))
            ->add('course', null, array('label' => 'Curso'))
            ->add('nactivity', null, array('label' => 'Actividad'));

        $builder->get('data')
            ->addModelTransformer(new CallbackTransformer(
                function ($dataArray) {
                    return count($dataArray) ? $dataArray[0] : null;
                },
                function ($dataString) {
                    return [$dataString];
                }

            ))

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ModelDiagramTest::class,
            'user_Action' => [$this->token->getToken()->getUser()->getName()],
        ]);
    }
}
