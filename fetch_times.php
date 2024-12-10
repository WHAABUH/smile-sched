<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$database = "smile-sched-db";

$connection = new mysqli($servername, $username, $password, $database);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $date = $_POST['date'];

    // Fetch the already taken time slots for the selected date
    $sql_check = "SELECT start_time FROM appointments WHERE date = '$date'";
    $resultExist = $connection->query($sql_check);

    $available_times = ["08:00-09:00", "09:00-10:00", "11:00-12:00", "13:00-14:00", "14:00-15:00", "15:00-16:00"]; // Default time slots

    // If there are appointments already taken, filter them out from available_times
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

    // Return the available time slots as a JSON response
    echo json_encode(['available_times' => array_values($available_times)]);
}
?>
