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
    <link rel="stylesheet" href="./CSS/pending.css"/>
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
        </div>

        <div class="logout">
            <form action="logout.php" method="post">
                <button type="submit" class="logout-button">Logout</button>
            </form>
        </div>
    </div>

    <div class="main-container">

        <div class="tab-container">

            <div class="pending-container"><h1>Pending</h1></div>
            <div class="done-container"><h1>Done</h1></div>
            <div class="missed-container"><h1>Missed</h1></div>
            <div class="add-container"><h1>Add</h1></div>

        </div>

        <div class="table-container">

            <div class="heading-container">

                <div class="service"><h1>Service</h1></div>
                <div class="price"><h1>Price</h1></div>
                <div class="date"><h1>Date</h1></div>
                <div class="time"><h1>Time</h1></div>
                <div class="status"><h1>Status</h1></div>

            </div>

            <div class="data-container">

                <div class="data">

                    <div class="service-value"><h1>Cleaning</h1></div>
                    <div class="price-value"><h1>₱ 1500</h1></div>
                    <div class="date-value"><h1>12/25/2024</h1></div>
                    <div class="time-value"><h1>11AM - 12PM</h1></div>
                    <div class="status-value">

                        <div class="status-container">
                            <h1 id="pending">Pending</h1>
                        </div>

                    </div>

                </div>

                <div class="data">

                    <div class="service-value"><h1>Cleaning</h1></div>
                    <div class="price-value"><h1>₱ 1500</h1></div>
                    <div class="date-value"><h1>12/25/2024</h1></div>
                    <div class="time-value"><h1>11AM - 12PM</h1></div>
                    <div class="status-value">

                        <div class="status-container">
                            <h1 id="pending">Pending</h1>
                        </div>

                    </div>

                </div>

                <div class="data">

                    <div class="service-value"><h1>Cleaning</h1></div>
                    <div class="price-value"><h1>₱ 1500</h1></div>
                    <div class="date-value"><h1>12/25/2024</h1></div>
                    <div class="time-value"><h1>11AM - 12PM</h1></div>
                    <div class="status-value">

                        <div class="status-container">
                            <h1 id="pending">Pending</h1>
                        </div>

                    </div>

                </div>

                <div class="data">

                    <div class="service-value"><h1>Cleaning</h1></div>
                    <div class="price-value"><h1>₱ 1500</h1></div>
                    <div class="date-value"><h1>12/25/2024</h1></div>
                    <div class="time-value"><h1>11AM - 12PM</h1></div>
                    <div class="status-value">

                        <div class="status-container">
                            <h1 id="pending">Pending</h1>
                        </div>

                    </div>

                </div>


            </div>

        </div>

    </div>

</body>
</html>