<?php

namespace App\Repository;

use App\Entity\PostCommentary;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PostCommentary>
 *
 * @method PostCommentary|null find($id, $lockMode = null, $lockVersion = null)
 * @method PostCommentary|null findOneBy(array $criteria, array $orderBy = null)
 * @method PostCommentary[]    findAll()
 * @method PostCommentary[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostCommentaryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PostCommentary::class);
    }

    public function save(PostCommentary $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(PostCommentary $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function messagesByPost($id = null)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "
            SELECT 
                 pc.id, pc.message, u.username,pc.creation_date
            FROM post_commentary pc
            INNER JOIN user u ON pc.user_id = u.id
            WHERE pc.post_id = :id
            ORDER BY pc.creation_date DESC
        ";

        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['id' => $id]);
        return $resultSet->fetchAllAssociative();
    }

//    /**
//     * @return PostCommentary[] Returns an array of PostCommentary objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?PostCommentary
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
