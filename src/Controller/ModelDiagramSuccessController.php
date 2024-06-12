<?php

namespace App\Controller;

use App\Entity\ModelDiagramSuccess;
use App\Form\ModelDiagramSuccessType;
use App\Repository\ModelDiagramSuccessRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @Route("/model/diagram/success")
 */
class ModelDiagramSuccessController extends AbstractController
{
    /**
     * @Route("/", name="app_model_diagram_success_index", methods={"GET"})
     */
    public function index(ModelDiagramSuccessRepository $modelDiagramSuccessRepository): Response
    {
        return $this->render('model_diagram_success/index.html.twig', [
            'model_diagram_successes' => $modelDiagramSuccessRepository->findDataToShowNames(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_model_diagram_success_index1", methods={"GET"})
     */
    public function indexId(ModelDiagramSuccessRepository $modelDiagramSuccessRepository,int $id): Response
    {
        return $this->render('model_diagram_success/index.html.twig', [
            'model_diagram_successes' => $modelDiagramSuccessRepository->findDataToShowId($id),
        ]);
    }
    
    /**
     * @Route("/{id}", name="app_model_diagram_success_index2", methods={"GET"})
     */
    public function indexIdAct(ModelDiagramSuccessRepository $modelDiagramSuccessRepository,int $id): Response
    {
        return $this->render('model_diagram_success/index.html.twig', [
            'model_diagram_successes' => $modelDiagramSuccessRepository->findDataToShowIdAct($id),
        ]);
    }

    
    /**
     * @Route("/{id}", name="app_model_diagram_success_index3", methods={"GET"})
     */
    public function indexIdUser(ModelDiagramSuccessRepository $modelDiagramSuccessRepository,int $id): Response
    {
        return $this->render('model_diagram_success/index.html.twig', [
            'model_diagram_successes' => $modelDiagramSuccessRepository->findDataToShowIdUser($id),
        ]);
    }

    /**
     * @Route("/new", name="app_model_diagram_success_new", methods={"GET", "POST"})
     */
    function new (Request $request, ModelDiagramSuccessRepository $modelDiagramSuccessRepository, SluggerInterface $slugger): Response {
        $modelDiagramSuccess = new ModelDiagramSuccess();
        $form = $this->createForm(ModelDiagramSuccessType::class, $modelDiagramSuccess);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $diagramFile = $form->get('archive')->getData();
            if ($diagramFile) {
                $originalFilename = pathinfo($diagramFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $diagramFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $diagramFile->move(
                        $this->getParameter('diagrams_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents

            }
            $modelDiagramSuccess->setArchive($newFilename);

            $modelDiagramSuccessRepository->add($modelDiagramSuccess);
            $this->addFlash("success", 'Insertado satisfactoriamente!!! ');
            return $this->redirectToRoute('app_model_diagram_success_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('model_diagram_success/new.html.twig', [
            'model_diagram_success' => $modelDiagramSuccess,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_model_diagram_success_show", methods={"GET"})
     */
    public function show(ModelDiagramSuccess $modelDiagramSuccess): Response
    {
        return $this->render('model_diagram_success/show.html.twig', [
            'model_diagram_success' => $modelDiagramSuccess,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_model_diagram_success_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, ModelDiagramSuccess $modelDiagramSuccess, ModelDiagramSuccessRepository $modelDiagramSuccessRepository): Response
    {
        $form = $this->createForm(ModelDiagramSuccessType::class, $modelDiagramSuccess);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $file = $form->get('archive')->getData();
            $fileName = md5(uniqid()) . '.' . $file->guessExtension();
            try {
                $file->move(
                    $this->getParameter('diagrams_directory'),
                    $fileName
                );

            } catch (FileException $e) {

            }

            $modelDiagramSuccess->setArchive($fileName);

            $modelDiagramSuccessRepository->add($modelDiagramSuccess);
            $this->addFlash('success', 'Datos actualizados satisfactoriamente !!!');
            return $this->redirectToRoute('app_model_diagram_success_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('model_diagram_success/edit.html.twig', [
            'model_diagram_success' => $modelDiagramSuccess,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_model_diagram_success_delete", methods={"POST"})
     */
    public function delete(Request $request, ModelDiagramSuccess $modelDiagramSuccess, ModelDiagramSuccessRepository $modelDiagramSuccessRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $modelDiagramSuccess->getId(), $request->request->get('_token'))) {
            $modelDiagramSuccessRepository->remove($modelDiagramSuccess);
            $this->addFlash('success', 'Datos eliminados satisfactoriamente !!!');
        }

        return $this->redirectToRoute('app_model_diagram_success_index', [], Response::HTTP_SEE_OTHER);
    }
    
    /**
     * @Route("/{id}/delete", name="app_model_diagram_delete", methods={"GET","POST"})
     */
    public function deletediagram(Request $request, ModelDiagramSuccess $modelDiagramSuccess, ModelDiagramSuccessRepository $modelDiagramSuccessRepository): Response
    {
        /*if ($this->isCsrfTokenValid('delete' . $modelDiagramSuccess->getId(), $request->request->get('_token'))) {
            $modelDiagramSuccessRepository->remove($modelDiagramSuccess);
            $this->addFlash('success', 'Datos eliminados satisfactoriamente !!!');
        }*/
        
        $conexion = mysqli_connect('localhost', 'xperienc', 'Zb(nY;w1A62r1R', 'xperienc_uml');
        $result = mysqli_query($conexion, "update model_diagram_success set data='[]' where id='".$modelDiagramSuccess->getId()."'");
        if ($result){   //$conexion->query($ssql)
            $this->addFlash('success', 'Datos eliminados satisfactoriamente !!!');
        }else{
            $this->addFlash('error', 'No se puedo eliminar satisfactoriamente, revise!!!');
        }
        $conexion->close();

        return $this->redirectToRoute('app_model_diagram_success_index', [], Response::HTTP_SEE_OTHER);
    }
}
