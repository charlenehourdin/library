<?php


namespace App\Controller;

use App\Entity\Book;

use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Routing\Annotation\Route;



class BookController extends AbstractController
{
    /**
     * @Route ("/books", name="books")
     */

    public function BooksList(BookRepository $bookRepository)
    {
        $books = $bookRepository->findAll();
        return $this->render('book/books.html.twig', ['books' => $books]);

    }

    /**
     * @Route("/books/search", name="books_search")
     */
    public function getBooksByGenre(BookRepository $bookRepository)
    {
        // Appelle le bookRepository (en le passant en parametre de la méthode)
        // Appelle la méthode qu'on a créer dans le bookRepository ("getByGenre()")
        // Cette méthode est censée nous retourner tous les livres en fonction d'un genre
        // Elle va donc executer une requete SELECT en base de données
        $books = $bookRepository->getByGenre();
        dump($books);die;

    }
    // Inserer un livre en BDD
    /**
     * @Route("/book/insert", name="book_insert")
     */
    public function insertBook(EntityManagerInterface $entityManager)
    {
        // Insérer dans la table book un nouveau livre
        // Book = entité
        // new book = instance de la classe Book
        // j'instancie l'entité book afin de créer des données dans chaque colonne
        // de ma table en utilisant les setters de chaque colonne

        $book = new Book();
        $book->setTitle("Le temps d'un automne");
        $book->setStyle('Drame');
        $book->setInstock(true);
        $book->setNbPage(355);

        $entityManager->persist($book);
        $entityManager->flush();

        return $this->render('book/insert.html.twig', ['book' => $book]);
    }

    // Supprimer un book en BDD

    /**
     * @Route("/book/delete{id}", name="book_delete")
     */
    public function deleteBook(BookRepository $bookRepository, EntityManagerInterface $entityManager, $id)
    {
            $book = $bookRepository->find($id);
            $entityManager->remove($book);
            $entityManager->flush();

        return $this->render('book/deleteBook.html.twig', ['book' => $book]);
    }


    // Créer un nouveau livre avec un formulaire
    /**
     * @Route("/book/insert_form", name="book_insert_form")
     */
    public function insertBookForm(Request $request, EntityManagerInterface $entityManager)
    {
        // J'utilise le gabarit de formulaire pour créer mon formulaire
        // j'envoie mon formulaire à un fichier twig
        // et je l'affiche
        // je crée un nouveau Book,
        // en créant une nouvelle instance de l'entité Book
        $book = new Book();
        // J'utilise la méthode createForm pour créer le gabarit / le constructeur de
        // formulaire pour le Book : BookType (que j'ai généré en ligne de commandes)
        // Et je lui associe mon entité Book vide
        $bookForm = $this->createForm(BookType::class, $book);
        // Si je suis sur une méthode POST
        // donc qu'un formulaire a été envoyé
        if ($request->isMethod('Post')) {
            // Je récupère les données de la requête (POST)
            // et je les associe à mon formulaire
            $bookForm->handleRequest($request);
            // Si les données de mon formulaire sont valides
            // (que les types rentrés dans les inputs sont bons,
            // que tous les champs obligatoires sont remplis etc)
            if ($bookForm->isValid()) {
                // J'enregistre en BDD ma variable $book
                // qui n'est plus vide, car elle a été remplie
                // avec les données du formulaire
                $entityManager->persist($book);
                $entityManager->flush();
                return $this->redirectToRoute('book_insert');
            }
        }
        // à partir de mon gabarit, je crée la vue de mon formulaire
        $bookFormView = $bookForm->createView();
        // je retourne un fichier twig, et je lui envoie ma variable qui contient
        // mon formulaire
        return $this->render('book/insert_form.html.twig', [
            'bookFormView' => $bookFormView
        ]);
    }

    //modifier les données en BDD avec le formulaire

    /**
     * @Route("/book/update_form{id}", name="book_update_form")
     */
    public function updateBookForm(BookRepository $bookRepository, Request $request, EntityManagerInterface $entityManager, $id)
    {
        $book = $bookRepository->find($id);
        $bookForm = $this->createForm(BookType::class, $book);
        if ($request->isMethod('Post'))
        {
            $bookForm->handleRequest($request);
            if ($bookForm->isValid()) {


                $entityManager->persist($book);
                $entityManager->flush();
                return $this->redirectToRoute('book_update',['id'=> $id]);
            }
        }
        // à partir de mon gabarit, je crée la vue de mon formulaire
        $bookFormView = $bookForm->createView();
        // je retourne un fichier twig, et je lui envoie ma variable qui contient
        // mon formulaire
        return $this->render('book/update_form.html.twig', [
            'bookFormView' => $bookFormView
        ]);
    }
    //Mettre à jour un book en BDD

    /**
     * @Route("/book/update{id}", name="book_update")
     */
    public function updateBook(BookRepository $bookRepository, EntityManagerInterface $entityManager, $id)
    {
        //J'utilise le repository de l'entité Book pour récupérer un livre en fonction de son id
        $book = $bookRepository->find($id);

        //Je donne un nouveau titre à mon entité Book

        $book->setTitle("nouveau");
        $book->setStyle("drame");

        // Je re-enregistre mon livre en BDD avec l'entité manager
        $entityManager->persist($book);
        $entityManager->flush();

        return $this->render('book/updateBook.html.twig', ['book' => $book]);
    }
    // Selectionner un livre en BDD
    /**
     * @Route ("/book/{id}", name="book")
     */

    //méthode qui permet de faire un select en BDD de l'ensemble de mes champs dans ma table Book
    public function book(BookRepository $bookRepository, $id)
    {
        // j'utilise le repository de author afin de pouvoir selectionner tous les élèments de ma table
        //Les repository en général servent à faire les requêtes select dans les tables de ma BDD
        $book = $bookRepository->find($id);

        // utilisation de la méthode render pour appeler un fichier Twig en lui envoyant des variables
        return $this->render('book/book.html.twig', ['book' => $book]);
    }


}



