<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: http://localhost/smile-sched/login.php");
    exit();
}

// Greet the user
$fullname = htmlspecialchars($_SESSION['fullname']);

$tomorrow = date("Y-m-d", strtotime("+2 day")); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./CSS/add.css"/>
    <title>Home</title>
    <script>
        // JavaScript function to update the amount based on selected service
        function updateAmount() {
            var service = document.getElementById("service").value;
            var amountText = document.getElementById("amount-text");

            // Define the amounts for each service
            var serviceAmounts = {
                "Consultation": "₱ 1000",
                "Cleaning": "₱ 1500",
                "Whitening": "₱ 3000",
                "Filling": "₱ 500",
                "Wisdom Removal": "₱ 4000",
                "Tooth Removal": "₱ 2500"
            };

            // Update the amount text
            amountText.textContent = serviceAmounts[service] || "₱ 0"; // Default to ₱ 0 if no service is selected
        }

        // Add an event listener to update the amount when the service is changed
        window.onload = function() {
            document.getElementById("service").addEventListener("change", updateAmount);
        };
    </script>
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
            <a href="./add.php" id="add" style="text-decoration: underline;  font-weight: bolder;
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
            <div class="service-amount-container">

                <div class="service">
                    <label for="service">Service:</label>
                    <select name="service" id="service">
                        <option value="Consultation">Consultation</option>
                        <option value="Cleaning">Cleaning</option>
                        <option value="Whitening">Whitening</option>
                        <option value="Filling">Filling</option>
                        <option value="Wisdom Removal">Wisdom Removal</option>
                        <option value="Tooth Removal">Tooth Removal</option>
                    </select>
                </div>

                <div class="amount">
                    <label for="dentist">Amount:</label>
                    <div class="amount-holder">
                        <p id="amount-text">₱ 1000</p> <!-- This will be updated dynamically -->
                    </div>
                </div>

            </div>

            <div class="date-time-container">
                <div class="date">
                    <label for="date">Date:</label>
                    <input type="date" id="date" name="date" min="<?php echo $tomorrow; ?>">
                </div>

                <div class="time">
                    <label for="amount">Time:</label>
                    <select name="service">
                        <option value="9AM - 10AM">9AM - 10AM</option>
                        <option value="10AM - 11AM">10AM - 11AM</option>
                        <option value="11AM - 12PM">11AM - 12PM</option>
                        <option value="1PM - 2PM">1PM - 2PM</option>
                        <option value="2PM - 3PM">2PM - 3PM</option>
                        <option value="4PM - 5PM">4PM - 5PM</option>
                        <option value="5PM - 6PM">5PM - 6PM</option>
                    </select>
                </div>
            </div>

            <div class="dentist-container">
                <div class="dentist">
                    <label for="dentist">Assigned Dentist:</label>
                    <div class="name-holder">
                        <p>Leonie von Meusebach–Zesch</p>
                    </div>
                </div>
            </div>

            <div class="add-appointment-container">
                <button>Add Appointment</button>
            </div>

        </div>

        <div class="right-side">
            <div class="text-container">
                <h1>You're in good hands!</h1>
            </div>

            <div class="image-container">
                <img src="./Images/addpage.png" alt="safe-hands">
            </div>

        </div>

    </div>

</body>
</html>
