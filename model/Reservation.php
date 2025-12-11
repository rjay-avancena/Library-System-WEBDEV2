<?php
require_once __DIR__ . '/../config/db.php';
class Reservation {
    private $conn;
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    public function reserveBook($userId, $bookId, $expiryDate) {
        $sql = 'INSERT INTO Reservation (User_ID, Book_ID, Reservation_Date, Expiry_Date, Status) VALUES (?, ?, CURDATE(), ?, "Active")';
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$userId, $bookId, $expiryDate]);
    }
    public function getUserReservations($userId) {
        // Include book title for display
        $stmt = $this->conn->prepare('SELECT r.*, b.Title FROM Reservation r JOIN Book b ON r.Book_ID = b.Book_ID WHERE r.User_ID = ? AND r.Status = "Active"');
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
