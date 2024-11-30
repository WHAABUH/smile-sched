<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Redirect to login page if not logged in
    header("Location: http://localhost/smile-sched/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body> 
    <h1>Good Day Doctor!</h1>
    <form action="logout.php" method="post">
        <button type="submit" class="logout-button">Logout</button>
    </form>

    <div class="schedule-container">
        <div class="patient">
            <p>Patient:</p>
        </div>
        <div class="schedule">
            <p>Schedule:</p>
        </div>
        <div class="age">
            <p>Age:</p>
        </div>
        <div class="sex">
            <p>Sex:</p>
        </div>
        <div class="success-button">
            <button class="success-button">Success</button>
        </div>
        <div class="missed-button">
            <button class="missed-button">Missed</button>
        </div>
    </div>
</body>
</html>