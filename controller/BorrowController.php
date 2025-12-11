<?php
require_once __DIR__ . '/../model/Borrow.php';
require_once __DIR__ . '/../model/Semester.php';
require_once __DIR__ . '/../model/User.php';
require_once __DIR__ . '/../model/Penalty.php';
class BorrowController {
    public function canUserBorrow($userId) {
        $userModel = new User();
        $user = $userModel->getById($userId);
        if (!$user) return [false, 'User not found'];
        $semesterModel = new Semester();
        $current = $semesterModel->getCurrentSemester();
        if (!$current) return [false, 'No active semester configured'];
        $borrowModel = new Borrow();
        $currentBorrows = $borrowModel->getUserBorrows($userId, $current['Semester_ID']);
        $count = count($currentBorrows);
        if ($user['Role'] === 'Teacher') {
            return [true, ''];
        }
        // Student or others: check student limit
        $limit = (int)$current['Student_Borrow_Limit'];
        if ($count >= $limit) {
            return [false, 'Borrow limit reached ('.$limit.')'];
        }
        return [true, ''];
    }
    public function borrowBook($userId, $bookId, $semesterId = null, $dueDate, $processedBy) {
        $semesterModel = new Semester();
        if (!$semesterId) {
            $current = $semesterModel->getCurrentSemester();
            if (!$current) return false;
            $semesterId = $current['Semester_ID'];
        }
        $can = $this->canUserBorrow($userId);
        if (!$can[0]) {
            return ['success' => false, 'message' => $can[1]];
        }
        $borrowModel = new Borrow();
        $ok = $borrowModel->borrowBook($userId, $bookId, $semesterId, $dueDate, $processedBy);
        if ($ok) return ['success' => true];
        return ['success' => false, 'message' => 'Unable to borrow (maybe no copies available)'];
    }
    public function returnBook($borrowId, $processedBy = null) {
        $borrowModel = new Borrow();
        $borrow = $borrowModel->getById($borrowId);
        if (!$borrow) return ['success' => false, 'message' => 'Borrow record not found'];
        $due = $borrow['Due_Date'];
        $dueDate = new DateTime($due);
        $now = new DateTime();
        $lateDays = 0;
        if ($now > $dueDate) {
            $lateDays = (int)$now->diff($dueDate)->format('%a');
        }
        $ok = $borrowModel->returnBook($borrowId, $lateDays);
        if (!$ok) return ['success' => false, 'message' => 'Failed to process return'];
        // if late, create penalty (P10/day)
        if ($lateDays > 0) {
            $penaltyModel = new Penalty();
            $amount = $lateDays * 10.00;
            $desc = $lateDays . ' days overdue at P10/day';
            $penaltyModel->addPenalty($borrowId, $borrow['User_ID'], $amount, 'Late', $desc);
        }
        return ['success' => true, 'late_days' => $lateDays];
    }
    public function getUserBorrows($userId, $semesterId) {
        $borrowModel = new Borrow();
        return $borrowModel->getUserBorrows($userId, $semesterId);
    }
    public function getBorrowedByUser($userId) {
        $borrowModel = new Borrow();
        return $borrowModel->getBorrowedByUser($userId);
    }
}
