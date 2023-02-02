<?php

namespace App\Repository;

use App\Entity\UserLikePost;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserLikePost>
 *
 * @method UserLikePost|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserLikePost|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserLikePost[]    findAll()
 * @method UserLikePost[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserLikePostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserLikePost::class);
    }

    public function save(UserLikePost $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(UserLikePost $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findUserLikedPost(Array $var = null)
    {
        return $this->getEntityManager()
            ->createQuery('
                SELECT l.user_id,l.post_id,l.like_post
                FROM App:UserLikePost l
                WHERE l.user_id = :user 
                    AND l.post_id = :post
            ')
            ->setParameter('user',$var['user'])
            ->setParameter('post',$var['post'])
            ->getResult();
    }

    public function findTotalByPost($id) {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "
            SELECT 
                *
            FROM user_like_post
            WHERE post_id = :id
        ";

        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['id' => $id]);
        return $resultSet->fetchAllAssociative();
    }

//    /**
//     * @return UserLikePost[] Returns an array of UserLikePost objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?UserLikePost
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
