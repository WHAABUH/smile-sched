<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Redirect to login page if not logged in
    header("Location: http://localhost/smile-sched/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get user_id and action (ban/unban) from the AJAX request
    $user_id = isset($_POST['user_id']) ? htmlspecialchars($_POST['user_id']) : '';
    $action = isset($_POST['action']) ? htmlspecialchars($_POST['action']) : '';

    if (empty($user_id) || empty($action)) {
        echo json_encode(['error' => 'Missing user ID or action']);
        exit();
    }

    // Set the new role based on the action
    $new_role = ($action == 'ban') ? 'banned' : 'patient';

    // Update the user's role in the database
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "smile-sched-db";

    $connection = new mysqli($servername, $username, $password, $database);

    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }

    // Use prepared statement to prevent SQL injection
    $stmt = $connection->prepare("UPDATE user_info SET role = ? WHERE user_id = ?");
    $stmt->bind_param("si", $new_role, $user_id);

    if ($stmt->execute()) {
        // Send a response back with the new button text and role
        $newButtonText = ($new_role == 'banned') ? 'Unban User' : 'Ban User';
        echo json_encode(['newButtonText' => $newButtonText, 'newRole' => $new_role]);
    } else {
        echo json_encode(['error' => 'Failed to update role']);
    }

    $stmt->close();
    $connection->close();
}
?>
