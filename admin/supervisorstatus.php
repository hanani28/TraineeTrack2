<?php
include("../connection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["superid"]) && isset($_POST["newStatus"])) {
    $superid = $_POST["superid"];
    $newStatus = $_POST["newStatus"];

    // Update the status in the database
    $updateQuery = "UPDATE supervisor SET status = '$newStatus' WHERE superid = '$superid'";
    $updateResult = mysqli_query($database, $updateQuery);

    if ($updateResult) {
        echo "Status updated successfully.";
    } else {
        echo "Failed to update status.";
    }
} else {
    echo "Invalid request.";
}
?>
