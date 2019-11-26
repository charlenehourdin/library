<?php


namespace App\Controller;

use App\Entity\Book;

use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;



class BookController extends AbstractController
{
    /**
     * @Route ("/books", name="books")
     */

    public function BooksList(BookRepository $bookRepository)
    {
        $books = $bookRepository->findAll();
        return $this->render('books.html.twig', ['books' => $books]);

    }
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
        return $this->render('book.html.twig', ['book' => $book]);
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
    /**
     * @Route("/books/insert", name="books_insert")
     */
    public function insertBook(EntityManagerInterface $entityManager)
    {
        // Insérer dans la table book un nouveau livre
        $book = new Book();
        $book->setTitle('titre');
        $book->setStyle('Escroquerie');
        $book->setInstock(true);
        $book->setNbPage(288);

        $entityManager->persist($book);
        $entityManager->flush();

        return $this->render('insert.html.twig', ['book' => $book]);
    }
}


