<?php
require_once __DIR__ . '/../model/Book.php';
class BookController {
    public function getAllBooks() {
        $bookModel = new Book();
        return $bookModel->getAll();
    }
    public function addBook($data) {
        $bookModel = new Book();
        return $bookModel->add($data);
    }
    public function updateBook($id, $data) {
        $bookModel = new Book();
        return $bookModel->update($id, $data);
    }
    public function archiveBook($id) {
        $bookModel = new Book();
        return $bookModel->archive($id);
    }

    public function getArchivedBooks() {
        $bookModel = new Book();
        return $bookModel->getArchivedBooks();
    }

    public function restoreBook($id) {
        $bookModel = new Book();
        return $bookModel->restore($id);
    }
}
