<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    die(json_encode(["status" => "error", "message" => "User not logged in."]));
}

require_once 'db_connection.php'; // Include your database connection

$email = $_SESSION['email'];

// Step 1: Fetch user details
$query = "SELECT user_id, fullname, age, sex FROM `user_info` WHERE email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die(json_encode(["status" => "error", "message" => "User not found."]));
}

$user = $result->fetch_assoc();
$user_id = $user['user_id'];
$fullname = $user['fullname'];
$age = $user['age'];
$sex = $user['sex'];

// Step 2: Get inputs from the POST request
$service = $_POST['service'] ?? '';
$amount = $_POST['amount'] ?? '';
$date = $_POST['date'] ?? '';
$time = $_POST['time'] ?? '';
$dentist = $_POST['dentist'] ?? '';

// Step 3: Validate inputs
if (empty($service) || empty($amount) || empty($date) || empty($time) || empty($dentist)) {
    die(json_encode(["status" => "error", "message" => "All fields are required."]));
}

// Step 4: Check if the appointment already exists
$query = "SELECT * FROM `appointments` WHERE `date` = ? AND `time` = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $date, $time);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    die(json_encode(["status" => "error", "message" => "This date and time are already booked."]));
}

// Step 5: Insert the new appointment
$query = "INSERT INTO `appointments` (`user_id`, `fullname`, `age`, `sex`, `service`, `amount`, `date`, `time`, `dentist`) 
          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("isissssss", $user_id, $fullname, $age, $sex, $service, $amount, $date, $time, $dentist);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Appointment successfully added."]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to add appointment."]);
}

$stmt->close();
$conn->close();
?>
