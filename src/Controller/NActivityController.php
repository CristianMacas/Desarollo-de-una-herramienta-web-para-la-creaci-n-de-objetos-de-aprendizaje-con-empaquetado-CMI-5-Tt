<?php

namespace App\Controller;

use App\Entity\NActivity;
use App\Entity\NTA;
use App\Entity\Course;

use App\Entity\ModelDiagramTest;
use App\Entity\ModelDiagramSuccess;
use App\Form\NActivityType;
use App\Repository\NActivityRepository;
use App\Repository\CourseRepository;
use App\Repository\ModelDigramTestRepository;
use App\Repository\ModelDigramSuccessRepository;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Snappy\Pdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/n/activity")
 */
class NActivityController extends AbstractController
{
    /**
     * @Route("/", name="app_n_activity_index", methods={"GET"})
     */
    public function index(NActivityRepository $nActivityRepository): Response
    {
        return $this->render('n_activity/index.html.twig', [
            'n_activities' => $nActivityRepository->findDataToShow(),
            'title' => 'Actividades de cursos',
        ]);
    }

    /**
     * @Route("/new/{id}", name="app_n_activity_new", methods={"GET", "POST"})
     */
    function new (Request $request, NActivityRepository $nActivityRepository, int $id): Response 
    {
        $nActivity = new NActivity();
        $form = $this->createForm(NActivityType::class, $nActivity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            /** @var NTA $interactiva */
            $interactiva = $em->getRepository('App:NTA')->findBy(array('denomination' => 'Interactiva'));
            $nActivity->setNTA($interactiva[0]);
            $nActivityRepository->add($nActivity);

            $course =  $em->getRepository('App:Course')->findOneBy(array('id' => $id));

            $diagramSuccess = new ModelDiagramSuccess();
            $diagramSuccess->setCourse($course);
            $diagramSuccess->setNActivity($nActivity);
            $diagramSuccess->setData([]);
            $modeldiagramsuccessRepo = $em->getRepository('App:ModelDiagramSuccess');
            $modeldiagramsuccessRepo->add($diagramSuccess);
            $idDiagramSucces= $diagramSuccess->getId();
            
            $diagram = new ModelDiagramTest();
            $diagram->setCourse($course);
            $diagram->setNActivity($nActivity);
            $user = $this->getUser();
            $diagram->setAction($user->getName());
            $diagram->setData([]);
            $diagram->setFecha();
            $modeldiagramtestRepo = $em->getRepository('App:ModelDiagramTest');
            $modeldiagramtestRepo->add($diagram);

            $nActivity->addModelDiagramTest($diagram);
            $nActivity->addModelDiagramSuccess($diagramSuccess);
            $course->addModelDiagramTest($diagram);
            $course->addModelDiagramSuccess($diagramSuccess);
            
            if ($request->getMethod() === 'POST' && $request->request->has('confirmButton'))
            {
                return $this->redirect('/public/GoJS-master/projects/pdf/diagrama.php?mode=edit&table=model_diagram_success&id='.(string)$idDiagramSucces.'&course='.(string)$id);
            }
            return $this->render('n_activity/index.html.twig', [
                        'n_activities' => $nActivityRepository->findDataToShowIdSuccessByCourse($id),
                        'title' => 'Actividades del curso '. $course->getName(),]);
            
        }

        return $this->render('n_activity/new.html.twig', [
            'n_activity' => $nActivity,
            'id' => $id,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/show", name="app_n_activity_show", methods={"GET"},requirements={"id": ".+"})
     */
    public function show(Request $request, NActivityRepository $nActivityRepository,int $id): Response
    {
        return $this->render('n_activity/show.html.twig', [
            'n_activity' => $nActivityRepository->findDataToShowIdSuccessByActivity($id),
        ]);
    }
    
    /**
     * @Route("/{id}/course", name="app_n_activity_show_course", methods={"GET"})
     */
    public function show_course(Request $request, NActivityRepository $nActivityRepository,int $id): Response
    {
        $em = $this->getDoctrine()->getManager();
        $course =  $em->getRepository('App:Course')->findOneBy(array('id' => $id));
        return $this->render('n_activity/index.html.twig', [
            'n_activities' => $nActivityRepository->findDataToShowIdSuccessByCourse($id),
            'title' => 'Actividades del curso '. $course->getName(),
        ]);
    }

    /**
     * @Route("/{id}/edit/{idcurso}", name="app_n_activity_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, NActivity $nActivity, NActivityRepository $nActivityRepository, int $idcurso): Response
    {
        $form = $this->createForm(NActivityType::class, $nActivity);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            /** @var NTA $interactiva */
            $interactiva = $em->getRepository('App:NTA')->findBy(array('denomination' => 'Interactiva'));
            $nActivity->setNTA($interactiva[0]);
            $nActivityRepository->add($nActivity);
            $this->addFlash('success', 'Datos actualizados satisfactoriamente !!!');
            
            //return $this->render('course/show.html.twig', [
            //    'course' => $idcurso,
            //]);
            
            // $em = $this->getDoctrine()->getManager();
            $course =  $em->getRepository('App:Course')->findOneBy(array('id' => $idcurso));
            return $this->render('n_activity/index.html.twig', [
                'n_activities' => $nActivityRepository->findDataToShowIdSuccessByCourse($idcurso),
                'title' => 'Actividades del curso '. $course->getName(),
            ]);
        }

        return $this->render('n_activity/edit.html.twig', [
            'n_activity' => $nActivity,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete/{idcurso}", name="app_n_activity_delete", methods={"POST", "GET"})
     */
    public function delete(Request $request, NActivity $nActivity, NActivityRepository $nActivityRepository, int $idcurso): Response
    {
        if (!$this->asocActividad($nActivity) ){
            $nActivityRepository->remove($nActivity);
            $this->addFlash('success', 'Datos eliminados satisfactoriamente !!!');
        } else 
        {
            $nActivityRepository->deleteDiagramAssocTest($nActivity->getId());
            $nActivityRepository->deleteDiagramAssocSucces($nActivity->getId());
            $nActivityRepository->remove($nActivity);
            $this->addFlash('success', 'Datos eliminados satisfactoriamente!!!');

        }
        $em = $this->getDoctrine()->getManager();
        $course =  $em->getRepository('App:Course')->findOneBy(array('id' => $idcurso));
            return $this->render('n_activity/index.html.twig', [
                'n_activities' => $nActivityRepository->findDataToShowIdSuccessByCourse($idcurso),
                'title' => 'Actividades del curso '. $course->getName(),
            ]);
    }
    

    public function asocActividad(NActivity $activity)
    {
        $asociada = false;
        $em = $this->getDoctrine()->getManager();

        $diagram = $em->getRepository('App:ModelDiagramSuccess')->findBy(array('nactivity' => $activity->getId()));
        $diagramest = $em->getRepository('App:ModelDiagramTest')->findBy(array('nactivity' => $activity->getId()));

        if (!empty($diagram) || !empty($diagramest)) 
        {
            $asociada = true;

        }

        return $asociada;

    }

    /**
     * @Route("pdf/activity/{id}", name="app_n_activity_pdf")
     */
    public function PdfActionNActivity(Pdf $pdf, NActivity $a)
    {
        $em = $this->getDoctrine()->getManager();
        $html = $this->renderView('pdf/show_nactivity.html.twig', array('a' => $a));
        $pdf->setTemporaryFolder('c:\xampp\htdocs\v2cristian\temp');
        return new PdfResponse(
            $pdf->getOutputFromHtml($html,
                array('lowquality' => false,
                    'print-media-type' => true,
                    'encoding' => 'utf-8',
                    'page-size' => 'A4',
                    'outline-depth' => 8,
                    'orientation' => 'Portrait',
                    'title' => 'Actividad',
                    'header-right' => 'Pag. [page] de [toPage]',
                    'header-font-size' => 7,
                )),
            200,
            array(
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="actividad.pdf"',
            )
        );
    }

}
