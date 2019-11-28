<?php


namespace App\Controller;

use App\Entity\Author;
use App\Entity\Book;
use App\Form\AuthorType;
use App\Form\BookType;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;



class AuthorController extends AbstractController
{

    /**
     * @Route ("/admin_authors", name="admin_authors")
     */

    //méthode qui permet de faire un select en BDD de l'ensemble de mes champs dans ma table author
    public function AdminAuthorList (AuthorRepository $authorRepository)
    {
        // j'utilise le repository de author afin de pouvoir selectionner tous les élèments de ma table
        //Les repository en général servent à faire les requêtes select dans les tables de ma BDD

        //findAll permet de récupérer tout les element de ma table author
        $authors = $authorRepository->findAll();

        return $this->render('admin/author/authors.html.twig', ['authors' => $authors]);
    }

    /**
     * @Route ("/authors", name="authors")
     */

    //méthode qui permet de faire un select en BDD de l'ensemble de mes champs dans ma table author
    public function AuthorList (AuthorRepository $authorRepository)
    {
        // j'utilise le repository de author afin de pouvoir selectionner tous les élèments de ma table
        //Les repository en général servent à faire les requêtes select dans les tables de ma BDD

        //findAll permet de récupérer tout les element de ma table author
        $authors = $authorRepository->findAll();

        return $this->render('author/authors.html.twig', ['authors' => $authors]);
    }



    /**
     * @Route("authors_by_biography", name="authors_by_biography")
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
        return $this->render('author/bio.html.twig', ['author' => $authors]);


    }

    // j'ajoute un nouvel auteur en BDD
    /**
     * @Route("/author/insert", name="author_insert")
     */
    public function insertAuthors(EntityManagerInterface $entityManager)
    {
        // Insérer dans la table book un nouvelle auteur
        $author = new Author();
        $author->setName('Spark');
        $author->setFirstName('Nicolas');
        $author->setBirthDate(new \DateTime('31-12-1965'));
        $author->setDeathDate(new \DateTime(''));
        $author->setBiography('Nicholas Sparks, né le 31 décembre 1965 à Omaha, au Nebraska, est un écrivain américain.
                                        Ses romans évoquent les rencontres amoureuses et l\'amour en général.');

        $entityManager->persist($author);
        $entityManager->flush();

        return $this->render('author/insertAuthor.html.twig', ['author' => $author]);
    }
    //Je supprime mon auteur en BDD

    /**
     * @Route("Admin/author/delete{id}", name="author_delete")
     */
    public function deleteBook(AuthorRepository $authorRepository, EntityManagerInterface $entityManager, $id)
    {
        $authors = $authorRepository->find($id);
        $entityManager->remove($authors);
        $entityManager->flush();

        return $this->render('Admin/author/deleteAuthor.html.twig', ['authors' => $authors]);
    }

    /**
     * @Route("author/insert_form", name="author_insert_form")
     */
    public function insertAuthorForm(Request $request, EntityManagerInterface $entityManager)
    {

        $author = new Author();

        $authorForm = $this->createForm(AuthorType::class, $author);

        if ($request->isMethod('Post')) {

            $authorForm->handleRequest($request);

            if ($authorForm->isValid()) {

                $entityManager->persist($author);
                $entityManager->flush();

                return $this->redirectToRoute('author_insert');
            }
        }
        // à partir de mon gabarit, je crée la vue de mon formulaire
        $authorFormView = $authorForm->createView();
        // je retourne un fichier twig, et je lui envoie ma variable qui contient
        // mon formulaire
        return $this->render('author/insert_form.html.twig', [
            'authorFormView' => $authorFormView
        ]);
    }

//modifier les données en BDD avec le formulaire

    /**
     * @Route("author/update_form{id}", name="author_update_form")
     */
    public function updateAuthorForm(AuthorRepository $authorRepository, Request $request, EntityManagerInterface $entityManager, $id)
    {
        $author = $authorRepository->find($id);
        $authorForm = $this->createForm(AuthorType::class, $author);
        if ($request->isMethod('Post'))
        {
            $authorForm->handleRequest($request);
            if ($authorForm->isValid()) {

                $entityManager->persist($author);
                $entityManager->flush();
                return $this->redirectToRoute('author_update',['id'=> $id]);
            }
        }
        // à partir de mon gabarit, je crée la vue de mon formulaire
        $authorFormView = $authorForm->createView();
        // je retourne un fichier twig, et je lui envoie ma variable qui contient
        // mon formulaire
        return $this->render('author/update_form.html.twig', [
            'authorFormView' => $authorFormView
        ]);
    }
    //Mettre à jour un auteur en BDD

    /**
     * @Route("author/update{id}", name="author_update")
     */
    public function updateAuthor(AuthorRepository $authorRepository, EntityManagerInterface $entityManager, $id)
    {
        //J'utilise le repository de l'entité Author pour récupérer un auteur en fonction de son id
        $author = $authorRepository->find($id);

        //Je change le nom et le prenom de mon auteur
        $author->setName('Spark');
        $author->setFirstName("Nicolas");

        // Je re-enregistre mon auteur en BDD avec l'entité manager
        $entityManager->persist($author);
        $entityManager->flush();

        return $this->render('author/updateAuthor.html.twig', ['author' => $author]);
    }
    //Je selectionne un auteur en BDD


    // 1er parametre = chemin de l'url
    // 2eme parametre = nom de la route (identifiant pour pouvoir retrouver le chemin)
    /**
     * @Route ("author/{id}", name="author")
     */


    public function Author (AuthorRepository $authorRepository, $id)
    {
        // j'utilise le repository de author afin de pouvoir selectionner l'id de l'élèments de ma table
        // find permet de récupéré un element de ma table author
        $author = $authorRepository->find($id);

        return $this->render('author/author.html.twig', ['author' => $author]);
    }

}


