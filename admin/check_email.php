<?php
include("../connection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];

    // Check if the email already exists in the 'trainee' table
    $check_trainee_query = $database->prepare("SELECT COUNT(*) as count FROM trainee WHERE temail = ?");
    $check_trainee_query->bind_param("s", $email);

    if ($check_trainee_query->execute()) {
        $result_trainee = $check_trainee_query->get_result();
        $row_trainee = $result_trainee->fetch_assoc();
        $count_trainee = $row_trainee['count'];

        // Check if the email already exists in the 'webuser' table
        $check_webuser_query = $database->prepare("SELECT COUNT(*) as count FROM webuser WHERE email = ?");
        $check_webuser_query->bind_param("s", $email);

        if ($check_webuser_query->execute()) {
            $result_webuser = $check_webuser_query->get_result();
            $row_webuser = $result_webuser->fetch_assoc();
            $count_webuser = $row_webuser['count'];

            if ($count_trainee > 0 || $count_webuser > 0) {
                echo 'exists';
            } else {
                echo 'not_exists';
            }

            // Close the 'webuser' statement
            $check_webuser_query->close();
        } else {
            echo 'error';
        }

        // Close the 'trainee' statement
        $check_trainee_query->close();
    } else {
        echo 'error';
    }

    // Close the database connection
    $database->close();
}
?>
