<?php
require_once __DIR__ . '/../config/db.php';
class Penalty {
    private $conn;
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    public function addPenalty($borrowId, $userId, $amount, $type, $desc) {
        $sql = 'INSERT INTO Penalty (Borrow_ID, User_ID, Amount, Penalty_Type, Description, Issued_Date, Status) VALUES (?, ?, ?, ?, ?, CURDATE(), "Unpaid")';
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$borrowId, $userId, $amount, $type, $desc]);
    }
    public function getUserPenalties($userId) {
        $stmt = $this->conn->prepare('SELECT * FROM Penalty WHERE User_ID = ? AND Status = "Unpaid"');
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function markPaid($penaltyId, $processedBy = null, $method = 'Cash') {
        try {
            $this->conn->beginTransaction();
            // fetch penalty
            $stmt = $this->conn->prepare('SELECT * FROM Penalty WHERE Penalty_ID = ? FOR UPDATE');
            $stmt->execute([$penaltyId]);
            $pen = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$pen) {
                $this->conn->rollBack();
                return false;
            }
            // update penalty status
            $upd = $this->conn->prepare('UPDATE Penalty SET Status = "Paid" WHERE Penalty_ID = ?');
            $ok = $upd->execute([$penaltyId]);
            if (!$ok) {
                $this->conn->rollBack();
                return false;
            }
            // insert payment record
            $ins = $this->conn->prepare('INSERT INTO Payment (User_ID, Penalty_ID, Amount, Method, Processed_By) VALUES (?, ?, ?, ?, ?)');
            $insOk = $ins->execute([$pen['User_ID'], $penaltyId, $pen['Amount'], $method, $processedBy]);
            if (!$insOk) {
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
}
