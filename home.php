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
    <link rel="stylesheet" href="./CSS/home.css"/>
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

        <div class="left-side">

            <div class="heading">
             <h2>Dentist Appointments Made Easier</h2>
            </div>
            
            <div class="paragraph">
                <p style="text-align: justify;">Book an appointment with our top-notch dentists today! With the convenience of our easy-to-use website, online scheduling has never been simpler. Experience hassle-free access to quality dental care at your fingertips.</p>
            </div>
            
            <div class="start-container">
                <a href="pending.php">
                    <button type="button" class="start-button">Set Appointment</button>
                </a>
            </div>
            
        </div>

        <div class="right-side">
            <img src="./Images/homeImage.png" alt="home-image">
        </div>

    </div>
    
   


</body>
</html>
