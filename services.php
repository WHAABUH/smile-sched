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
    <link rel="stylesheet" href="./CSS/services.css"/>
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
            <a href="reservation.php" id="reservation">Reservation</a>
        </div>

        <div class="logout">
            <form action="logout.php" method="post">
                <button type="submit" class="logout-button">Logout</button>
            </form>
        </div>

    </div>

    <div class="main-container">

        <div class="first-container">
            <div class="service-container">
                <div class="image-container">
                    <img src="./Images/consultation.png" alt="consultation">
                </div>
                <div class="text-container">
                    <h1>Consultation</h1>
                    <p>Have our doctors check your gums to keep it healthier than ever.</p>
                    <h1 id="price">₱ 1,000</h1>
                </div>
            </div>

            <div class="service-container">
                <div class="image-container">
                    <img src="./Images/cleaning.png" alt="cleaning">
                </div>
                <div class="text-container">
                    <h1>Cleaning</h1>
                    <p>Out dentist will clean you teeth to prevent tooth aches and problems in the future.</p>
                    <h1 id="price">₱ 1,500</h1>
                </div>
            </div>

            <div class="service-container">
                <div class="image-container" id="overflow-image">
                    <img src="./Images/whitening.png" alt="whitening">
                </div>
                <div class="text-container">
                    <h1>Whitening</h1>
                    <p>With our dentist's help your teeth will glow and be whiter than snow.</p>
                    <h1 id="price">₱ 3,000</h1>
                </div>
            </div>
        </div>

        <div class="second-container">
            

            <div class="service-container">
                <div class="image-container">
                        <img src="./Images/filling.png" alt="filling">
                </div>
                <div class="text-container">
                        <h1>Filling</h1>
                        <p>Fill the gaps and holes in your tooth with our filling services.</p>
                        <h1 id="price">₱ 500</h1>
                </div>
            </div>

            <div class="service-container">
                <div class="image-container">
                    <img src="./Images/wisdom.png" alt="wisdom">
                </div>
                <div class="text-container">
                    <h1>Wisdon Removal</h1>
                    <p>Get rid of your tooth aches and have your aching wisdom tooth removed.</p>
                    <h1 id="price">₱ 4,000</h1>
                </div>
            </div>

            <div class="service-container">
                <div class="image-container" id="overflow-image">
                    <img src="./Images/removal.png" alt="removal">
                </div>
                <div class="text-container">
                    <h1>Tooth Removal</h1>
                    <p>Get rid of your tooth aches and have your aching tooth removed.</p>
                    <h1 id="price">₱ 2,500</h1>
                </div>
            </div>

        </div>

    </div>
    

</body>
</html>
