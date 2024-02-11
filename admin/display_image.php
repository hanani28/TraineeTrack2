<?php
include("../connection.php");

// Check if the 'tid' parameter is set in the URL
$tid = isset($_GET['tid']) ? $_GET['tid'] : null;

if ($tid === null) {
    // Handle the case where 'tid' is not set
    echo "Trainee data not found: 'tid' parameter is missing.";
    exit();
}

// Fetch image data based on the provided tid
// ...

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    // Set appropriate headers for an image
    header('Content-Type: image/jpeg'); // Adjust based on your image type (jpeg, png, etc.)

    // Output the image data
    echo $row['image_data'];
} else {
    // Image not found for the given tid
    // You can set a default image or handle it as needed
    header('Content-Type: image/png'); // Set a default image type
    readfile('path_to_default_image/default.png'); // Replace with the path to your default image
}

$stmt->close();
?>