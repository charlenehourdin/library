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

}


