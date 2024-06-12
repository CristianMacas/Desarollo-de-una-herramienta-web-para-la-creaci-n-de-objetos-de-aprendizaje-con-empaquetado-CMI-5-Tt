<?php

namespace App\Controller;

use App\Entity\Atribute;
use App\Form\AtributeType;
use App\Repository\AtributeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/atribute")
 */
class AtributeController extends AbstractController
{
    /**
     * @Route("/", name="app_atribute_index", methods={"GET"})
     */
    public function index(AtributeRepository $atributeRepository): Response
    {
        return $this->render('atribute/index.html.twig', [
            'atributes' => $atributeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_atribute_new", methods={"GET", "POST"})
     */
    public function new(Request $request, AtributeRepository $atributeRepository): Response
    {
        $atribute = new Atribute();
        $form = $this->createForm(AtributeType::class, $atribute);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $atributeRepository->add($atribute);
            return $this->redirectToRoute('app_atribute_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('atribute/new.html.twig', [
            'atribute' => $atribute,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_atribute_show", methods={"GET"})
     */
    public function show(Atribute $atribute): Response
    {
        return $this->render('atribute/show.html.twig', [
            'atribute' => $atribute,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_atribute_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Atribute $atribute, AtributeRepository $atributeRepository): Response
    {
        $form = $this->createForm(AtributeType::class, $atribute);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $atributeRepository->add($atribute);
            return $this->redirectToRoute('app_atribute_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('atribute/edit.html.twig', [
            'atribute' => $atribute,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_atribute_delete", methods={"POST"})
     */
    public function delete(Request $request, Atribute $atribute, AtributeRepository $atributeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$atribute->getId(), $request->request->get('_token'))) {
            $atributeRepository->remove($atribute);
        }

        return $this->redirectToRoute('app_atribute_index', [], Response::HTTP_SEE_OTHER);
    }
}
