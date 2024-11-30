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
    <title>Home</title>
</head>
<body>
    <h1>Smile-Sched</h1>
    <a href="home.php">Home</a>
    <a href="services.php">Services</a>
    <a href="slot.php">Reservation</a>
    <form action="logout.php" method="post">
        <button type="submit" class="logout-button">Logout</button>
    </form>

    <h2>Dentist Appointments Made Easier</h2>
    <p style="text-align: justify;">Reserve an appointment with out top-notch dentists. Online scheduling is now available thanks to this easy access website. 
       Reserve an appointment with out top-notch dentists. Online scheduling is now available thanks to this easy access website.</p>

    <form action="logout.php" method="post">
        <button type="submit" class="logout-button">Start now</button>
    </form>

</body>
</html>
