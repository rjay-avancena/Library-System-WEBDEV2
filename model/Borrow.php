<?php
require_once __DIR__ . '/../config/db.php';
class Borrow {
    private $conn;
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    public function borrowBook($userId, $bookId, $semesterId, $dueDate, $processedBy) {
        // Begin transaction: insert borrow and decrement book copies
        try {
            // Use SELECT ... FOR UPDATE to check availability and avoid double-decrement
            $this->conn->beginTransaction();
            $check = $this->conn->prepare('SELECT Copies_Available FROM Book WHERE Book_ID = ? FOR UPDATE');
            $check->execute([$bookId]);
            $row = $check->fetch(PDO::FETCH_ASSOC);
            if (!$row || (int)$row['Copies_Available'] <= 0) {
                $this->conn->rollBack();
                return false;
            }
            // Insert borrow record; a DB trigger will decrement Copies_Available after insert
            $sql = 'INSERT INTO Borrow (User_ID, Book_ID, Semester_ID, Borrow_Date, Due_Date, Status, Processed_By) VALUES (?, ?, ?, CURDATE(), ?, "Borrowed", ?)';
            $stmt = $this->conn->prepare($sql);
            $ok = $stmt->execute([$userId, $bookId, $semesterId, $dueDate, $processedBy]);
            if (!$ok) {
                $this->conn->rollBack();
                return false;
            }
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }
    public function returnBook($borrowId, $lateDays = 0) {
        try {
            $this->conn->beginTransaction();
            // Update borrow record (do not assume Late_Days column exists in DB schema)
            $sql = 'UPDATE Borrow SET Return_Date = CURDATE(), Status = "Returned" WHERE Borrow_ID = ?';
            $stmt = $this->conn->prepare($sql);
            $ok = $stmt->execute([$borrowId]);
            if (!$ok) {
                $this->conn->rollBack();
                return false;
            }
            // Increment book copies
            $stmt2 = $this->conn->prepare('SELECT Book_ID FROM Borrow WHERE Borrow_ID = ?');
            $stmt2->execute([$borrowId]);
            $row = $stmt2->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                $bookId = $row['Book_ID'];
                $stmt3 = $this->conn->prepare('UPDATE Book SET Copies_Available = Copies_Available + 1 WHERE Book_ID = ?');
                $stmt3->execute([$bookId]);
            }
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }
    public function getUserBorrows($userId, $semesterId) {
        $stmt = $this->conn->prepare('SELECT * FROM Borrow WHERE User_ID = ? AND Semester_ID = ? AND Status = "Borrowed"');
        $stmt->execute([$userId, $semesterId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getBorrowedByUser($userId) {
        $stmt = $this->conn->prepare('SELECT b.*, bk.Title FROM Borrow b JOIN Book bk ON b.Book_ID = bk.Book_ID WHERE b.User_ID = ? AND b.Status = "Borrowed"');
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getById($borrowId) {
        $stmt = $this->conn->prepare('SELECT * FROM Borrow WHERE Borrow_ID = ?');
        $stmt->execute([$borrowId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
