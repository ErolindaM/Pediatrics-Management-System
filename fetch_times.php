<?php
require_once "includes/functions/connect.php";

if (!isset($_GET['doctor_id']) || !isset($_GET['date'])) {
    echo json_encode([]);
    exit;
}

$doctor_id = (int)$_GET['doctor_id'];
$date = $_GET['date'];


$all_times = [
    "09:00:00", "09:30:00", "10:00:00", "10:30:00",
    "11:00:00", "11:30:00", "12:00:00", "12:30:00",
    "13:00:00", "13:30:00", "14:00:00", "14:30:00",
    "15:00:00", "15:30:00", "16:00:00", "16:30:00"
];

$sql = "SELECT TIME(appointment_date) as time FROM appointments WHERE doctor_id = ? AND DATE(appointment_date) = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "is", $doctor_id, $date);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$booked_times = [];
while ($row = mysqli_fetch_assoc($result)) {
    $booked_times[] = $row['time'];
}

// Filtrimi i orëve të lira
$available_times = array_diff($all_times, $booked_times);

echo json_encode(array_values($available_times));
