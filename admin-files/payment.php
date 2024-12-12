<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['admin'] !== true) {
    // Redirect to login page if not logged in
    header("Location: http://localhost/smile-sched/login.php");
    exit();
}

// Greet the user
$fullname = htmlspecialchars($_SESSION['fullname']); // Escape to prevent XSS

// Retrieve data passed from schedule.php
$id = isset($_GET['id']) ? $_GET['id'] : "Unknown ID";
$patient = isset($_GET['patient']) ? $_GET['patient'] : "Unknown Patient";
$email = isset($_GET['email']) ? $_GET['email'] : "Unknown Email";
$service = isset($_GET['service']) ? $_GET['service'] : "Unknown Service";
$total = isset($_GET['total']) ? $_GET['total'] : "Unknown Total"; // Ensure this is numeric
$date = isset($_GET['date']) ? $_GET['date'] : "Unknown Date";
$start_time = isset($_GET['start_time']) ? $_GET['start_time'] : "Unknown Start Time";
$end_time = isset($_GET['end_time']) ? $_GET['end_time'] : "Unknown End Time";

$message = "";

$payment_method = $payment_amount = $change_amount = "";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if required fields are set and not empty
    if (isset($_POST['payment_method'], $_POST['payment_amount'], $_POST['change_amount'])) {
        $payment_method = htmlspecialchars($_POST['payment_method']);
        $payment_amount = $_POST['payment_amount'];  // Directly use POST data for payment_amount
        $change_amount = $_POST['change_amount'];    // Directly use POST data for change_amount

        // Ensure the total is treated as numeric for comparison
        $total = (float)$total;
        $payment_amount = (float)$payment_amount;

        echo "<script>console.log('$total');</script>";

        // Check if the payment amount is greater than or equal to the total
        if ($payment_amount >= $total) {
            // Insert data into the database
            $servername = "localhost";
            $username = "root";
            $password = "";
            $database = "smile-sched-db";

            $connection = new mysqli($servername, $username, $password, $database);

            if ($connection->connect_error) {
                die("Connection failed: " . $connection->connect_error);
            }

            // Use a prepared statement to avoid SQL injection
            $stmt = $connection->prepare("INSERT INTO payment (appointment_id, email, total, payment, change_amount, payment_method) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("isiiis", $id, $email, $total, $payment_amount, $change_amount, $payment_method);

            // Execute the insert statement
            if ($stmt->execute()) {
                // If the insert is successful, update the appointment status
                $sql = "UPDATE appointments SET status = 'Paid' WHERE appointment_id = ?";
                $update_stmt = $connection->prepare($sql);
                $update_stmt->bind_param("i", $id);
                if($update_stmt->execute()){
                    echo '<script>alert("Payment successful."); window.location.href = "schedule.php";</script>';
                    exit();
                }
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
            $connection->close();
        } else {
            echo '<script>alert("Payment cant be less than the total.");</script>';
        }
    } else {
        echo "Please fill out all payment fields.";
    }
}
?>


<!-- HTML remains unchanged -->


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/payment.css">
    <title>Payment Finalization</title>
</head>
<body>

    <div class="mainContainer">

        <div class="receiptContainer">

            <div class="titleContainer">
                <h1>Payment Finalization</h1>
            </div>

            <div class="fieldsContainer">
                
                <div class="fieldContainer">
                    <span>Patient: <?php echo $patient; ?></span>
                </div>
                <div class="fieldContainer">
                    <span>Email: <?php echo $email; ?></span>
                </div>
                <div class="fieldContainer">
                    <span>Service: <?php echo $service; ?></span>
                </div>
                <div class="fieldContainer">
                    <span>Total: ₱ <?php echo $total; ?></span>
                </div>
                <!-- Payment Form -->
                <form name="paymentForm" method="POST" class="formInput">

                    <div class="fieldContainer">
                        <span>Payment Method:
                            <select name="payment_method" id="payment_method" required>
                                <option value="Cash" <?php echo ($payment_method == 'Cash') ? 'selected' : ''; ?>>Cash</option>
                                <option value="E-Money" <?php echo ($payment_method == 'E-Money') ? 'selected' : ''; ?>>E-Money</option>
                            </select>
                        </span>
                    </div>
                    <div class="fieldContainer">
                        <span>Payment: 
                            <input type="number" id="payment_amount" name="payment_amount" value="<?php echo $payment_amount; ?>" required>
                        </span>
                    </div>
                    <div class="fieldContainer">
                        <span>Change: 
                            <input type="number" id="change_amount" name="change_amount" value="<?php echo $change_amount; ?>" required readonly>
                        </span>
                    </div>

                    <div class="buttonContainer">
                        <button id="cancelButton" type="button" onclick="window.location.href = 'schedule.php';">Cancel</button>
                        <button id="confirmButton" type="submit">Confirm</button>
                    </div>

                </form>

            </div>

        </div>

    </div>

    <script>
        window.addEventListener('wheel', function (e) {
            if (e.ctrlKey) {
                e.preventDefault();
            }
        }, { passive: false });

        // Example JavaScript to calculate change dynamically
        document.getElementById('payment_amount').addEventListener('input', function () {
            const total = <?php echo json_encode((float)$total); ?>;
            const payment = parseFloat(this.value) || 0;
            const change = payment - total;
            document.getElementById('change_amount').value = change > 0 ? change.toFixed(2) : 0;
        });

    </script>

</body>
</html>