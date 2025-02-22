<?php
session_start();
include "db.php";

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "Login required!"]);
    exit();
}

$user_id = $_SESSION['user_id'];
$event_id = $_POST['event_id'];

// Check if the user has already booked this event
$stmt = $conn->prepare("SELECT id FROM bookings WHERE user_id = ? AND event_id = ?");
$stmt->bind_param("ii", $user_id, $event_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo json_encode(["status" => "error", "message" => "You have already booked this event!"]);
    exit();
}

// Check if the event is fully booked
$stmt = $conn->prepare("SELECT available_seats FROM events WHERE id = ?");
$stmt->bind_param("i", $event_id);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($available_seats);
$stmt->fetch();

if ($available_seats <= 0) {
    echo json_encode(["status" => "error", "message" => "Event is fully booked!"]);
    exit();
}

// Insert booking into the database
$stmt = $conn->prepare("INSERT INTO bookings (user_id, event_id) VALUES (?, ?)");
$stmt->bind_param("ii", $user_id, $event_id);

if ($stmt->execute()) {
    $conn->query("UPDATE events SET available_seats = available_seats - 1 WHERE id = $event_id");
    echo json_encode(["status" => "success", "message" => "Booking successful!", "remaining_seats" => $available_seats - 1]);
} else {
    echo json_encode(["status" => "error", "message" => "Error booking event!"]);
}
?>
