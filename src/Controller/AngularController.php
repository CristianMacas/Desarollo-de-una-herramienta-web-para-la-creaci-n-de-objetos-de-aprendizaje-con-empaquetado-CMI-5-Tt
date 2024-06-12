<?php

namespace App\Controller;

use App\Entity\Course;
use App\Entity\ModelDiagramTest;
use App\Repository\CourseRepository;
use App\Repository\ModelDiagramTestRepository;
use App\Repository\NActivityRepository;
use App\Repository\NTARepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class AngularController extends AbstractController
{
    /**
     * API Mostrar user que está logueado
     * @author 
     * @Route("/xapi/userLogged", name="userLogged_get",methods={"GET"})
     */
    public function userLoggged(Security $security)
    {

        $user = $security->getUser();

        if (!$user) {
            return $this->json('The user is not logged in', 404);
        }

        $data = [
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'roles' => $user->getRoles(),
            'name' => $user->getName(),

        ];

        $response = new JsonResponse($data, Response::HTTP_OK);
        $response->headers->set('Cache-Control', 'public');

        return $response;
    }

    /**
     * API Mostrar user que está logueado
     * @author 
     * @Route("/xapi/userLogged/id", name="userLoggedid_get",methods={"GET"})
     */
    public function userLoggedid(Security $security)
    {
        $user = $security->getUser();

        if (!$user) {
            return $this->json('The user is not logged in', 404);
        }

        $data = 
            $user->getId();
       
        $response = new JsonResponse($data, Response::HTTP_OK);
        $response->headers->set('Cache-Control', 'public');
        $response->headers->set('Transfer-Encoding', 'identity');

        return $response;
    }


    /**
     * API Listar Actividad de Modelado
     * @author 
     * @Route("/xapi/modelActivity", name="modelActivity_get",methods={"GET"})
     */

    public function listModelActivity(NActivityRepository $nActivityRepository, NTARepository $nTARepository)
    {
        $arrayactividad = array();
        $ntipo = $nTARepository->findBy(array('denomination' => 'Interactiva'));

        $actividades = $nActivityRepository->findBy(array('nta' => $ntipo));

        foreach ($actividades as $act) {
            $arrayactividad[] = array(
                'id' => $act->getId(),
                'title' => $act->getTitle(),
                'description' => $act->getDescription(),
                'place' => $act->getPlace(),
                'nta' => $act->getNTA(),
                'tecsol'=> $act->getTecSol(),

            );
        }

        return new JsonResponse($arrayactividad);
    }
    /**
     * API Listar Curso
     * @author 
     * @Route("/xapi/course", name="course_get",methods={"GET"})
     */

    public function listCourse(NActivityRepository $nActivityRepository, CourseRepository $courseRepository)
    {
        $arraycursos = array();
        $courses = $courseRepository->findAll();

        foreach ($courses as $c) {
            $arraycursos[] = array(
                'id' => $c->getId(),
                'name' => $c->getName(),

            );
        }

        return new JsonResponse($arraycursos);
    }

    /**
     * API Insertar Actividad de Modelado
     * @author 
     * @Route("/xapi/newmodeldiagram", name="modeldiagram_new",methods={"POST"})
     */

    public function newModelDiagram(Request $request, ModelDiagramTestRepository $modelDiagramTestRepository, CourseRepository $courseRepository, NActivityRepository $nActivityRepository, NTARepository $nTARepository, EntityManagerInterface $em): Response
    {
        $request = $this->transformJsonBody($request);
        //Obtener usuario
        $conexion = mysqli_connect('localhost', 'xperienc', 'Zb(nY;w1A62r1R', 'xperienc_uml');
        $sql = 'select name from user where email like "' . $request->get('action') . '"';
        $consult = mysqli_query($conexion, $sql);
        $result = mysqli_fetch_column($consult);

        if ($result != false) {

            //Obtener los demas campos
            $courses_get = $courseRepository->findby(['id' => $request->get('course')])[0];
            $ntipo = $nTARepository->findBy(array('denomination' => 'Interactiva'));
            $activy_get = $nActivityRepository->findBy(['nta' => $ntipo]);
            $activy_get = $nActivityRepository->findBy(['id' => $request->get('nactivity')])[0];

            //Obtengo el data y le reemplazo los \n por espacios
            $data = str_replace("\n", ' ', $request->get('data'));

            $x = new ModelDiagramTest();
            $x->setAction($result); //campo que guarda el name del user logueado(el que está haciendo la actividad de modelado )
            $x->setCourse($courses_get); //curso
            $x->setNActivity($activy_get); //actividad
            $x->setData([$data]); //json de diagrama
            $em->persist($x);
            $em->flush();

            return new Response(
                'Successfully created',
                Response::HTTP_OK
            );
        } else {
            return new Response(
                'Error en el usuario',
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    protected function transformJsonBody(\Symfony\Component\HttpFoundation\Request$request)
    {
        $data = json_decode($request->getContent(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {

            return null;
        }

        if ($data === null) {
            return $request;
        }
        $request->request->replace($data);

        return $request;
    }


}
