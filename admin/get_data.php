<?php
include("../connection.php");
// Include your database connection code here

if (isset($_GET['departments'])) {
    // Fetch departments based on subsidiaryId
    $subsidiaryId = $_GET['subsidiaryId']; // You need to sanitize and validate input
    $departments = getDepartments($subsidiaryId);

    // Return the data as JSON
    header('Content-Type: application/json');
    echo json_encode($departments);
    exit;
}

// Add other cases for handling different data fetching scenarios

function getDepartments($subsidiaryId) {
  include("../connection.php");
    // Example query to fetch departments based on subsidiaryId
    // You need to customize this query based on your database schema
    $sql = "SELECT * FROM department WHERE subid = ?";
    $stmt = $database->prepare($sql);
    $stmt->bind_param("i", $subsidiaryId); // Assuming subid is an integer, adjust accordingly
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch data from the result set
    $departments = array();
    while ($row = $result->fetch_assoc()) {
        $departments[] = $row;
    }

    // Close the statement
    $stmt->close();

    return $departments;
}

// Add similar functions for fetching other types of data (supervisors, subsidiaries, etc.)

?>
