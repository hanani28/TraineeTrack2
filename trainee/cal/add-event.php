<?php
require_once "../../connection.php";

session_start(); // Start the session

// Check if 'tid' is set in the session
if (!isset($_SESSION['tid'])) {
    // Handle the case where 'tid' is not set, e.g., redirect or display an error message
    echo "Trainee ID (tid) is not set in the session.";
    exit; // You can also redirect the user to another page
}

$title = isset($_POST['title']) ? $_POST['title'] : "";
$description = isset($_POST['description']) ? $_POST['description'] : "";
$start_date = isset($_POST['start']) ? $_POST['start'] : "";
$end_date = isset($_POST['end']) ? $_POST['end'] : "";
$tid = $_SESSION['tid']; // Get 'tid' from the session
$created = date("Y-m-d H:i:s"); // Current timestamp
$status = 1; // Default status

$sqlInsert = "INSERT INTO events (title, description, start_date, end_date, created, status, tid)
              VALUES ('$title', '$description', '$start_date', '$end_date', '$created', $status, $tid)";

$result = mysqli_query($conn, $sqlInsert);

if (!$result) {
    $error = mysqli_error($conn);
    // Handle the error appropriately, such as logging or displaying an error message.
    echo "Error: $error";
} else {
    // Insertion was successful.
    echo "Event added successfully.";
}

mysqli_close($conn);
?>
