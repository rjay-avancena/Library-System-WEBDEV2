<?php
require_once __DIR__ . '/../config/db.php';
class Book {
    private $conn;
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    public function getAll() {
        $stmt = $this->conn->query('SELECT * FROM Book WHERE Status = "Available"');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getById($id) {
        $stmt = $this->conn->prepare('SELECT * FROM Book WHERE Book_ID = ?');
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function add($data) {
        $sql = 'INSERT INTO Book (Title, Author, ISBN, Category_ID, Publisher, Book_Price, Total_Copies, Copies_Available, Status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)';
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $data['title'], $data['author'], $data['isbn'], $data['category_id'], $data['publisher'], $data['book_price'], $data['total_copies'], $data['copies_available'], $data['status']
        ]);
    }
    public function update($id, $data) {
        $sql = 'UPDATE Book SET Title=?, Author=?, ISBN=?, Category_ID=?, Publisher=?, Book_Price=?, Total_Copies=?, Copies_Available=?, Status=? WHERE Book_ID=?';
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $data['title'], $data['author'], $data['isbn'], $data['category_id'], $data['publisher'], $data['book_price'], $data['total_copies'], $data['copies_available'], $data['status'], $id
        ]);
    }
    public function archive($id) {
        $stmt = $this->conn->prepare('UPDATE Book SET Status = "Archived" WHERE Book_ID = ?');
        return $stmt->execute([$id]);
    }
    public function decrementCopies($id, $count = 1) {
        $stmt = $this->conn->prepare('UPDATE Book SET Copies_Available = Copies_Available - ? WHERE Book_ID = ? AND Copies_Available >= ?');
        return $stmt->execute([$count, $id, $count]);
    }
    public function incrementCopies($id, $count = 1) {
        $stmt = $this->conn->prepare('UPDATE Book SET Copies_Available = Copies_Available + ? WHERE Book_ID = ?');
        return $stmt->execute([$count, $id]);
    }
    public function getAvailableBooks() {
        $stmt = $this->conn->query('SELECT * FROM Book WHERE Status = "Available" AND Copies_Available > 0');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getArchivedBooks() {
        $stmt = $this->conn->query('SELECT * FROM Book WHERE Status = "Archived"');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function restore($id) {
        $stmt = $this->conn->prepare('UPDATE Book SET Status = "Available" WHERE Book_ID = ?');
        return $stmt->execute([$id]);
    }
}
