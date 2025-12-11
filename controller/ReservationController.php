<?php
require_once __DIR__ . '/../model/Reservation.php';
class ReservationController {
    public function reserveBook($userId, $bookId, $expiryDate) {
        $reservationModel = new Reservation();
        return $reservationModel->reserveBook($userId, $bookId, $expiryDate);
    }
    public function getUserReservations($userId) {
        $reservationModel = new Reservation();
        return $reservationModel->getUserReservations($userId);
    }
}
