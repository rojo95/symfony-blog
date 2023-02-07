<?php

namespace App\Repository;

use App\Entity\Post;
use App\Entity\PostType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Post>
 *
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function save(Post $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Post $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * funcion para buscar post por id
     * se utilizo sql ya que sqlite no permite las relaciones
     * @param $id identificador del post
     */
    public function findPostbyId($id): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "
            SELECT 
                p.id, p.title, p.description, p.creation_date, p.watched, p.url, p.file,
                u.name, u.username, p.update_date, p.user_id, p.status, p.likes, p.dislikes,
                CASE 
                    WHEN p.type = 1 THEN 'Opinión' 
                    WHEN p.type = 2 THEN 'Noticias' 
                    WHEN p.type = 3 THEN 'Historia' 
                END as type 
            FROM post p
            --INNER JOIN post_type pt ON p.type = pt.id
            INNER JOIN user u ON p.user_id = u.id
            WHERE p.id = :id
            ORDER BY p.creation_date DESC
        ";

        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['id' => $id]);
        return $resultSet->fetchAssociative();
    }

    /**
     * funcion para mostrar todos los post
     * se utilizo sql ya que sqlite no permite las relaciones
     */
    public function getAll()
    {
        return $this->getEntityManager()
        ->createQuery('
            SELECT p.id, p.title, p.description, p.creation_date, p.watched, p.url, p.file, p.user_id, p.type as post_type
            FROM App:post p
            WHERE p.status = true
            ORDER BY p.creation_date DESC
        ');
        
        // $conn = $this->getEntityManager()->getConnection();

        // $sql = '
        //     SELECT p.id, p.title, p.description, p.creation_date, p.watched, p.url, p.file, p.user_id, pt.name as post_type
        //     FROM post p
        //     INNER JOIN post_type pt ON p.type = pt.id
        //     WHERE p.status = true
        //     ORDER BY p.creation_date DESC
        //     LIMIT 10
        // ';

        // $stmt = $conn->prepare($sql);
        // $resultSet = $stmt->executeQuery();
        // return $resultSet;
        // return $resultSet->fetchAllAssociative();
        
    }

    public function findByUser(int $id = null)
    {
        return $this->getEntityManager()
        ->createQuery('
            SELECT p.id, p.title, p.description, p.creation_date, p.watched, p.url, p.file, p.user_id, p.type as post_type, p.likes, p.dislikes
            FROM App:post p
            WHERE p.user_id = :id
            ORDER BY p.creation_date DESC
        ')->setParameter('id',$id);
    }

//    /**
//     * @return Post[] Returns an array of Post objects
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

//    public function findOneBySomeField($value): ?Post
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
