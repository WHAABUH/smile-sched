<?php
session_start(); // Start the session to store user login status

// Database connection
$servername = "localhost";
$username = "root";
$password = ""; // Default password for XAMPP
$database = "smile-sched-db";

$conn = new mysql($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Process login if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the email and password safely
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        echo '<script>alert("Please fill in all fields."); window.location.href = "login.php";</script>';
        exit();
    }

    // Prepare a statement to find the user by email
    $stmt = $conn->prepare("SELECT id, fullname, password FROM user_info WHERE email = ?");
    if ($stmt === false) {
        die("Failed to prepare statement: " . $conn->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the user exists
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Set session variables for successful login
            $_SESSION['loggedin'] = true;
            $_SESSION['fullname'] = $user['fullname'];
            $_SESSION['user_id'] = $user['id'];

            // Redirect to the home page
            header("Location: http://localhost/smile-sched/home.php");
            exit();
        } else {
            // Incorrect password
            echo '<script>alert("Incorrect password."); window.location.href = "login.php";</script>';
            exit();
        }
    } else {
        // User not found
        echo '<script>alert("No user found with this email. Please sign up."); window.location.href = "login.php";</script>';
        exit();
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
} else {
    // Redirect if accessed without POST request
    header("Location: http://localhost/smile-sched/login.php");
    exit();
}
?>
