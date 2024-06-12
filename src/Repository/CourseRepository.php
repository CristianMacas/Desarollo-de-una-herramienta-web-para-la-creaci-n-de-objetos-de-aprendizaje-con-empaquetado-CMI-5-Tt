<?php

namespace App\Repository;

use App\Entity\Course;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Course>
 *
 * @method Course|null find($id, $lockMode = null, $lockVersion = null)
 * @method Course|null findOneBy(array $criteria, array $orderBy = null)
 * @method Course[]    findAll()
 * @method Course[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CourseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Course::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Course $entity, bool $flush = true): void
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
    public function remove(Course $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function findModelsTestByCourseId(int $id)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT nactivity.title AS nactivity, model_diagram_test.*, course.name AS course
                FROM nactivity 
	                LEFT JOIN model_diagram_test ON model_diagram_test.nactivity_id = nactivity.id 
	                LEFT JOIN course ON model_diagram_test.course_id = course.id
                WHERE model_diagram_test.course_id = :id';
        $resultSet = $conn->executeQuery($sql,['id'=>$id]);
        return $resultSet->fetchAllAssociative();
    }
    
    public function findDataToShowIdSuccessByCourse(int $id)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT nactivity.id, nactivity.title, nactivity.description, nta.denomination, course.name, nactivity.tecsol
                FROM nactivity 
	                LEFT JOIN nta ON nactivity.nta_id = nta.id 
	                LEFT JOIN model_diagram_success ON model_diagram_success.nactivity_id = nactivity.id 
	                LEFT JOIN course ON model_diagram_success.course_id = course.id
                WHERE course.id = model_diagram_success.course_id AND model_diagram_success.nactivity_id = nactivity.id AND nta.id = nactivity.nta_id AND course.id = :id';
        $resultSet = $conn->executeQuery($sql,['id'=>$id]);
        return $resultSet->fetchAllAssociative();
    }
    
    public function findUserByCourseId(int $id)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT course.name AS course, user.name, user.email
                FROM user 
                    LEFT JOIN course_user ON course_user.user_id = user.id 
                    LEFT JOIN course ON course_user.course_id = course.id
                WHERE course.id = :id';
        $resultSet = $conn->executeQuery($sql,['id'=>$id]);
        return $resultSet->fetchAllAssociative();
    }
    
    public function deleteCourse(int $id)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql='DELETE FROM course WHERE id = :id';
        $resultSet = $conn->executeQuery($sql,['id'=>$id]);
    }
}
