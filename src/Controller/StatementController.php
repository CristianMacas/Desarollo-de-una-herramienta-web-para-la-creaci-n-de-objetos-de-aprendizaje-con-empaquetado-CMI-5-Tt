<?php

namespace App\Controller;

use App\Entity\Statement;
use App\Repository\StatementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class StatementController extends AbstractController
{

    private $statementRepository;

    public function __construct(StatementRepository $statementRepository)
    {
        $this->statementRepository = $statementRepository;
    }

    /**
     * @author
     * API de declaraciones xApi
     *
     */

/**
 *@Route("/xapi/statements",name="add_statement", methods={"POST"})
 */
    public function add(Request $request): JsonResponse
    {

        $actor = $request->get('actor', null);
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

        $result = $request->get('result', null);
        $context = $request->get('context', null);

        $object = $request->get('object', null);

        if (empty($actor) || empty($object)) {
            throw new NotFoundHttpException('Expecting mandatory parameters');
        }

        //$this->statementRepository->saveStatement($actor, $verb1, $result, $context, $object);
        //$this->statementRepository->saveStatement($actor, $verb2, $result, $context, $object);
        //$this->statementRepository->saveStatement($actor, $verb3, $result, $context, $object);
        return new JsonResponse(['status' => 'Statements created'], Response::HTTP_CREATED);
    }

    /**
     * @Route("/xapi/statements", name="statement_list",methods={"GET"})
     */
    function list(Request $request, StatementRepository $statementRepository) {

        $statements = $statementRepository->findAll();
        $statementsArray = [];
/**@var Statement $st */
        foreach ($statements as $st) {
            $statementsArray[] = [
                'id' => $st->getId(),
                'actor' => $st->getActor(),
                'verb' => $st->getVerb(),
                'object' => $st->getObject(),
            ];
        }

        $response = new JsonResponse();
        $response->setData(
            [
                'success' => true,
                'data' => $statementsArray,

            ]

        );

        return $response;
    }

    /**
     * @Route("/xapi/statements/{id}", name="statement_update",methods={"PUT"})
     */
    public function update($id, Request $request): Response
    {

        $st = $this->statementRepository->find($id);
        if (!$st) {
            return $this->json('No Statement found for id' . $id, 404);
        }

        $st->setActor($request->get('actor'));
        $st->setVerb($request->get('verb'));
        $st->setObject($request->get('object'));
        $updateStatement = $this->statementRepository->updateStatement($st);

        $data = [
            'id' => $st->getId(),
            'actor' => $st->getActor(),
            'verb' => $st->getVerb(),
            'object' => $st->getObject(),
        ];

        return $this->json($data);

    }

    /**
     * @Route("/xapi/statements/{id}", name="statement_delete",methods={"DELETE"})
     */
    public function destroy($id): Response
    {
        $st = $this->statementRepository->find($id);
        if (!$st) {
            return $this->json('No Statement found for id' . $id, 404);
        }
        $this->statementRepository->removeStatement($st);

        return $this->json('Deleted a statement successfully with id' . $id);

    }

    /**
     * @Route("/xapi/statements/{id}", name="statement_show",methods={"GET"})
     */
    public function show($id): Response
    {
        $st = $this->statementRepository->find($id);
        if (!$st) {
            return $this->json('No Statement found for id' . $id, 404);
        }

        $data = [
            'id' => $st->getId(),
            'actor' => $st->getActor(),
            'verb' => $st->getVerb(),
            'object' => $st->getObject(),

        ];

        return $this->json($data);

    }

}
