<?php
session_start(); // Start the session to store user login status

// Database connection
$servername = "localhost";
$username = "root";
$password = ""; // Default password for XAMPP
$database = "smile-sched-db";

// Connect to MySQL
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the email and password from the form
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Query to find the user by email
    $stmt = $conn->prepare("SELECT id, fullname, password FROM user_info WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the user exists
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Login successful
            $_SESSION['loggedin'] = true;
            $_SESSION['fullname'] = $user['fullname'];
            $_SESSION['user_id'] = $user['id'];

            // Redirect to home page or dashboard
            header("Location: http://localhost/smile-sched/home.php");
            exit();
        } else {
            // Incorrect password
            header("Location: http://localhost/smile-sched/login.php");
        }
    } else {
        // User not found
        echo '<script>alert("No user found, please sign up."); window.location.href = "http://localhost/smile-sched/login.php";</script>';
        exit();
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>