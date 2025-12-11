<?php
require_once __DIR__ . '/../config/db.php';
class Semester {
    private $conn;
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    public function getCurrentSemester() {
        // The database uses a `Status` column (e.g. 'Active' or 'Completed').
        // Return the most recently started active semester if present.
        $stmt = $this->conn->prepare("SELECT * FROM Semester WHERE Status = 'Active' ORDER BY Start_Date DESC LIMIT 1");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getById($id) {
        $stmt = $this->conn->prepare('SELECT * FROM Semester WHERE Semester_ID = ?');
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
