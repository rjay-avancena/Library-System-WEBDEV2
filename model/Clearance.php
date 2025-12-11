<?php
require_once __DIR__ . '/../config/db.php';
class Clearance {
    private $conn;
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    public function requestClearance($userId, $semesterId, $academicYear) {
        // New signature supports passing status, cleared_by, remarks
  
        $args = func_get_args();
        $status = isset($args[3]) ? $args[3] : 'Pending';
        $clearedBy = isset($args[4]) ? $args[4] : null;
        $remarks = isset($args[5]) ? $args[5] : '';

        $sql = 'INSERT INTO Clearance (User_ID, Semester_ID, Academic_Year, Library_Clearance, Clearance_Date, Cleared_By, Remarks) VALUES (?, ?, ?, ?, ';
        if ($status === 'Approved') {
            $sql .= 'CURDATE(), ?, ?)';
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$userId, $semesterId, $academicYear, $status, $clearedBy, $remarks]);
        } else {
            $sql .= 'NULL, NULL, ?)';
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$userId, $semesterId, $academicYear, $status, $remarks]);
        }
    }
    public function finalizeClearance($clearanceId, $status, $clearedBy = null, $remarks = '') {
        try {
            if ($status === 'Approved') {
                $sql = 'UPDATE Clearance SET Library_Clearance = ?, Clearance_Date = CURDATE(), Cleared_By = ?, Remarks = ? WHERE Clearance_ID = ?';
                $stmt = $this->conn->prepare($sql);
                return $stmt->execute([$status, $clearedBy, $remarks, $clearanceId]);
            } else {
                $sql = 'UPDATE Clearance SET Library_Clearance = ?, Clearance_Date = NULL, Cleared_By = NULL, Remarks = ? WHERE Clearance_ID = ?';
                $stmt = $this->conn->prepare($sql);
                return $stmt->execute([$status, $remarks, $clearanceId]);
            }
        } catch (Exception $e) {
            return false;
        }
    }
    public function getUserClearance($userId, $semesterId) {
        $stmt = $this->conn->prepare('SELECT * FROM Clearance WHERE User_ID = ? AND Semester_ID = ?');
        $stmt->execute([$userId, $semesterId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
