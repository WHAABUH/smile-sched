<?php
// Assuming database connection is the same as before
$servername = "localhost";
$username = "root";
$password = "";
$database = "smile-sched-db";

$connection = new mysqli($servername, $username, $password, $database);

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['appointment_id'])) {
    $appointment_id = htmlspecialchars($_POST['appointment_id']);

    // Update the status of the appointment to "Missed"
    $sql = "UPDATE appointments SET status = 'Missed' WHERE appointment_id = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $appointment_id);

    if ($stmt->execute()) {
        echo "Status updated successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$connection->close();
?>
