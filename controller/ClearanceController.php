<?php
require_once __DIR__ . '/../model/Clearance.php';
class ClearanceController {
    // Request clearance and auto-check for unreturned books / unpaid penalties.
    // If user has no borrowed books and no unpaid penalties -> auto-approve.
    // Otherwise record request as Pending with remarks.
    public function requestClearance($userId, $semesterId, $academicYear, $processedBy = null) {
        require_once __DIR__ . '/../model/Borrow.php';
        require_once __DIR__ . '/../model/Penalty.php';
        $borrowModel = new Borrow();
        $penaltyModel = new Penalty();

        // check active borrows
        $activeBorrows = $borrowModel->getBorrowedByUser($userId);
        $unpaidPenalties = $penaltyModel->getUserPenalties($userId);

        $clearanceModel = new Clearance();

        $remarks = '';
        $status = 'Pending';
        if (empty($activeBorrows) && empty($unpaidPenalties)) {
            $status = 'Approved';
            $remarks = 'Auto-approved';
        } else {
            $parts = [];
            if (!empty($activeBorrows)) $parts[] = 'Has unreturned books';
            if (!empty($unpaidPenalties)) $parts[] = 'Has unpaid penalties';
            $remarks = implode('; ', $parts);
            $status = 'Pending';
        }

        return $clearanceModel->requestClearance($userId, $semesterId, $academicYear, $status, $processedBy, $remarks);
    }

    public function getUserClearance($userId, $semesterId) {
        $clearanceModel = new Clearance();
        return $clearanceModel->getUserClearance($userId, $semesterId);
    }
    public function finalizeClearance($clearanceId, $status, $clearedBy, $remarks = '') {
        $clearanceModel = new Clearance();
        return $clearanceModel->finalizeClearance($clearanceId, $status, $clearedBy, $remarks);
    }
}
