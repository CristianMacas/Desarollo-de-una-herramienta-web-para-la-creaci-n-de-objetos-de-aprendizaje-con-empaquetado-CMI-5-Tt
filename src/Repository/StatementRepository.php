<?php

namespace App\Repository;

use App\Entity\Statement;   
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Statement>
 *
 * @method Statement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Statement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Statement[]    findAll()
 * @method Statement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StatementRepository extends ServiceEntityRepository
{
    private $manager;
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager)
    {
        parent::__construct($registry, Statement::class);
        $this->manager = $manager;
    }

    // /**
    //  * @return Statement[] Returns an array of Statement objects
    //  */
    /*
    public function findByExampleField($value)
    {
    return $this->createQueryBuilder('s')
    ->andWhere('s.exampleField = :val')
    ->setParameter('val', $value)
    ->orderBy('s.id', 'ASC')
    ->setMaxResults(10)
    ->getQuery()
    ->getResult()
    ;
    }
     */

    /*
    public function findOneBySomeField($value): ?Statement
    {
    return $this->createQueryBuilder('s')
    ->andWhere('s.exampleField = :val')
    ->setParameter('val', $value)
    ->getQuery()
    ->getOneOrNullResult()
    ;
    }
     */

    public function saveStatement($actor, $verb, $result, $context, $object)
    {
        $newstatement = new Statement();
        $date = new \DateTime('@'.strtotime('now'));
        $newstatement->setActor($actor)
        ->setTimestamp($date)
            ->setVerb($verb)
            ->setResult($result)
            ->setContext($context)
            ->setObject($object);
        $this->manager->persist($newstatement);
        $this->manager->flush();
    }

    public function removeStatement(Statement $statement)
    {
        $this->manager->remove($statement);
        $this->manager->flush();

    }

    public function updateStatement(Statement $st): Statement
    {

        $this->manager->persist($st);
        $this->manager->flush();

        return $st;

    }
    
    public function FindAllStatement()
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT statement.id, statement.actor, statement.verb, statement.object, statement.result, statement.context, statement.timestamp, statement.authority, statement.attachments
                FROM statement';
        $resultSet = $conn->executeQuery($sql);
        return $resultSet->fetchAllAssociative(); 
    }
}
