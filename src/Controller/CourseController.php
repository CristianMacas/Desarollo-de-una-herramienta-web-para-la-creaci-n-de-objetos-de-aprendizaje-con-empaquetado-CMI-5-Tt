<?php

namespace App\Controller;

use App\Entity\Course;
use App\Form\CourseType;
use App\Repository\CourseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/course")
 */
class CourseController extends AbstractController
{
    /**
     * @Route("/", name="app_course_index", methods={"GET"})
     */
    public function index(CourseRepository $courseRepository): Response
    {
        return $this->render('course/index.html.twig', [
            'courses' => $courseRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_course_new", methods={"GET", "POST"})
     */
    function new (Request $request, CourseRepository $courseRepository): Response {
        $course = new Course();
        $form = $this->createForm(CourseType::class, $course);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $courseRepository->add($course);
            $this->addFlash("success", 'Insertado satisfactoriamente!!! ');
            return $this->redirectToRoute('app_course_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('course/new.html.twig', [
            'course' => $course,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_course_show", methods={"GET"})
     */
    public function show(Course $course): Response
    {
        return $this->render('course/show.html.twig', [
            'course' => $course,
        ]);
    }
    
    /**
     * @Route("/{id}/users", name="app_course_studients", methods={"GET"})
     */
    public function studients(CourseRepository $courseRepository,int $id): Response
    {
        return $this->render('course/showMembers.html.twig', [
            'users' => $courseRepository->findUserByCourseId($id),
            'course_name' => $courseRepository->find($id),
            'course_id' => $id,
        ]);
    }

    /**
     * @Route("/diagram/test/{id}", name="app_course_show_diagram_test", methods={"GET"})
     */
    public function showModelsDiagramTest(CourseRepository $courseRepository, int $id): Response
    {
        //$course = $courseRepository->find($id);

        $models = $courseRepository->findModelsTestByCourseId($id);

        return $this->render('model_diagram_test/index.html.twig', [
            'model_diagram_tests' => $models,
        ]);
    }

    /**
     * @Route("/diagram/success/{id}", name="app_course_show_diagram_success", methods={"GET"})
     */
    public function showModelsDiagramSuccess(CourseRepository $courseRepository, int $id): Response
    {
        $course = $courseRepository->find($id);

        $models = $course->getModelsDiagramsSuccess();

        return $this->render('model_diagram_success/index.html.twig', [
            'model_diagram_success' => $models,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_course_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Course $course, CourseRepository $courseRepository): Response
    {
        $form = $this->createForm(CourseType::class, $course);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $courseRepository->add($course);
            $this->addFlash('success', 'Datos actualizados satisfactoriamente !!!');
            return $this->redirectToRoute('app_course_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('course/edit.html.twig', [
            'course' => $course,
            'form' => $form->createView(),
        ]);
    }
    
    /**
     * @Route("/{id}/members", name="app_course_members", methods={"GET", "POST"})
     */
    public function members(Request $request, Course $course, CourseRepository $courseRepository): Response
    {
        $form = $this->createForm(CourseType::class, $course, ['isEdit'=> true,]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $courseRepository->add($course);
            $this->addFlash('success', 'Datos actualizados satisfactoriamente !!!');
            return $this->redirectToRoute('app_course_studients', ['id' => $course->getId(),], Response::HTTP_SEE_OTHER);
        }
        return $this->render('course/members.html.twig', [
            'course' => $course,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete", name="app_course_delete", methods={"POST","GET"})
     */
    public function delete(Request $request, Course $course, CourseRepository $courseRepository, int $id): Response
    {
        if (!$this->tieneMiembros($course) && !$this->asocCurso($course) ){ 
            $courseRepository->remove($course);
            $this->addFlash('success', 'Datos eliminados satisfactoriamente !!!');
        } else {

            if ($this->asocCurso($course)) {
                $this->addFlash('error', 'Este curso está asociado a al menos una actividad, revise!!!');

            }
            if ($this->tieneMiembros($course)) {
                $this->addFlash('error', 'Este curso tiene miembros inscritos, revise!!!');

            }

        }

        return $this->redirectToRoute('app_course_index', [], Response::HTTP_SEE_OTHER);
    }

    public function asocCurso(Course $course)
    {
        $asociada = false;
        $em = $this->getDoctrine()->getManager();

        $diagram = $em->getRepository('App:ModelDiagramSuccess')->findBy(array('course' => $course->getId()));
        $diagramest = $em->getRepository('App:ModelDiagramTest')->findBy(array('course' => $course->getId()));

        if ( !empty($diagram) || !empty($diagramest)) {
            $asociada = true;
            return $asociada;
            $this->addFlash('error', 'Este curso está asociado a al menos una solución !!!');
        }

        return $asociada;

    }

    public function tieneMiembros(Course $course)
    {
        $miembros = false;
        $em = $this->getDoctrine()->getManager();

        if (count($course->getMembers()) > 0) {

            $miembros = true;
            return $miembros;
            $this->addFlash('error', 'Este curso tiene miembros inscritos, revise !!!');
        }

        return $miembros;

    }

}
