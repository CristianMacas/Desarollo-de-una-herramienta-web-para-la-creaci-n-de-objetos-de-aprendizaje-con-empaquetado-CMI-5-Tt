<?php

namespace App\Controller;
use App\Entity\SuccessCode;
use App\Entity\EvalCode;
use App\Form\EvalCodeType;
use App\Repository\EvalCodeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/eval/code")
 */
class EvalCodeController extends AbstractController
{
    /**
     * @Route("/", name="app_eval_code_index", methods={"GET"})
     */
    public function index(EvalCodeRepository $evalCodeRepository): Response
    {
        return $this->render('eval_code/index.html.twig', [
            'eval_codes' => $evalCodeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_eval_code_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EvalCodeRepository $evalCodeRepository): Response
    {
        $evalCode = new EvalCode();
        $form = $this->createForm(EvalCodeType::class, $evalCode);
        $form->handleRequest($request);
        $em=$this->getDoctrine()->getManager();
        $codigosSatisfactorios=$em->getRepository('App:SuccessCode')->findAll();

        if (!(empty($codigosSatisfactorios))&& $form->isSubmitted() && $form->isValid()) {
            $evalCodeRepository->add($evalCode);
            $this->addFlash("success", 'Insertado satisfactoriamente!!! ');
            return $this->redirectToRoute('app_eval_code_index', [], Response::HTTP_SEE_OTHER);
        }

        else{

            $this->addFlash('error', 'El profesor no ha publicado la solución de este ejercicio,pruebe más tarde !!!');

        }

        return $this->render('eval_code/new.html.twig', [
            'eval_code' => $evalCode,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_eval_code_show", methods={"GET"})
     */
    public function show(EvalCode $evalCode): Response
    {
        $em = $this->getDoctrine()->getManager();

        $solucioncode=$em->getRepository('App:SuccessCode')->findAll();
        $evalcode=$em->getRepository('App:EvalCode')->findAll();


        foreach($solucioncode as $sol){

            $lineasSolucion=$sol->getCodeline();
           
        }


        foreach($evalcode as $est){

            $lineasEstud=$est->getCodeline();
      
        }

        $incorrecto=true;

        $compareLineas = array_diff($lineasSolucion->toArray(), $lineasEstud->toArray());
    


        if (empty($compareLineas)){
          $incorrecto=false;
        }

        else{

           $incorrecto=true;
        }






        return $this->render('eval_code/show.html.twig', [
            'eval_code' => $evalCode,
            'incorrecto'=>$incorrecto
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_eval_code_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, EvalCode $evalCode, EvalCodeRepository $evalCodeRepository): Response
    {
        $form = $this->createForm(EvalCodeType::class, $evalCode);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $evalCodeRepository->add($evalCode);
            $this->addFlash('success', 'Datos actualizados satisfactoriamente !!!');
            return $this->redirectToRoute('app_eval_code_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('eval_code/edit.html.twig', [
            'eval_code' => $evalCode,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_eval_code_delete", methods={"POST"})
     */
    public function delete(Request $request, EvalCode $evalCode, EvalCodeRepository $evalCodeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$evalCode->getId(), $request->request->get('_token'))) {
            $evalCodeRepository->remove($evalCode);
            $this->addFlash('success', 'Datos eliminados satisfactoriamente !!!');
        }

        return $this->redirectToRoute('app_eval_code_index', [], Response::HTTP_SEE_OTHER);
    }
}
