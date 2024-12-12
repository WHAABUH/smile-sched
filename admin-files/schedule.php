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
    <link rel="stylesheet" href="../CSS/schedule.css">
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
                        <th class="noall">Date</th>
                        <th class="noall">Time</th>
                        <th class="noall">Status</th>
                        <th class="noleft">Actions</th>
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
                            WHERE status != 'Missed' AND status != 'Paid'
                            ORDER BY date ASC, start_time ASC";
                         
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
                                    <td>$row[date]</td>
                                    <td>{$start_time_str} - {$end_time_str}</td>
                                    <td>$row[status]</td>
                                    <td>
                                        <button class='doneButton' 
                                            data-id='$row[appointment_id]' 
                                            data-patient='$row[patient]' 
                                            data-email='$row[patient_email]' 
                                            data-service='$row[service]' 
                                            data-total='$row[amount]' 
                                            data-date='$row[date]' 
                                            data-start-time='$start_time_str' 
                                            data-end-time='$end_time_str'>
                                            Done
                                        </button>
                                        <button class='missedButton'>Missed</button>
                                    </td>
                                </tr>
                            ";
                         }

                        ?>
                        
                    </tbody>
                </table>
            </div>

        </div>

    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
        const doneButtons = document.querySelectorAll(".doneButton");

        doneButtons.forEach((button) => {
            button.addEventListener("click", function () {
                // Get data attributes from the clicked button
                const id = button.getAttribute("data-id");
                const patient = button.getAttribute("data-patient");
                const email = button.getAttribute("data-email");
                const service = button.getAttribute("data-service");
                const total = button.getAttribute("data-total");
                const date = button.getAttribute("data-date");
                const startTime = button.getAttribute("data-start-time");
                const endTime = button.getAttribute("data-end-time");

                // Redirect to payment.php with query parameters
                const url = `payment.php?id=${encodeURIComponent(id)}&patient=${encodeURIComponent(patient)}&email=${encodeURIComponent(email)}&service=${encodeURIComponent(service)}&total=${encodeURIComponent(total)}&date=${encodeURIComponent(date)}&start_time=${encodeURIComponent(startTime)}&end_time=${encodeURIComponent(endTime)}`;
                window.location.href = url;
            });
        });

        // Add event listener for "Missed" button
        const missedButtons = document.querySelectorAll(".missedButton");
        missedButtons.forEach((button) => {
            button.addEventListener("click", function () {
                const appointmentId = button.closest('tr').querySelector('[data-id]').getAttribute("data-id");

                // Send AJAX request to update the status to "Missed"
                const xhr = new XMLHttpRequest();
                xhr.open("POST", "update_status.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onload = function () {
                    if (xhr.status === 200) {
                        // Optionally, update the row's status in the UI
                        const statusCell = button.closest("tr").querySelector("td:nth-child(5)");
                        statusCell.textContent = "Missed";
                    } else {
                        alert("Error updating status.");
                    }
                };
                xhr.send("appointment_id=" + encodeURIComponent(appointmentId));
            });
        });
    });
    </script>


</body>
</html>
