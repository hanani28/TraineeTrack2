<?php
include("../connection.php");

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["reset"]) && isset($_GET["superid"])) {
    $superid = $_GET["superid"];
    $newPassword = password_hash("1224", PASSWORD_DEFAULT);

    // Update the supervisor's password
    $update_query = $database->prepare("UPDATE supervisor SET spassword = ? WHERE superid = ?");
    $update_query->bind_param("si", $newPassword, $superid);

    if ($update_query->execute()) {
        // Password updated successfully
        echo '<script>alert("Password reset to 1224 successfully.");</script>';
    } else {
        // Error updating password
        echo '<script>alert("Error resetting password: ' . $update_query->error . '");</script>';
    }

    // Close the update statement
    $update_query->close();

    // Close the database connection
    $database->close();
} else {
    // Invalid request
    echo '<script>alert("Invalid request.");</script>';
}
?>
