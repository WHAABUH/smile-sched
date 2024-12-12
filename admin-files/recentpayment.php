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
    <link rel="stylesheet" href="../CSS/recentpayments.css">
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
                <th class="noright">Payment ID</th>
                <th class="noall">Email</th>
                <th class="noall">Total</th>
                <th class="noall">Payment</th>
                <th class="noall">Change</th>
                <th class="noall">Method</th>
                <th class="noleft">Paid at</th>
            </thead>
            <tbody>
                <?php
                 
                 $servername = "localhost";
                 $username = "root";
                 $password = "";
                 $database = "smile-sched-db";

                 $connection = new mysqli($servername, $username, $password, $database);

                 $sql = "SELECT * 
                    FROM payment ORDER BY created_at ASC";
                 
                 $result = $connection->query($sql);

                 if(!$result){
                     die("SQL Error: " . $connection->connect_error);
                 }
                
                 while($row = $result->fetch_assoc()){
                    echo "
                        <tr>
                            <td>$row[payment_id]</td>
                            <td>$row[email]</td>
                            <td>$row[total]</td>
                            <td>$row[payment]</td>
                            <td>$row[change_amount]</td>
                            <td>$row[payment_method]</td>
                            <td>$row[created_at]</td>
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