<?php
require_once __DIR__ . '/../config/db.php';
class User {
    private $conn;
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    public function findByEmail($email) {
        $stmt = $this->conn->prepare('SELECT * FROM Users WHERE Email = ? LIMIT 1');
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function create($data) {
        $sql = 'INSERT INTO Users (First_Name, Last_Name, Role, Email, Password, Contact_Number) VALUES (?, ?, ?, ?, ?, ?)';
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $data['first_name'],
            $data['last_name'],
            $data['role'],
            $data['email'],
            $data['password'],
            $data['contact_number']
        ]);
    }
    public function verifyPassword($email, $password) {
        $user = $this->findByEmail($email);
        if (!$user) return false;
        // If password is hashed
        if (password_verify($password, $user['Password'])) {
            return $user;
        }
        // Fallback: if database has plain text password (migration case)
        if ($password === $user['Password']) {
            // Re-hash and update stored password
            $newHash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $this->conn->prepare('UPDATE Users SET Password = ? WHERE User_ID = ?');
            $stmt->execute([$newHash, $user['User_ID']]);
            $user['Password'] = $newHash;
            return $user;
        }
        return false;
    }
    public function updatePassword($userId, $newHashedPassword) {
        $stmt = $this->conn->prepare('UPDATE Users SET Password = ? WHERE User_ID = ?');
        return $stmt->execute([$newHashedPassword, $userId]);
    }
    public function getById($id) {
        $stmt = $this->conn->prepare('SELECT * FROM Users WHERE User_ID = ?');
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
