<?php

namespace App\Repository;

use App\Entity\ModelDiagramTest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ModelDiagramTest>
 *
 * @method ModelDiagramTest|null find($id, $lockMode = null, $lockVersion = null)
 * @method ModelDiagramTest|null findOneBy(array $criteria, array $orderBy = null)
 * @method ModelDiagramTest[]    findAll()
 * @method ModelDiagramTest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModelDiagramTestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ModelDiagramTest::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(ModelDiagramTest $entity, bool $flush = true): void
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
    public function remove(ModelDiagramTest $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

     /**
      * @return ModelDiagramTest objects
      */
    
    /*public function findByNActivityId($value): ?ModelDiagramTest
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.nactivity_id = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }*/
    

    /*
    public function findOneBySomeField($value): ?ModelDiagramTest
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findDataToShowId(int $id)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT nactivity.title AS nactivity, model_diagram_test.*, course.name AS course, user.name AS user
                FROM nactivity 
	                LEFT JOIN model_diagram_test ON model_diagram_test.nactivity_id = nactivity.id 
	                LEFT JOIN course ON model_diagram_test.course_id = course.id
	                , user
                WHERE model_diagram_test.action = user.name AND user.id = :id AND model_diagram_test.data!="[]"';
        $resultSet = $conn->executeQuery($sql,['id'=>$id]);
        return $resultSet->fetchAllAssociative();
    }
    
    public function findDataToShowIdU(int $id)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT nactivity.title AS nactivity, model_diagram_test.*, course.name AS course, user.name AS user
                FROM nactivity 
	                LEFT JOIN model_diagram_test ON model_diagram_test.nactivity_id = nactivity.id 
	                LEFT JOIN course ON model_diagram_test.course_id = course.id
	                , user
                WHERE model_diagram_test.action = user.name AND user.id = :id ';
        $resultSet = $conn->executeQuery($sql,['id'=>$id]);
        return $resultSet->fetchAllAssociative();
    }
    
    public function findDataToShowIdC(int $id)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT nactivity.title AS nactivity, model_diagram_test.*, course.name AS course, user.name AS user
                FROM nactivity 
	                LEFT JOIN model_diagram_test ON model_diagram_test.nactivity_id = nactivity.id 
	                LEFT JOIN course ON model_diagram_test.course_id = course.id
	                , user
                WHERE model_diagram_test.action = user.name AND course.id = :id AND model_diagram_test.data!="[]"';
        $resultSet = $conn->executeQuery($sql,['id'=>$id]);
        return $resultSet->fetchAllAssociative();
    }


    public function findDataToShow()
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT nactivity.id, nactivity.title, nactivity.description, nactivity.place, nta.denomination, model_diagram_test.data, model_diagram_test.Fecha, course.name
                FROM nactivity 
	                LEFT JOIN nta ON nactivity.nta_id = nta.id 
	                LEFT JOIN model_diagram_test ON model_diagram_test.nactivity_id = nactivity.id 
	                LEFT JOIN course ON model_diagram_test.course_id = course.id
                WHERE course.id = model_diagram_test.course_id AND model_diagram_test.nactivity_id = nactivity.id AND nta.id = nactivity.nta_id';
        $resultSet = $conn->executeQuery($sql);
        return $resultSet->fetchAllAssociative();
    }
    
    public function findDataToShowALL()
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT nactivity.title AS nactivity, model_diagram_test.*, course.name AS course, user.name AS user
                FROM nactivity 
	                LEFT JOIN model_diagram_test ON model_diagram_test.nactivity_id = nactivity.id 
	                LEFT JOIN course ON model_diagram_test.course_id = course.id
	                , user
                WHERE model_diagram_test.action = user.name AND model_diagram_test.data!="[]"';
        $resultSet = $conn->executeQuery($sql);
        return $resultSet->fetchAllAssociative();
    }
}
