<?php

namespace App\Repository;

use App\Entity\PostType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PostType>
 *
 * @method PostType|null find($id, $lockMode = null, $lockVersion = null)
 * @method PostType|null findOneBy(array $criteria, array $orderBy = null)
 * @method PostType[]    findAll()
 * @method PostType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PostType::class);
    }

    public function save(PostType $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(PostType $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function showPostTypes(): array
    {
        return $this->getEntityManager()
            ->createQuery('
                SELECT post.id, post.name 
                FROM App:post post 
            ')
            ->getResult()
        ;
    }

//    /**
//     * @return PostType[] Returns an array of PostType objects
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

//    public function findOneBySomeField($value): ?PostType
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
