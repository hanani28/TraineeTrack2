<?php
// Include your database connection code here
include("../connection.php");

// Function to generate the next trainee number based on the pattern
function generateTraineeNumber() {
    global $database;

    $result = $database->query("SELECT MAX(traineenumber) AS max_number FROM trainee");
    $row = $result->fetch_assoc();
    $maxNumber = $row['max_number'];

    if ($maxNumber) {
        $prefix = substr($maxNumber, 0, 1);
        $suffix = (int)substr($maxNumber, 1);

        if ($suffix < 9999) {
            $suffix++;
        } else {
            $prefix++;
            $suffix = 1;
        }
    } else {
        // Initial value if no existing trainee numbers
        $prefix = 'A';
        $suffix = 1;
    }

    $newTraineeNumber = $prefix . sprintf("%04d", $suffix);
    return $newTraineeNumber;
}

echo generateTraineeNumber();
?>
