<?php

namespace App\Controller;

use App\Entity\Datatype;
use App\Form\DatatypeType;
use App\Repository\DatatypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/datatype")
 */
class DatatypeController extends AbstractController
{
    /**
     * @Route("/", name="app_datatype_index", methods={"GET"})
     */
    public function index(DatatypeRepository $datatypeRepository): Response
    {
        return $this->render('datatype/index.html.twig', [
            'datatypes' => $datatypeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_datatype_new", methods={"GET", "POST"})
     */
    public function new(Request $request, DatatypeRepository $datatypeRepository): Response
    {
        $datatype = new Datatype();
        $form = $this->createForm(DatatypeType::class, $datatype);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $datatypeRepository->add($datatype);
            $this->addFlash("success", 'Insertado satisfactoriamente!!! ');
            return $this->redirectToRoute('app_datatype_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('datatype/new.html.twig', [
            'datatype' => $datatype,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_datatype_show", methods={"GET"})
     */
    public function show(Datatype $datatype): Response
    {
        return $this->render('datatype/show.html.twig', [
            'datatype' => $datatype,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_datatype_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Datatype $datatype, DatatypeRepository $datatypeRepository): Response
    {
        $form = $this->createForm(DatatypeType::class, $datatype);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $datatypeRepository->add($datatype);
            $this->addFlash('success', 'Datos actualizados satisfactoriamente !!!');
            return $this->redirectToRoute('app_datatype_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('datatype/edit.html.twig', [
            'datatype' => $datatype,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_datatype_delete", methods={"POST"})
     */
    public function delete(Request $request, Datatype $datatype, DatatypeRepository $datatypeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$datatype->getId(), $request->request->get('_token'))) {
            $datatypeRepository->remove($datatype);
            $this->addFlash('success', 'Datos eliminados satisfactoriamente !!!');
        }

        return $this->redirectToRoute('app_datatype_index', [], Response::HTTP_SEE_OTHER);
    }
}
