<?php

namespace App\Controller;

use App\Entity\NTA;
use App\Form\NTAType;
use App\Repository\NTARepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/n/t/a")
 */
class NTAController extends AbstractController
{
    /**
     * @Route("/", name="app_n_t_a_index", methods={"GET"})
     */
    public function index(NTARepository $nTARepository): Response
    {
        return $this->render('nta/index.html.twig', [
            'n_t_as' => $nTARepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_n_t_a_new", methods={"GET", "POST"})
     */
    public function new(Request $request, NTARepository $nTARepository): Response
    {
        $nTum = new NTA();
        $form = $this->createForm(NTAType::class, $nTum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $nTARepository->add($nTum);
            $this->addFlash("success", 'Insertado satisfactoriamente!!! ');
            return $this->redirectToRoute('app_n_t_a_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('nta/new.html.twig', [
            'n_tum' => $nTum,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_n_t_a_show", methods={"GET"})
     */
    public function show(NTA $nTum): Response
    {
        return $this->render('nta/show.html.twig', [
            'n_tum' => $nTum,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_n_t_a_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, NTA $nTum, NTARepository $nTARepository): Response
    {
        $form = $this->createForm(NTAType::class, $nTum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $nTARepository->add($nTum);
            $this->addFlash('success', 'Datos actualizados satisfactoriamente !!!');
            return $this->redirectToRoute('app_n_t_a_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('nta/edit.html.twig', [
            'n_tum' => $nTum,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_n_t_a_delete", methods={"POST"})
     */
    public function delete(Request $request, NTA $nTum, NTARepository $nTARepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$nTum->getId(), $request->request->get('_token'))) {
            $nTARepository->remove($nTum);
            $this->addFlash('success', 'Datos eliminados satisfactoriamente !!!');
        }

        return $this->redirectToRoute('app_n_t_a_index', [], Response::HTTP_SEE_OTHER);
    }
}
