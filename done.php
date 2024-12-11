<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Redirect to login page if not logged in
    header("Location: http://localhost/smile-sched/login.php");
    exit();
}

// Greet the user
$fullname = htmlspecialchars($_SESSION['fullname']); // Escape to prevent XSS
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./CSS/done.css"/>
    <title>Home</title>
</head>
<body>

    <div class="nav-bar">

        <div class="logo-title">
            <img src="./Images/logo.png" alt="logo">
            <h1>Smile-Sched</h1>
        </div>

        <div class="links">
            <a href="home.php" id="home">Home</a>
            <a href="services.php" id="services">Services</a>
            <a href="./pending.php" id="reservation">Appointment</a>
            <a href="./add.php" id="add" style="text-decoration: none;
            cursor: pointer;">Add</a>
        </div>

        <div class="logout">
            <form action="logout.php" method="post">
                <button type="submit" class="logout-button">Logout</button>
            </form>
        </div>
    </div>

    <div class="main-container">

        
            <div class="choices">
                <a href="pending.php">Pending</a>
                <a href="done.php">Done</a>
                <a href="missed.php">Missed</a>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Service</th>
                        <th>Price</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $servername = "localhost";
                        $username = "root";
                        $password = "";
                        $database = "smile-sched-db";
                        $patient_email = $_SESSION['email'];

                        //create a connection to the database
                        $connection = new mysqli($servername, $username, $password, $database);

                        //function to delete data when user want to cancel appointment
                        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cancel_appointment_id'])) {
                            $appointment_id = $_POST['cancel_appointment_id'];
                    
                            // SQL to delete the appointment (modify as needed)
                            $delete_sql = "DELETE FROM appointments WHERE appointment_id = ?";
                            $stmt = $connection->prepare($delete_sql);
                            $stmt->bind_param('i', $appointment_id);
                    
                            if ($stmt->execute()) {
                                
                            } else {
                                
                            }
                        }

                        if($connection->connect_error){
                            die("Connection with database failed: " . $connection->connect_error);
                        }

                        $sql = "SELECT * FROM appointments WHERE patient_email = '$patient_email' AND status = 'Done' ORDER BY start_time ASC";
                        $result = $connection->query($sql);

                        if(!$result){
                            die("SQL Error: " . $connection->connect_error);
                        }

                        while($row = $result->fetch_assoc()){
                            // Convert start_time to a 12-hour format and calculate the end time
                            $start_time = new DateTime($row['start_time']);
                            $end_time = clone $start_time; // Clone to calculate the end time
                            $end_time->modify('+1 hour'); // Add 1 hour

                            // Format start and end times to 12-hour format with AM/PM
                            $formatted_start_time = $start_time->format('g:i A');
                            $formatted_end_time = $end_time->format('g:i A');

                            echo "
                            <tr>
                                <td>{$row['service']}</td>
                                <td>{$row['amount']}</td>
                                <td>{$row['date']}</td>
                                <td>{$formatted_start_time} - {$formatted_end_time}</td>
                                <td>{$row['status']}</td>
                            </tr>
                            ";
                        }
                    ?>

                </tbody>
            </table>

    </div>

</body>
</html>