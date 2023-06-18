<?php

namespace App\Repository;

use App\Entity\Film;
use App\Data\SearchData;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Film>
 *
 * @method Film|null find($id, $lockMode = null, $lockVersion = null)
 * @method Film|null findOneBy(array $criteria, array $orderBy = null)
 * @method Film[]    findAll()
 * @method Film[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FilmRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Film::class);
    }

    public function add(Film $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Film $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findSearch(SearchData $search): array
    {
        $query = $this->createQueryBuilder('f');
     
        if(!empty($search->genre))
        {
            $query = $query 
            ->innerJoin('f.genre', 'g')
            ->where('g.id IN (:genre)')
            ->setParameter('genre', $search->genre)
            ;
        }

        if(!empty($search->min))
        {
            $query = $query
            ->andWhere('f.runtime >= :min')
            ->setParameter('min', $search->min)
            ;
        }

        if(!empty($search->max))
        {
            $query = $query
            ->andWhere('f.runtime <= :max')
            ->setParameter('max', $search->max)
            ;
        }

        if(!empty($search->sort))
        {
            if($search->sort == "notes"){
                $query = $query
                ->orderBy('f.rating', 'DESC')
                ;
            } else if($search->sort == "new"){
                $query = $query
                ->orderBy('f.year', 'DESC')
                ;
            } else if($search->sort == "old"){
                $query = $query
                ->orderBy('f.year', 'ASC')
                ;
            }
           
        }

        return $query->getQuery()->getResult();
    } 

    public function findFilmByUser($user): array
    {
        return $this->createQueryBuilder('f')
            ->leftJoin('f.users', 'user')
            ->where('user.id IN (:user)')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult()
        ;
    }

    public function getSimilarsFilms($film): array
    {
        $genres = $film->getGenre();
        $genreIds = [];

        foreach ($genres as $genre) {
            $genreIds[] = $genre->getId();
        }
    
        return $this->createQueryBuilder('f')
        ->innerJoin('f.genre', 'g')
        ->where('g.id = :genreIds')
        ->andWhere('f.id != :filmId')
        ->setParameter('genreIds', $genre->getId())
        ->setParameter('filmId', $film->getId())
        ->getQuery()
        ->getResult();
    }

    public function findByTitle($term): array
    {
        return $this->createQueryBuilder('f')
        ->where('f.title LIKE :term')
        ->setParameter('term', '%'.$term.'%')
        ->getQuery()
        ->getResult();
    }

//    /**
//     * @return Film[] Returns an array of Film objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Film
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
