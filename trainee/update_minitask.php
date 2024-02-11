<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['minitaskId']) && isset($_POST['isChecked'])) {
    // Replace with your database connection details
    include("../connection.php");

    $minitaskId = $_POST['minitaskId'];
    $isChecked = $_POST['isChecked'];

    // Update the mini-task status in the database
    $updateQuery = "UPDATE minitask SET status = $isChecked WHERE minID = $minitaskId";
    if (mysqli_query($database, $updateQuery)) {
        echo "Mini-Task status updated successfully.";
    } else {
        echo "Error updating mini-task status: " . mysqli_error($database);
    }
}
?>
