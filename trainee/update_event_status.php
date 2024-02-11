<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['eventId'])) {
        // Replace with your database connection details
        include("../connection.php");
        
        $eventId = $_POST['eventId'];

        // Check if all mini-tasks are checked for the event
        $checkQuery = "SELECT COUNT(*) as unchecked_count FROM minitask WHERE id = $eventId AND status = 0";
        $result = mysqli_query($database, $checkQuery);
        $row = mysqli_fetch_assoc($result);
        $uncheckedCount = $row['unchecked_count'];

        if ($uncheckedCount == 0) {
            // Update the event status to 1
            $updateQuery = "UPDATE events SET status = 1 WHERE id = $eventId";
            if (mysqli_query($database, $updateQuery)) {
                echo "Event status updated successfully.";
            } else {
                echo "Error updating event status: " . mysqli_error($database);
            }
        }
    }
}
?>
