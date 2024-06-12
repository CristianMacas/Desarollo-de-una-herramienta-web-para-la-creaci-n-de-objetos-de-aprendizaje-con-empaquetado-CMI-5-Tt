<?php

namespace App\Controller;

use App\Entity\PClass;
use App\Form\PClassType;
use App\Repository\PClassRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/p/class")
 */
class PClassController extends AbstractController
{
    /**
     * @Route("/", name="app_p_class_index", methods={"GET"})
     */
    public function index(PClassRepository $pClassRepository): Response
    {
        return $this->render('p_class/index.html.twig', [
            'p_classes' => $pClassRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_p_class_new", methods={"GET", "POST"})
     */
    public function new(Request $request, PClassRepository $pClassRepository): Response
    {
        $pClass = new PClass();
        $form = $this->createForm(PClassType::class, $pClass);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pClassRepository->add($pClass);
            $this->addFlash("success", 'Insertado satisfactoriamente!!! ');
            return $this->redirectToRoute('app_p_class_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('p_class/new.html.twig', [
            'p_class' => $pClass,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_p_class_show", methods={"GET"})
     */
    public function show(PClass $pClass): Response
    {
        return $this->render('p_class/show.html.twig', [
            'p_class' => $pClass,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_p_class_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, PClass $pClass, PClassRepository $pClassRepository): Response
    {
        $form = $this->createForm(PClassType::class, $pClass);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pClassRepository->add($pClass);
            $this->addFlash('success', 'Datos actualizados satisfactoriamente !!!');
            return $this->redirectToRoute('app_p_class_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('p_class/edit.html.twig', [
            'p_class' => $pClass,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_p_class_delete", methods={"POST"})
     */
    public function delete(Request $request, PClass $pClass, PClassRepository $pClassRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$pClass->getId(), $request->request->get('_token'))) {
            $pClassRepository->remove($pClass);
            $this->addFlash('success', 'Datos eliminados satisfactoriamente !!!');
        }

        return $this->redirectToRoute('app_p_class_index', [], Response::HTTP_SEE_OTHER);
    }
}
