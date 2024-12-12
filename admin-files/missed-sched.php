<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['admin'] !== true) {
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
    <link rel="stylesheet" href="../CSS/missed-sched.css">
    <title>Schedule</title>
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
            <a href="missed-sched.php" id="missed">Missed</a>
            <a href="accounts.php" id="accounts">Accounts</a>
            <a href="recentpayment.php" id="payments">Payments</a>
        </div>

        <div class="logout">
            <form action="../logout.php" method="post">
                <button type="submit" class="logout-button">Logout</button>
            </form>
        </div>

    </div>


    <div class="mainContainer">

<div class="infoContainer">

    <div class="titleContainer">
        <h1>Entire Schedule</h1>
    </div>

    <div class="tableHolder">
        <table>
            <thead>
                <th class="noright">Patient</th>
                <th class="noall">Service</th>
                <th class="noall">Balance</th>
                <th class="noall">Date</th>
                <th class="noall">Time</th>
                <th class="noleft">Status</th>
            </thead>
            <tbody>
                <?php
                 
                 $servername = "localhost";
                 $username = "root";
                 $password = "";
                 $database = "smile-sched-db";

                 $connection = new mysqli($servername, $username, $password, $database);

                 $sql = "SELECT * 
                    FROM appointments WHERE status = 'Missed' ORDER BY date ASC, start_time ASC";
                 
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


                    echo "
                        <tr>
                            <td>$row[patient]</td>
                            <td>$row[service]</td>
                            <td>$row[amount]</td>
                            <td>$row[date]</td>
                            <td>{$start_time_str} - {$end_time_str}</td>
                            <td>$row[status]</td>
                        </tr>
                    ";
                 }

                ?>
                
            </tbody>
        </table>
    </div>

</div>

</div>

</body>
</html>