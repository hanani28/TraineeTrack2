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
$sql_trainee = "SELECT spassword FROM supervisor WHERE superid = ?";
$stmt_trainee = $database->prepare($sql_trainee);
$stmt_trainee->bind_param("i", $tid);
$stmt_trainee->execute();
$result_trainee = $stmt_trainee->get_result();

// Check if trainee data exists
if ($result_trainee->num_rows > 0) {
    $row_trainee = $result_trainee->fetch_assoc();
    $stored_hashed_password_from_database = $row_trainee['spassword']; // Retrieve the hashed password from the database
} else {
    // Handle the case where trainee data is not found
    echo "Trainee data not found for tid: " . $tid;
    exit();
}

$stmt_trainee->close();

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
            $sql_update_password = "UPDATE supervisor SET spassword = ? WHERE superid = ?";
            $stmt_update_password = $database->prepare($sql_update_password);
            $stmt_update_password->bind_param("si", $new_hashed_password, $tid);

        
            if ($stmt_update_password->execute()) {
                $successMessage = "Password changed successfully.";
            } else {
                $errorMessage = "Error updating password: " . $stmt_update_password->error;
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
    <title>Password Change</title>
    <!-- Include other necessary styles or scripts here -->

    <?php if (isset($successMessage)) : ?>
        <!-- Display success message and set JavaScript variable -->
        <script>
            var passwordChangeSuccess = true;
            setTimeout(function () {
                window.location.href = "index.php";
            }, 1000);
        </script>
    <?php endif; ?>

    <?php if (isset($errorMessage)) : ?>
        <!-- Display error message -->
        <script>
            var passwordChangeSuccess = false;
        </script>
    <?php endif; ?>
</head>
<body>
    <!-- Your HTML content here -->
    <?php
        if (isset($successMessage)) {
            echo '<div id="successMessage">' . $successMessage . '</div>';
        } elseif (isset($errorMessage)) {
            echo '<div id="errorMessage">' . $errorMessage . '</div>';
        }
    ?>

    <!-- Include other HTML content -->

    <!-- Include Bootstrap JS if needed -->
    <script src="path/to/jquery.min.js"></script>
    <script src="path/to/bootstrap.bundle.min.js"></script>
    
    <script>
        // Check the JavaScript variable and redirect if needed
        document.addEventListener("DOMContentLoaded", function () {
            if (typeof passwordChangeSuccess !== 'undefined' && passwordChangeSuccess) {
                setTimeout(function () {
                    window.location.href = "index.php";
                }, 1000);
            }
        });
    </script>
</body>
</html>