<?php 
// Database connection
$servername = "localhost"; // Use localhost for local servers
$username = "root";        // Default username for local MySQL servers
$password = "";            // Default password (blank for XAMPP)
$database = "smile-sched-db";     // Name of the database containing your `user_info` table

// Connect to MySQL database
$conn = new mysql($servername, $username, $password, $database);

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user inputs and sanitize them
    $fullname = trim($_POST['fullname']);
    $age = intval($_POST['age']);
    $sex = $_POST['sex'];
    $email = trim($_POST['email']);
    $password = trim($_POST['password']); 

    // Hash the password using password_hash()
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Prepare and bind the SQL query
    $stmt = $conn->prepare("INSERT INTO user_info (fullname, age, sex, email, password) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sisss", $fullname, $age, $sex, $email, $hashedPassword);

    // Execute the query and check for success
    if ($stmt->execute()) {
        echo '<script>alert("Sign-up successful! Welcome, ' . htmlspecialchars($fullname) . '."); window.location.href = "http://localhost/smile-sched/login.php";</script>';
    } else {
        // Check for duplicate email error
        if ($conn->errno == 1062) {
            echo '<script>alert("The email address is already registered. Please use a different email."); window.location.href = "http://localhost/smile-sched/signup.php";</script>';
        } else {
            echo '<script>alert("Error: ' . htmlspecialchars($stmt->error) . '"); window.location.href = "http://localhost/smile-sched/signup.php";</script>';
        }
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>
