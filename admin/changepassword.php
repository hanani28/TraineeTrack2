<?php
session_start();

// Include necessary files and initialize database connection
include("../connection.php");

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

// Fetch current user's details from the database based on $_SESSION['user']
// Note: You need to fetch the hashed password from the database, e.g., $stored_hashed_password_from_database

// Assuming $database is your database connection

// Check if the 'tid' parameter is set in the URL
$tid = isset($_GET['tid']) ? $_GET['tid'] : null;

if ($tid === null) {
    // Handle the case where 'tid' is not set
    echo "Trainee data not found: 'tid' parameter is missing.";
    exit();
}

// Fetch trainee data based on the provided tid
$sql_trainee = "SELECT tpassword FROM trainee WHERE tid = ?";
$stmt_trainee = $database->prepare($sql_trainee);
$stmt_trainee->bind_param("i", $tid);
$stmt_trainee->execute();
$result_trainee = $stmt_trainee->get_result();

// Check if trainee data exists
if ($result_trainee->num_rows > 0) {
    $row_trainee = $result_trainee->fetch_assoc();
    $stored_hashed_password_from_database = $row_trainee['tpassword']; // Retrieve the hashed password from the database
} else {
    // Handle the case where trainee data is not found
    echo "Trainee data not found for tid: " . $tid;
    exit();
}

$stmt_trainee->close();

// Now, $stored_hashed_password_from_database contains the hashed password for the specified trainee


// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $current_password = $_POST["current_password"];
    $new_password = $_POST["new_password"];
    $confirm_password = $_POST["confirm_password"];

    // Verify current password before proceeding
    if (password_verify($current_password, $stored_hashed_password_from_database)) {
        // Current password is correct

        // Verify if the new password matches the confirmed password
        if ($new_password === $confirm_password) {
            // Hash the new password
            $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            // Update the user's password in the database
            $sql_update_password = "UPDATE trainee SET tpassword = ? WHERE tid = ?";
            $stmt_update_password = $database->prepare($sql_update_password);
            $stmt_update_password->bind_param("si", $new_hashed_password, $tid);
            
            if ($stmt_update_password->execute()) {
                echo "Password changed successfully.";
            } else {
                echo "Error updating password: " . $stmt_update_password->error;
            }

            $stmt_update_password->close();
        } else {
            echo "New password and confirm password do not match.";
        }
    } else {
        echo "Current password is incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
</head>
<body>
    <form action="changepassword.php?tid=<?php echo $tid; ?>" method="post">
        <label for="current_password">Current Password:</label>
        <input type="password" id="current_password" name="current_password" required><br>

        <label for="new_password">New Password:</label>
        <input type="password" id="new_password" name="new_password" required><br>

        <label for="confirm_password">Confirm New Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" required><br>

        <input type="submit" value="Change Password">
    </form>
</body>
</html>
