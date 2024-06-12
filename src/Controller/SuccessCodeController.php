<?php

namespace App\Controller;

use App\Entity\SuccessCode;
use App\Form\SuccessCodeType;
use App\Repository\SuccessCodeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/success/code")
 */
class SuccessCodeController extends AbstractController
{
    /**
     * @Route("/", name="app_success_code_index", methods={"GET"})
     */
    public function index(SuccessCodeRepository $successCodeRepository): Response
    {
        return $this->render('success_code/index.html.twig', [
            'success_codes' => $successCodeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_success_code_new", methods={"GET", "POST"})
     */
    public function new(Request $request, SuccessCodeRepository $successCodeRepository): Response
    {
        $successCode = new SuccessCode();
        $form = $this->createForm(SuccessCodeType::class, $successCode);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $successCodeRepository->add($successCode);
            $this->addFlash("success", 'Insertado satisfactoriamente!!! ');
            return $this->redirectToRoute('app_success_code_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('success_code/new.html.twig', [
            'success_code' => $successCode,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_success_code_show", methods={"GET"})
     */
    public function show(SuccessCode $successCode): Response
    {
        return $this->render('success_code/show.html.twig', [
            'success_code' => $successCode,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_success_code_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, SuccessCode $successCode, SuccessCodeRepository $successCodeRepository): Response
    {
        $form = $this->createForm(SuccessCodeType::class, $successCode);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $successCodeRepository->add($successCode);
            $this->addFlash('success', 'Datos actualizados satisfactoriamente !!!');
            return $this->redirectToRoute('app_success_code_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('success_code/edit.html.twig', [
            'success_code' => $successCode,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_success_code_delete", methods={"POST"})
     */
    public function delete(Request $request, SuccessCode $successCode, SuccessCodeRepository $successCodeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$successCode->getId(), $request->request->get('_token'))) {
            $successCodeRepository->remove($successCode);
            $this->addFlash('success', 'Datos eliminados satisfactoriamente !!!');
        }

        return $this->redirectToRoute('app_success_code_index', [], Response::HTTP_SEE_OTHER);
    }
}
