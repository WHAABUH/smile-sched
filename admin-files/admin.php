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
    <link rel="stylesheet" href="../CSS/admin-home.css"/>
    <title>Home</title>
</head>
<body>

    <div class="nav-bar">

        <div class="logo-title">
            <img src="../Images/logo.png" alt="logo">
            <h1>Smile-Sched</h1>
        </div>

        <div class="links">
            <a href="admin.php" id="home">Home</a>
            <a href="schedule.php" id="schedule">Schedule</a>
            <a href="accounts.php" id="accounts">Accounts</a>
            <a href="recentpayment.php" id="payments">Payments</a>
        </div>

        <div class="logout">
            <form action="../logout.php" method="post">
                <button type="submit" class="logout-button">Logout</button>
            </form>
        </div>

    </div>
    
    <div class="main-container">

        <div class="left-side">

            <?php

                $servername = "localhost";
                $username = "root";
                $password = "";
                $database = "smile-sched-db";

                $connection = new mysqli($servername, $username, $password, $database);

                $sql = "SELECT
                (SELECT COUNT(*) FROM user_info WHERE role = 'patient' AND role != 'banned') AS total_patients,
                (SELECT COUNT(*) FROM appointments WHERE status = 'Waiting') AS waiting_count,
                (SELECT COUNT(*) FROM appointments WHERE status = 'Paid') AS paid_count,
                (SELECT COUNT(*) FROM appointments WHERE status = 'Missed') AS missing_count";
                $result = $connection->query($sql);

                if(!$result){
                    die("SQL Error: " . $connection->connect_error);
                }

                while($row = $result->fetch_assoc()){
                    echo"
                    <div class='left-container'>
                
                        <div class='box'>
                            
                            <div class='image-container'>
                                <img src='../Images/patientIMG.png' alt=''>
                            </div>

                            <div class='text-container'>
                                <h1>$row[total_patients]</h1>
                                <p>Patients</p>
                            </div>

                        </div>

                        <div class='box'>
                            
                            <div class='image-container'>
                                <img src='../Images/doneIMG.png' alt=''>
                            </div>

                            <div class='text-container'>
                                <h1>$row[paid_count]</h1>
                                <p>Done</p>
                            </div>

                        </div>

                    </div>

                    <div class='right-container'>

                        <div class='box'>
                                
                                <div class='image-container'>
                                    <img src='../Images/pendingIMG.png' alt=''>
                                </div>

                                <div class='text-container'>
                                    <h1>$row[waiting_count]</h1>
                                    <p>Pending</p>
                                </div>

                            </div>

                            <div class='box'>
                                
                                <div class='image-container'>
                                    <img src='../Images/missedIMG.png' id='missedIMG'>
                                </div>

                                <div class='text-container'>
                                    <h1>$row[missing_count]</h1>
                                    <p>Missed</p>
                                </div>

                            </div>

                    </div>
                    ";
                }

            ?>


        </div>

        <div class='right-side'>

            <div class='dashContainer'>

                <div class='title'>
                    <h1>Today's Schedule</h1>
                </div>

                <div class='tableHolder'>
                    <table>
                        <thead>
                            <th id='noright'>Patient</th>
                            <th id='noall'>Service</th>
                            <th id='noleft'>Time</th>
                        </thead>
                        <tbody>
                           <?php 
                            
                            $servername = "localhost";
                            $username = "root";
                            $password = "";
                            $database = "smile-sched-db";

                            $connection = new mysqli($servername, $username, $password, $database);

                            $sql = "SELECT * 
                            FROM appointments
                            WHERE DATE(date) = CURDATE() AND status = 'Waiting' ORDER BY date ASC, start_time ASC";
                            
                            $result = $connection->query($sql);

                            if(!$result){
                                die("SQL Error: " . $connection->connect_error);
                            }

                            while($row = $result->fetch_assoc()){

                                $start_time = new DateTime($row['start_time']);
    
                                // Clone the start_time object and add 1 hour for the end time
                                $end_time = clone $start_time;
                                $end_time->modify('+1 hour');
                                
                                // Format the start and end times to 12-hour format with AM/PM
                                $start_time_str = $start_time->format('g:i A');
                                $end_time_str = $end_time->format('g:i A');

                                echo"
                                    <tr>
                                        <td>$row[patient]</td>
                                        <td>$row[service]</td>
                                        <td>{$start_time_str} - {$end_time_str}</td>
                                    </tr>
                                ";
                            }

                           ?>
                        </tbody>
                    </table>
                </div>

            </div>

        </div>

    </div>
    
    <script>
        window.addEventListener('wheel', function (e) {
            if (e.ctrlKey) {
                e.preventDefault();
            }
        }, { passive: false });
    </script>
   


</body>
</html>
