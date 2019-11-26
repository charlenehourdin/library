<?php


namespace App\Controller;

use App\Entity\Author;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;



class AuthorController extends AbstractController
{
    /**
     * 1er parametre = chemin de l'url
     * 2eme parametre = nom de la route (identifiant pour pouvoir retrouver le chemin)
     * @Route ("/authors", name="authors")
     */

    //méthode qui permet de faire un select en BDD de l'ensemble de mes champs dans ma table author
    public function AuthorList (AuthorRepository $authorRepository)
    {
        // j'utilise le repository de author afin de pouvoir selectionner tous les élèments de ma table
        //Les repository en général servent à faire les requêtes select dans les tables de ma BDD

        //findAll permet de récupérer tout les element de ma table author
        $authors = $authorRepository->findAll();

        return $this->render('authors.html.twig', ['authors' => $authors]);
    }
    /**
     * @Route ("/author/{id}", name="author")
     */


    public function Author (AuthorRepository $authorRepository, $id)
    {
        // j'utilise le repository de author afin de pouvoir selectionner l'id de l'élèments de ma table
        // find permet de récupéré un element de ma table author
        $author = $authorRepository->find($id);

        return $this->render('author.html.twig', ['author' => $author]);
    }

    /**
     * @Route("/authors_by_biography", name="authors_by_biography")
     */
        //l'url "/authors_by_biography" qui a le nom "authors_by_biography" retourne la methode suivante :

    public function getAuthorsByBiography(AuthorRepository $authorRepository)
    {
        // Appelle le AuthorRepository (en le passant en parametre de la méthode)
        // Appelle la méthode qu'on a créer dans le AuthorRepository ("getByBiography()")
        // Cette méthode est censée nous retourner tous les mots en fonction d'un genre
        // Elle va donc executer une requete SELECT en base de données

        $word = 'a';

        // AuthorRepository contient une instance de la classe "AuthorRepository"
        // Généralement on obtient une instance de classe (ou un objet) en utilisant le mot clé "new"
        // ici, grace à symfony, on obtient l'instance de la classe repository en la passant simplement en paramètre
        $authors = $authorRepository->getByBiography($word);
        return $this->render('bio.html.twig', ['author' => $authors]);

    }
    /**
     * @Route("/authors/insert", name="authors_insert")
     */
    public function insertAuthors(EntityManagerInterface $entityManager)
    {
        // Insérer dans la table book un nouvelle auteur
        $author = new Author();
        $author->setName('Franck');
        $author->setFirstName('Anne');
        $author->setBirthDate(new \DateTime('12-06-1929'));
        $author->setDeathDate(new \DateTime('00-02-1945'));
        $author->setBiography('Annelies Marie Frank, plus connue sous le nom d’Anne Frank, 
                                         née le 12 juin 1929 à Francfort-sur-le-Main en Allemagne,
                                        sous la République de Weimar, et morte en février 1945 ou mars 1945 à Bergen-Belsen en Allemagne nazie, 
                                         est une adolescente allemande, connue pour avoir écrit un journal intime.');

        $entityManager->persist($author);
        $entityManager->flush();

        return $this->render('insertAuthor.html.twig', ['author' => $author]);
    }

}


