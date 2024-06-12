<?php

namespace App\Repository;

use App\Entity\ModelDiagramSuccess;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ModelDiagramSuccess>
 *
 * @method ModelDiagramSuccess|null find($id, $lockMode = null, $lockVersion = null)
 * @method ModelDiagramSuccess|null findOneBy(array $criteria, array $orderBy = null)
 * @method ModelDiagramSuccess[]    findAll()
 * @method ModelDiagramSuccess[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModelDiagramSuccessRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ModelDiagramSuccess::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(ModelDiagramSuccess $entity, bool $flush = true): void
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
    public function remove(ModelDiagramSuccess $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return ModelDiagramSuccess[] Returns an array of ModelDiagramSuccess objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ModelDiagramSuccess
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    
    public function findDataToShowNames()
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT model_diagram_success.*, course.name AS course, nactivity.title AS nactivity, nactivity.description AS description, nactivity.tecsol AS tecsol
                FROM model_diagram_success 
	                LEFT JOIN nactivity ON model_diagram_success.nactivity_id = nactivity.id 
	                LEFT JOIN course ON model_diagram_success.course_id = course.id
	                WHERE model_diagram_success.data != "[]"';
        $resultSet = $conn->executeQuery($sql);
        return $resultSet->fetchAllAssociative(); 
    }

    public function findDataToShowId(int $id)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT nactivity.title AS nactivity, model_diagram_success.*, nactivity.description AS description, nactivity.tecsol AS tecsol, nactivity.place AS place, course.name AS course
                FROM nactivity 
	                LEFT JOIN model_diagram_success ON model_diagram_success.nactivity_id = nactivity.id 
	                LEFT JOIN course ON model_diagram_success.course_id = course.id
                WHERE model_diagram_success.course_id = :id AND model_diagram_success.data!="[]"';
        $resultSet = $conn->executeQuery($sql,['id'=>$id]);
        return $resultSet->fetchAllAssociative(); 
    }


    public function findDataToShowIdAct(int $id)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT nactivity.title AS nactivity, model_diagram_success.*, course.name AS course
                FROM nactivity 
	                LEFT JOIN model_diagram_success ON model_diagram_success.nactivity_id = nactivity.id 
	                LEFT JOIN course ON model_diagram_success.course_id = course.id
                WHERE model_diagram_success.nactivity_id = :id';
        $resultSet = $conn->executeQuery($sql,['id'=>$id]);
        return $resultSet->fetchAllAssociative(); 
    }

    public function findDataToShowIdUser(int $id)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT nactivity.title AS nactivity, model_diagram_success.*, course.name AS course
                FROM nactivity 
	                LEFT JOIN model_diagram_success ON model_diagram_success.nactivity_id = nactivity.id 
	                LEFT JOIN course ON model_diagram_success.course_id = course.id
                    LEFT JOIN course_user ON course_user.course_id = course.id
                WHERE course_user.user_id = :id';
        $resultSet = $conn->executeQuery($sql,['id'=>$id]);
        return $resultSet->fetchAllAssociative(); 
    }
}
