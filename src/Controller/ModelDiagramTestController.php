<?php

namespace App\Controller;

use App\Entity\ModelDiagramTest;   
use App\Form\ModelDiagramTestType;
use App\Repository\ModelDiagramTestRepository;
use App\Repository\StatementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @Route("/model/diagram/test")
 */
class ModelDiagramTestController extends AbstractController
{

    private $statementRepository;

    public function __construct(StatementRepository $statementRepository, TokenStorageInterface $token)
    {
        $this->statementRepository = $statementRepository;
        $this->token = $token;
    }
    /**
     * @Route("/", name="app_model_diagram_test_index", methods={"GET"})
     */
    public function index(Request $request, ModelDiagramTestRepository $modelDiagramTestRepository): Response
    {

        return $this->render('model_diagram_test/index.html.twig', [
            'model_diagram_tests' => $modelDiagramTestRepository->findDataToShowALL(),
        ]);
    }

    /**
     * @Route("/{id}/course", name="app_model_diagram_test_index1", methods={"GET"})
     */
    public function indexId(Request $request, ModelDiagramTestRepository $modelDiagramTestRepository,int $id): Response
    {
        return $this->render('model_diagram_test/index.html.twig', [
            'model_diagram_tests' => $modelDiagramTestRepository->findDataToShowIdC($id),
        ]);
    }
    
    /**
     * @Route("/{id}/user", name="app_model_diagram_test_index2", methods={"GET"})
     */
    public function indexIdU(Request $request, ModelDiagramTestRepository $modelDiagramTestRepository,int $id): Response
    {
        return $this->render('model_diagram_test/index.html.twig', [
            'model_diagram_tests' => $modelDiagramTestRepository->findDataToShowId($id),
        ]);
    }

