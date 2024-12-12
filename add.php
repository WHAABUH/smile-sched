<?php
session_start(); // Start the session

// Check if the user is logged in (loggedin session variable is set and true)
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Redirect to the login page if the user is not logged in
    header("Location: login.php");
    exit(); // Ensure the rest of the script doesn't execute
}

$servername = "localhost";
$username = "root";
$password = "";
$database = "smile-sched-db";

$connection = new mysqli($servername,$username,$password,$database);

$patient_email = $_SESSION['email'];
$patient = $_SESSION['fullname'];
$service = "";
$amount = 0;
$date = "";
$start_time = "";
$end_time = "";

$errormessage = "";
$successmessage = "";

$available_times = ["08:00-09:00", "09:00-10:00", "11:00-12:00", "13:00-14:00", "14:00-15:00", "15:00-16:00"]; // Default time slots

// Check for selected date and filter available time slots
if ($_SERVER['REQUEST_METHOD'] == 'POST') { // Ensure this block only runs when the form is submitted
    $date = $_POST['date']; 
    $service = $_POST['service']; 
    $amount = $_POST['amount'];

    $errormessage = "";
    $successmessage = "";

    if (isset($_POST['time']) && !empty($_POST['time'])) {
        $timeRange = $_POST['time']; 
        
        list($start_time, $end_time) = explode("-", $timeRange);
        
        $start_time = date("H:i:s", strtotime($start_time));
        $end_time = date("H:i:s", strtotime($end_time));
    }

    do {
        if (empty($patient) || empty($service) || empty($amount) || empty($date)) {
            $errormessage = "All fields must not be empty.";
            break;
        }
        
        // Query to check existing appointments for the selected date
        $sql_check = "SELECT start_time FROM appointments WHERE date = '$date'";
        $resultExist = $connection->query($sql_check);

        // If there are appointments already taken, filter them out from the available slots
        if ($resultExist->num_rows > 0) {
            while ($row = $resultExist->fetch_assoc()) {
                $taken_time = $row['start_time'];
                $taken_time_str = date("H:i", strtotime($taken_time));
                
                // Remove the taken time slot from available_times
                $available_times = array_filter($available_times, function($time) use ($taken_time_str) {
                    return !str_contains($time, $taken_time_str);
                });
            }
        }

        // Check if schedule is available for the selected time
        $sql_check_time = "SELECT * FROM appointments WHERE date = '$date' AND start_time = '$start_time'";
        $resultExistTime = $connection->query($sql_check_time);

        if($resultExistTime ->num_rows < 1){
            $sql = "INSERT INTO appointments (patient_email, patient, service, amount, date, start_time, end_time) 
            VALUES ('$patient_email', '$patient', '$service', '$amount', '$date', '$start_time', '$end_time')";
            $result = $connection->query($sql);
            
            if (!$result) {
                $errormessage = "Invalid query: " . $connection->error;
                echo '<script>alert("Error: ' . $errormessage . '"); window.location.href = "add.php";</script>';
                exit();
            }
            
            
            $service = "";
            $amount = 0;
            $date = "";
            $start_time = "";
            $end_time = "";
            
            $successmessage = "Successfully added an appointment.";
            
            echo '<script>alert("Appointment setup successful."); window.location.href = "add.php";</script>';
            exit();    
        }

        $errormessage = "Schedule already taken..";
        $resultExist = "";
       
    } while (false); // This ensures that the loop doesn't execute multiple times
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./CSS/add.css">
    <title>Smile-Sched</title>
    <script>
        
    function updateAmount() {
        const serviceAmounts = {
            "Consultation": 1000,
            "Cleaning": 1500,
            "Whitening": 3000,
            "Filling": 500,
            "Wisdom Removal": 4000,
            "Tooth Removal": 2500
        };

        const selectedService = document.querySelector("select[name='service']").value;
        const amountField = document.querySelector("input[name='amount']");
        amountField.value = serviceAmounts[selectedService] || "0.00";
    }

    function updateAvailableTimes() {
        const selectedDate = document.getElementById('date').value;
        if (!selectedDate) return; // Don't make the request if no date is selected

        // Create an AJAX request
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'fetch_times.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                const timeSelect = document.getElementById('time');
                timeSelect.innerHTML = ''; // Clear existing options

                // Add a default option
                const defaultOption = document.createElement('option');
                defaultOption.value = '';
                defaultOption.text = 'Select time';
                timeSelect.appendChild(defaultOption);

                // Add available time slots as options
                response.available_times.forEach(function(time) {
                    const option = document.createElement('option');
                    option.value = time;
                    option.text = time;
                    timeSelect.appendChild(option);
                });
            }
        };

        xhr.send('date=' + encodeURIComponent(selectedDate));
    }

    // Combine both functions into one window.onload event
    window.onload = function() {
        updateAmount();
        updateAvailableTimes(); // Ensure both functions run on page load
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
            <a href="./add.php" id="add_tab" style="
            cursor: pointer;">Add</a>
        </div>

        <div class="logout">
            <form action="logout.php" method="post">
                <button type="submit" class="logout-button">Logout</button>
            </form>
        </div>

    </div>
    <div class="mainContainer">

        <div class="formContainer">

            <form method="post" id="appointment-form">
                <div class="firstLayer">
                    <div>
                        <label for="name">Patient:</label>
                        <input type="text" value="<?php echo $patient; ?>" name="patient" readonly>
                    </div>
                    <div>
                        <label for="service">Service:</label>
                        <select name="service" onchange="updateAmount()" value="<?php echo $service; ?>">
                            <option value="Consultation">Consultation</option>
                            <option value="Cleaning">Cleaning</option>
                            <option value="Whitening">Whitening</option>
                            <option value="Filling">Filling</option>
                            <option value="Wisdom Removal">Wisdom Removal</option>
                            <option value="Tooth Removal">Tooth Removal</option>
                        </select>
                    </div>
                </div>
               
                <div class="secondLayer">
                    <div>
                        <label for="amount">Amount:</label>
                        <input type="text" name="amount" value="<?php echo $amount; ?>" readonly>
                    </div>
                    <div>
                        <label for="date">Date:</label>
                        <input type="date" name="date" id="date" value="<?php echo $date; ?>" min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" onchange="updateAvailableTimes()">
                    </div>
                </div>

                <div class="thirdLayer">
                    <div>
                        <label for="time">Time:</label>
                        <select name="time" id="time">
                            <?php
                            foreach ($available_times as $time) {
                                echo "<option value='$time'>$time</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div>
                        <label for="dentist">Dentist:</label>
                        <input type="text" value="Dr. John Doe" name="doctor" readonly>
                    </div>
                </div>
                
               <div class="buttonContainer">
                        <button type="reset" class="clear">Clear</button>
                        <button type="submit" class="confirm">Set Appointment</button>
               </div>
               
                <?php 
                if (!empty($successmessage)) {
                    echo "
                    <div>
                        <h1>$successmessage</h1>
                    </div> 
                ";
                }
                ?>
            </form>

        </div>

        

        <div class="maastigNaDesign">
            <div class="textToPre">
                <h1>You're in good hands!</h1>
            </div>
            <div class="imageToBai">
                <img src="./Images/goodhands.png" alt="">
            </div>
        </div>

    </div>
</body>
</html>
