<?php
// booking_handler.php
session_start();
include '../filter_wisata/db_connect.php';
class BookingHandler {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    public function createBooking($booking_data) {
        try {
            $this->db->begin_transaction();
            
            // Validasi data booking
            $this->validateBookingData($booking_data);
            
            // Insert ke tabel bookings
            $sql = "INSERT INTO bookings (
                booking_type, reference_id, title, full_name, email, phone,
                num_adults, num_children, payment_method, total_price, status,
                created_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', NOW())";
            
            $stmt = $this->db->prepare($sql);
            if (!$stmt) {
                throw new Exception("Prepare failed: " . $this->db->error);
            }
            
            $stmt->bind_param(
                "sissssiisd",
                $booking_data['booking_type'],
                $booking_data['reference_id'],
                $booking_data['title'],
                $booking_data['full_name'],
                $booking_data['email'],
                $booking_data['phone'],
                $booking_data['num_adults'],
                $booking_data['num_children'],
                $booking_data['payment_method'],
                $booking_data['total_price']
            );
            
            if (!$stmt->execute()) {
                throw new Exception("Execute failed: " . $stmt->error);
            }
            
            $booking_id = $this->db->insert_id;
            
            // Insert booking details berdasarkan tipe
            if ($booking_data['booking_type'] === 'hotel') {
                $this->insertHotelBooking($booking_id, $booking_data);
            }
            
            $this->db->commit();
            
            $_SESSION['booking_success'] = true;
            $_SESSION['booking_id'] = $booking_id;
            $_SESSION['success_message'] = "Booking berhasil dengan nomor pemesanan: " . $booking_id;
            
            return [
                'success' => true,
                'booking_id' => $booking_id,
                'message' => 'Booking berhasil dibuat!'
            ];
            
        } catch (Exception $e) {
            $this->db->rollback();
            error_log("Booking error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error membuat booking: ' . $e->getMessage()
            ];
        }
    }
    
    private function validateBookingData($data) {
        $required_fields = [
            'booking_type', 'reference_id', 'title', 'full_name',
            'email', 'phone', 'num_adults', 'payment_method'
        ];
        
        foreach ($required_fields as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                throw new Exception("Field '$field' is required");
            }
        }
        
        // Validasi tambahan untuk hotel booking
        if ($data['booking_type'] === 'hotel') {
            if (!isset($data['check_in_date']) || !isset($data['check_out_date'])) {
                throw new Exception("Check-in and check-out dates are required for hotel bookings");
            }
        }
    }
    
    private function insertHotelBooking($booking_id, $data) {
        // Pastikan room masih tersedia
        $check_room = "SELECT availability FROM rooms WHERE room_id = ? AND availability > 0";
        $stmt = $this->db->prepare($check_room);
        $stmt->bind_param("i", $data['room_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            throw new Exception("Room is not available");
        }
        
        // Insert ke hotel_bookings
        $sql = "INSERT INTO hotel_bookings (
            booking_id, hotel_id, room_id, check_in_date, check_out_date
        ) VALUES (?, ?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            throw new Exception("Failed to prepare hotel booking statement: " . $this->db->error);
        }
        
        $stmt->bind_param(
            "iiiss",
            $booking_id,
            $data['hotel_id'],
            $data['room_id'],
            $data['check_in_date'],
            $data['check_out_date']
        );
        
        if (!$stmt->execute()) {
            throw new Exception("Failed to insert hotel booking: " . $stmt->error);
        }
        
        // Update room availability
        $update_room = "UPDATE rooms SET availability = availability - 1 WHERE room_id = ?";
        $stmt = $this->db->prepare($update_room);
        $stmt->bind_param("i", $data['room_id']);
        $stmt->execute();
    }
}