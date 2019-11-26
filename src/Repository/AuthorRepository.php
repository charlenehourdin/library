<?php

namespace App\Repository;

use App\Entity\Author;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Author|null find($id, $lockMode = null, $lockVersion = null)
 * @method Author|null findOneBy(array $criteria, array $orderBy = null)
 * @method Author[]    findAll()
 * @method Author[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuthorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Author::class);
    }

       public function getByBiography()
       {
           // Récupérer le query builder (car c'est le query builder qui permet de faire la requête SQL)

           $word ='ecrivain';
           $queryBuilder = $this->createQueryBuilder('a');

           //CreateQueryBuilder = createur de requetes SQL

           // On construit la requête façon SQL, mais en PHP
           // Traduire la requête en véritable requête SQL

           $query = $queryBuilder->select('a')
                ->where('a.biography LIKE :word')
               //where creer la condition
                ->setParameter('word', '%'.$word.'%')
               // setParameter permet d'echapper ce que l'utilisateur rentre il securise la requete
                ->getQuery();

           // - Executer la requête SQL en base de données pour récupérer sous forme de tableau
           $author = $query->getArrayResult();
           return $author;
       }
}
