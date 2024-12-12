<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['admin'] !== true) {
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
    <link rel="stylesheet" href="../CSS/accounts.css">
    <title>Patient Accounts</title>
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
            <a href="missed-sched.php" id="missed">Missed</a>
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
                <h1>Patient Accounts</h1>
            </div>

            <div class="tableHolder">
                    <table>
                        <thead>
                            <th class="noright">ID</th>
                            <th class="noall">Name</th>
                            <th class="noall">Email</th>
                            <th class="noall">Age</th>
                            <th class="noall">Sex</th>
                            <th class="noall">Role</th>
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
                                    FROM user_info
                                    WHERE role != 'admin'";
                                 
                                $result = $connection->query($sql);
    
                                if (!$result) {
                                    die("SQL Error: " . $connection->connect_error);
                                }
                                 
                                while ($row = $result->fetch_assoc()) {
                                    // Set the button text based on role
                                    $buttonText = ($row['role'] == 'banned') ? 'Unban User' : 'Ban User';
                                    echo "
                                        <tr>
                                            <td>$row[user_id]</td>
                                            <td>$row[fullname]</td>
                                            <td>$row[email]</td>
                                            <td>$row[age]</td>
                                            <td>$row[sex]</td>
                                            <td>$row[role]</td>
                                            <td>
                                                <button class='banUser' data-user-id='$row[user_id]' data-role='$row[role]'>$buttonText</button>
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
        document.querySelectorAll('.banUser').forEach(button => {
            button.addEventListener('click', function() {
                const userId = this.getAttribute('data-user-id');
                const action = this.textContent.trim() === 'Unban User' ? 'unban' : 'ban';
                
                handleBanUnban(userId, action, this);
            });
        });

        function handleBanUnban(userId, action, button) {
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "ban_unban.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            xhr.send("user_id=" + userId + "&action=" + action);

            xhr.onload = function() {
                if (xhr.status == 200) {
                    const response = JSON.parse(xhr.responseText);
                    button.textContent = response.newButtonText;
                    button.setAttribute("data-role", response.newRole);
                }
            };
        }
    </script>

</body>
</html>
