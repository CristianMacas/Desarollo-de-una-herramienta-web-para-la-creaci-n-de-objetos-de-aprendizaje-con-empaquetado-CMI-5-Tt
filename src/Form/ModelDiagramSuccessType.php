<?php

namespace App\Form;

use App\Entity\ModelDiagramSuccess;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ModelDiagramSuccessType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('data', null, array('label' => 'Data', 'attr' => array('class' => 'form-control','placeholder' => 'Código en formato json del diagrama')))

            ->add('course', null, array('label' => 'Curso'))
            ->add('nactivity', null, array('label' => 'Actividad'))

            ->add('archive', FileType::class, array('label' => 'Subir Archivo',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '500000k',
                        'mimeTypes' => [
                            'application/pdf',
                            'application/x-pdf',
                            'application/msword',
                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                            'application/vnd.ms-powerpoint',
                            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                            'application/vnd.ms-excel',
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            'application/zip',
                            'application/x-7z-compressed',
                            'application/rar',
                            'application/x-zip',
                            'application/x-rar',
                            'application/x-zip-compressed',
                        ],
                        'mimeTypesMessage' => 'Por favor suba el documento con una extensión válida',
                    ]),
                ],

            ))
        ;

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
            'data_class' => ModelDiagramSuccess::class,
        ]);
    }
}
