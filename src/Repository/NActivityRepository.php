<?php

namespace App\Repository;

use App\Entity\NActivity;
use App\Entity\Course;
use App\Entity\ModelDiagramTest;
//use App\Entity\ModelDiagramSuccess;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<NActivity>
 *
 * @method NActivity|null find($id, $lockMode = null, $lockVersion = null)
 * @method NActivity|null findOneBy(array $criteria, array $orderBy = null)
 * @method NActivity[]    findAll()
 * @method NActivity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NActivityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NActivity::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(NActivity $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(NActivity $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

  
    public function findDataToShow()
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT nactivity.title AS title, nactivity.id AS id, nactivity.description AS description, nactivity.place AS place, nta.denomination As denomination, nactivity.tecsol AS tecsol, model_diagram_success.id AS idsuccess, model_diagram_success.data AS data, course.name AS name, course.id as curso
                FROM model_diagram_success 
	                LEFT JOIN course ON model_diagram_success.course_id = course.id 
	                LEFT JOIN nactivity ON model_diagram_success.nactivity_id = nactivity.id 
	                LEFT JOIN nta ON nactivity.nta_id = nta.id
	            WHERE nactivity.id IS NOT NULL';
        $resultSet = $conn->executeQuery($sql);
        return $resultSet->fetchAllAssociative();
    }


    public function findDataToShowId(int $id)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT nactivity.id, nactivity.title, nactivity.description, nactivity.place, nactivity.tecsol, nta.denomination, model_diagram_test.data, course.name, course.id as curso
                FROM nactivity  
	                LEFT JOIN nta ON nactivity.nta_id = nta.id 
	                LEFT JOIN model_diagram_test ON model_diagram_test.nactivity_id = nactivity.id 
	                LEFT JOIN course ON model_diagram_test.course_id = course.id
                WHERE course.id = model_diagram_test.course_id AND model_diagram_test.nactivity_id = nactivity.id AND nta.id = nactivity.nta_id AND nactivity.id = :id';
        $resultSet = $conn->executeQuery($sql,['id'=>$id]);
        return $resultSet->fetchAllAssociative()[0];
    }

    public function findDataToShowSucces()
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT nactivity.id, nactivity.title, nactivity.description, nactivity.place, nta.denomination, model_diagram_success.data, course.name, course.id as curso
                FROM nactivity 
	                LEFT JOIN nta ON nactivity.nta_id = nta.id 
	                LEFT JOIN model_diagram_success ON model_diagram_success.nactivity_id = nactivity.id 
	                LEFT JOIN course ON model_diagram_success.course_id = course.id
                WHERE course.id = model_diagram_success.course_id AND model_diagram_success.nactivity_id = nactivity.id AND nta.id = nactivity.nta_id';
        $resultSet = $conn->executeQuery($sql);
        return $resultSet->fetchAllAssociative();
    }


    public function findDataToShowIdSuccessByCourse(int $id)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT nactivity.title AS title, nactivity.id AS id, nactivity.description AS description, nactivity.place AS place, nta.denomination As denomination, nactivity.tecsol AS tecsol, model_diagram_success.id AS idsuccess, course.id as curso, course.name AS name, model_diagram_success.data AS data
                FROM nactivity 
	                INNER JOIN model_diagram_success ON model_diagram_success.nactivity_id = nactivity.id 
	                INNER JOIN course ON model_diagram_success.course_id = course.id
	                INNER JOIN nta ON nactivity.nta_id = nta.id 
                WHERE course.id = :id';
        $resultSet = $conn->executeQuery($sql,['id'=>$id]);
        return $resultSet->fetchAllAssociative();
    }

    public function findDataToShowIdSuccessByActivity(int $id)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT nactivity.title AS title, nactivity.id AS id, nactivity.description AS description, nactivity.place AS place, nta.denomination AS denomination, nactivity.tecsol AS tecsol, model_diagram_success.id AS idsuccess, course.id as curso, course.name AS name, model_diagram_success.data AS data
                FROM nactivity 
                    LEFT JOIN nta ON nactivity.nta_id = nta.id 
	                LEFT JOIN model_diagram_success ON model_diagram_success.nactivity_id = nactivity.id
                    LEFT JOIN course ON model_diagram_success.course_id = course.id
                WHERE course.id = model_diagram_success.course_id AND model_diagram_success.nactivity_id = nactivity.id AND nta.id = nactivity.nta_id AND nactivity.id = :id';
        $resultSet = $conn->executeQuery($sql,['id'=>$id]);
        return $resultSet->fetchAssociative()   ;
    }
    
    public function findIdDiagramSucces(int $id)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT model_diagram_success.id, model_diagram_success.data, model_diagram_success.archive
                FROM model_diagram_success
                WHERE model_diagram_success.nactivity_id = :id';
        $resultSet = $conn->executeQuery($sql,['id'=>$id]);
        return $resultSet->fetchAllAssociative();
    }
    
    public function findDataToDelete(int $id)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT nactivity.id AS id, model_diagram_success.id AS idsuccess, model_diagram_success.data AS data
                FROM model_diagram_success 
	                LEFT JOIN course ON model_diagram_success.course_id = course.id 
	                LEFT JOIN nactivity ON model_diagram_success.nactivity_id = nactivity.id 
	                LEFT JOIN nta ON nactivity.nta_id = nta.id
	            WHERE nactivity.id = :id AND model_diagram_success.data = "[]"';
        $resultSet = $conn->executeQuery($sql,['id'=>$id]);
        return $resultSet->fetchAllAssociative();
    }

    public function deleteDiagramAssocTest(int $id)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql1 = 'delete FROM model_diagram_test WHERE nactivity_id = :id ';
        $resultSet1 = $conn->executeQuery($sql1,['id'=>$id]);
        return $resultSet1->fetchAllAssociative();

    }

    public function deleteDiagramAssocSucces(int $id)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql1 = 'delete FROM model_diagram_success WHERE nactivity_id = :id ';
        $resultSet1 = $conn->executeQuery($sql1,['id'=>$id]);
        return $resultSet1->fetchAllAssociative();

    }

}
