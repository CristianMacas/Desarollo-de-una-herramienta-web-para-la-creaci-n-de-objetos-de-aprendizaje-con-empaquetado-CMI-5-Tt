<?php

namespace App\Controller;

use App\Entity\StudPClass;
use App\Form\StudPClassType;
use App\Repository\StudPClassRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/stud/p/class")
 */
class StudPClassController extends AbstractController
{
    /**
     * @Route("/", name="app_stud_p_class_index", methods={"GET"})
     */
    public function index(StudPClassRepository $studPClassRepository): Response
    {
        return $this->render('stud_p_class/index.html.twig', [
            'stud_p_classes' => $studPClassRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_stud_p_class_new", methods={"GET", "POST"})
     */
    public function new(Request $request, StudPClassRepository $studPClassRepository): Response
    {
        $studPClass = new StudPClass();
        $form = $this->createForm(StudPClassType::class, $studPClass);
        $form->handleRequest($request);

        $em=$this->getDoctrine()->getManager();
        $estructurasClasesok=$em->getRepository('App:PClass')->findAll();

        if (!(empty($estructurasClasesok))&&$form->isSubmitted() && $form->isValid()) {
            $studPClassRepository->add($studPClass);
            $this->addFlash("success", 'Insertado satisfactoriamente!!! ');
            return $this->redirectToRoute('app_stud_p_class_index', [], Response::HTTP_SEE_OTHER);
        }

    else{

        $this->addFlash('error', 'El profesor no ha publicado la solución de este ejercicio,pruebe más tarde !!!');
 
    }

        return $this->render('stud_p_class/new.html.twig', [
            'stud_p_class' => $studPClass,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_stud_p_class_show", methods={"GET"})
     */
    public function show(StudPClass $studPClass): Response
    {

        $em = $this->getDoctrine()->getManager();
        $solucionclass=$em->getRepository('App:PClass')->findAll();
        $estudclass=$em->getRepository('App:StudPClass')->findAll(); 

        foreach($solucionclass as $sol){

            $atributosSolucion=$sol->getAtribute();
            $operacionesSolucion=$sol->getOperation();
        }


        foreach($estudclass as $est){

            $atributosEstud=$est->getAtribute();
            $operacionesEstud=$est->getOperation();
        }


         $incorrecto=true;

         $compareAtributes = array_diff($atributosSolucion->toArray(), $atributosEstud->toArray());
         $compareOperaciones = array_diff($operacionesSolucion->toArray(), $operacionesEstud->toArray());


         if (empty($compareAtributes) && empty($compareOperaciones) ){
           $incorrecto=false;
         }

         else{

            $incorrecto=true;
         }





        return $this->render('stud_p_class/show.html.twig', [
            'stud_p_class' => $studPClass,

            'incorrecto'=>$incorrecto
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_stud_p_class_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, StudPClass $studPClass, StudPClassRepository $studPClassRepository): Response
    {
        $form = $this->createForm(StudPClassType::class, $studPClass);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $studPClassRepository->add($studPClass);
            $this->addFlash('success', 'Datos actualizados satisfactoriamente !!!');
            return $this->redirectToRoute('app_stud_p_class_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('stud_p_class/edit.html.twig', [
            'stud_p_class' => $studPClass,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_stud_p_class_delete", methods={"POST"})
     */
    public function delete(Request $request, StudPClass $studPClass, StudPClassRepository $studPClassRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$studPClass->getId(), $request->request->get('_token'))) {
            $studPClassRepository->remove($studPClass);
            $this->addFlash('success', 'Datos eliminados satisfactoriamente !!!');
        }

        return $this->redirectToRoute('app_stud_p_class_index', [], Response::HTTP_SEE_OTHER);
    }
}
