<?php
require_once __DIR__ . '/../model/Penalty.php';
class PenaltyController {
    public function addPenalty($borrowId, $userId, $amount, $type, $desc) {
        $penaltyModel = new Penalty();
        return $penaltyModel->addPenalty($borrowId, $userId, $amount, $type, $desc);
    }
    public function getUserPenalties($userId) {
        $penaltyModel = new Penalty();
        return $penaltyModel->getUserPenalties($userId);
    }

    public function markPenaltyPaid($penaltyId, $processedBy = null, $method = 'Cash') {
        $penaltyModel = new Penalty();
        return $penaltyModel->markPaid($penaltyId, $processedBy, $method);
    }
}