    /**
     * @Route("/new", name="app_model_diagram_test_new", methods={"GET", "POST"})
     */
    function new (Request $request, ModelDiagramTestRepository $modelDiagramTestRepository): Response {
        $modelDiagramTest = new ModelDiagramTest();
        $form = $this->createForm(ModelDiagramTestType::class, $modelDiagramTest);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
        $modelossatisfactorios = $em->getRepository('App:ModelDiagramSuccess')->findAll();

        if (!(empty($modelossatisfactorios)) && $form->isSubmitted() && $form->isValid()) {
            $actor = $request->get('actor', array(
                'name' => $modelDiagramTest->getAction(),
                'mbox' => $this->token->getToken()->getUser()->getEmail(),
            ));
            $verb1 = $request->get('verb', array(
                'id' => 'http://adlnet.gov/expapi/verbs/lanzado',
                'display' => array(
                    'en-US' => 'launched',
                    'es' => 'lanzado',
                ),
            ));
            $verb2 = $request->get('verb', array(
                'id' => 'http://adlnet.gov/expapi/verbs/inicializado',
                'display' => array(
                    'en-US' => 'initialized',
                    'es' => 'inicializado',
                ),
            ));
            $verb3 = $request->get('verb', array(
                'id' => 'http://adlnet.gov/expapi/verbs/terminado',
                'display' => array(
                    'en-US' => 'terminated',
                    'es' => 'terminado',
                ),
            ));
            $object = $request->get('object', array(
                'objectType' => $modelDiagramTest->getNActivity()->getNTA()->getDenomination(),
                'id' => $modelDiagramTest->getNActivity()->getId(),
                'definition' => $modelDiagramTest->getNActivity()->getTitle(),

            ));

            $result = $request->get('result', array(
                'completion' => true,
                'success' => true,

            ));
            $context = $request->get('context', array(
                'instructor' => $modelDiagramTest->getAction(),
                'team' => $modelDiagramTest->getAction(),
                'contextActivities' => $modelDiagramTest->getCourse()->getName(),
                'platform' => 'XPerienceUML',
                'extensions' => 'http://localhost/v2cristian/umlexperience/location": "umlexprience"',

            ));

            //$this->statementRepository->saveStatement($actor, $verb1, $result, $context, $object);
            //$this->statementRepository->saveStatement($actor, $verb2, $result, $context, $object);
            //$this->statementRepository->saveStatement($actor, $verb3, $result, $context, $object);

            $modelDiagramTestRepository->add($modelDiagramTest);

            $this->addFlash("success", 'Declaración xapi actualizada satisfactoriamente !!! ');
            $this->addFlash("success", 'Insertado satisfactoriamente!!! ');
            return $this->redirectToRoute('app_model_diagram_test_index', [], Response::HTTP_SEE_OTHER);
        } else {
            $this->addFlash('error', 'El profesor no ha publicado la solución de este ejercicio,pruebe más tarde !!!');

        }

        return $this->render('model_diagram_test/new.html.twig', [
            'model_diagram_test' => $modelDiagramTest,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_model_diagram_test_show", methods={"GET"})
     */
    public function show(Request $request, ModelDiagramTest $modelDiagramTest): Response
    {
        /*$em = $this->getDoctrine()->getManager();
        $solucionesmodelo = $em->getRepository('App:ModelDiagramSuccess')->findAll();
        $modelosestudiante = $em->getRepository('App:ModelDiagramTest')->findAll();
        foreach ($solucionesmodelo as $solm) {
            $jsonArray1 = $solm->getData();

        }

        foreach ($modelosestudiante as $solest) {
            $jsonArray2 = $solest->getData();

        }
        $actor = $request->get('actor', array(
            'name' => $modelDiagramTest->getAction(),
            'mbox' => $this->token->getToken()->getUser()->getEmail(),
        ));
        $object = $request->get('object', array(
            'objectType' => $modelDiagramTest->getNActivity()->getNTA()->getDenomination(),
            'id' => $modelDiagramTest->getNActivity()->getId(),
            'definition' => $modelDiagramTest->getNActivity()->getTitle(),

        ));

        $context = $request->get('context', array(
            'instructor' => $modelDiagramTest->getAction(),
            'team' => $modelDiagramTest->getAction(),
            'contextActivities' => $modelDiagramTest->getCourse()->getName(),
            'platform' => 'XPerienceUML',
            'extensions' => 'http://localhost/v2cristian/umlexperience/location": "umlexprience"',

        ));

        $incorrecto = true;
        $verbo = null;

        $compare = array_diff($jsonArray1, $jsonArray2);
        if (empty($compare)) {
            $incorrecto = false;

            $verbo = $request->get('verb', array(
                'id' => 'http://adlnet.gov/expapi/verbs/satisfecho',
                'display' => array(
                    'en-US' => 'satisfied',
                    'es' => 'satisfecho',
                ),
            ));
            $result = $request->get('result', array(
                'completion' => true,
                'success' => true,
                'extensions' => 'http://localhost/v2cristian/umlexperience/calification": "10"',

            ));

        } else {

            $incorrecto = true;
            $verbo = $request->get('verb', array(
                'id' => 'http://adlnet.gov/expapi/verbs/fallido',
                'display' => array(
                    'en-US' => 'failed',
                    'es' => 'fallido',
                ),
            ));
            $result = $request->get('result', array(
                'completion' => true,
                'success' => true,
                'extensions' => 'http://localhost/v2cristian/umlexperience/calification": "0"',

            ));

        }*/

        //$this->statementRepository->saveStatement($actor, $verbo, $result, $context, $object);

        return $this->render('model_diagram_test/show.html.twig', [
            'model_diagram_test' => $modelDiagramTest/*,
            'incorrecto' => $incorrecto*/,

        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_model_diagram_test_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, ModelDiagramTest $modelDiagramTest, ModelDiagramTestRepository $modelDiagramTestRepository): Response
    {
        $form = $this->createForm(ModelDiagramTestType::class, $modelDiagramTest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $modelDiagramTestRepository->add($modelDiagramTest);
            $this->addFlash('success', 'Datos actualizados satisfactoriamente !!!');
            return $this->redirectToRoute('app_model_diagram_test_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('model_diagram_test/edit.html.twig', [
            'model_diagram_test' => $modelDiagramTest,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_model_diagram_test_delete", methods={"POST"})
     */
    public function delete(Request $request, ModelDiagramTest $modelDiagramTest, ModelDiagramTestRepository $modelDiagramTestRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $modelDiagramTest->getId(), $request->request->get('_token'))) {
            $modelDiagramTestRepository->remove($modelDiagramTest);
            $this->addFlash('success', 'Datos eliminados satisfactoriamente !!!');
        }

        return $this->redirectToRoute('app_model_diagram_test_index', [], Response::HTTP_SEE_OTHER);
    }
}
