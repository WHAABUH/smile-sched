<?php 
// Database connection
$servername = "localhost"; // Use localhost for local servers
$username = "root";        // Default username for local MySQL servers
$password = "";            // Default password (blank for XAMPP)
$database = "smile-sched-db"; // Name of the database containing your `user_info` table

// Connect to MySQL database
$conn = new mysqli($servername, $username, $password, $database);

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

    // Check if the email already exists in the database
    $emailCheckStmt = $conn->prepare("SELECT user_id FROM user_info WHERE email = ?");
    $emailCheckStmt->bind_param("s", $email);
    $emailCheckStmt->execute();
    $emailCheckStmt->store_result();

    if ($emailCheckStmt->num_rows > 0) {
        // Email already exists
        echo '<script>alert("The email address is already registered. Please use a different email."); window.location.href = "http://localhost/smile-sched/sign-up.php";</script>';
        $emailCheckStmt->close();
        $conn->close();
        exit;
    }

    $emailCheckStmt->close(); // Close the email check statement

    // Prepare and bind the SQL query
    try {
        $stmt = $conn->prepare("INSERT INTO user_info (fullname, age, sex, email, password, role) VALUES (?, ?, ?, ?, ?, 'patient')");
        $stmt->bind_param("sisss", $fullname, $age, $sex, $email, $hashedPassword);

        // Attempt to execute the query
        if ($stmt->execute()) {
            echo '<script>alert("Sign-up successful! Welcome, ' . htmlspecialchars($fullname) . '."); window.location.href = "http://localhost/smile-sched/login.php";</script>';
        }
    } catch (mysqli_sql_exception $e) {
        // Handle duplicate entry error
        if ($e->getCode() == 1062) {
            echo '<script>alert("The email address is already registered. Please use a different email."); window.location.href = "http://localhost/smile-sched/sign-up.php";</script>';
        } else {
            echo '<script>alert("Error: ' . htmlspecialchars($e->getMessage()) . '"); window.location.href = "http://localhost/smile-sched/sign-up.php";</script>';
        }
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>
