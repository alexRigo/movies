<?php

namespace App\Repository;

use App\Entity\Film;
use App\Entity\User;
use App\Entity\Critic;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Critic>
 *
 * @method Critic|null find($id, $lockMode = null, $lockVersion = null)
 * @method Critic|null findOneBy(array $criteria, array $orderBy = null)
 * @method Critic[]    findAll()
 * @method Critic[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CriticRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Critic::class);
    }

    public function add(Critic $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Critic $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findCriticByUserAndFilm(Film $film, User $user)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.user = :user')
            ->setParameter('user', $user)
            ->andWhere('c.film = :film')
            ->setParameter('film', $film)
            ->getQuery()
            ->getResult()
            ;
    }

    public function getAverageNoteByFilm(Film $film)
    {
        return $this->createQueryBuilder('c')
            ->select('avg(c.note)')
            ->andWhere('c.film = :film')
            ->setParameter('film', $film)
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }

    public function findCriticByUser($user): array
    {
        return $this->createQueryBuilder('c')
            ->leftJoin('c.user', 'user')
            ->andWhere('user.id IN (:user)')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult()
            
        ;
       
    }

//    /**
//     * @return Critic[] Returns an array of Critic objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Critic
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
