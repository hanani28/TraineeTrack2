<?php
include("../connection.php");

// Check if the department ID is passed via GET request
if (isset($_GET['deptid'])) {
    $deptid = $_GET['deptid'];

    // Prepare and execute a SQL query to get supervisors for the selected department
    $sql = "SELECT superid, name FROM supervisor WHERE deptid = ?";
    $stmt = $database->prepare($sql);
    $stmt->bind_param("i", $deptid);
    $stmt->execute();

    // Fetch and store the results
    $result = $stmt->get_result();

    // Create an array to store the supervisors
    $supervisors = array();

    // Fetch each supervisor and add them to the array
    while ($row = $result->fetch_assoc()) {
        $supervisors[] = $row;
    }

    // Close the database connection
    $stmt->close();
    $database->close();

    // Return the supervisors as JSON
    echo json_encode($supervisors);
} else {
    echo "Department ID not provided.";
}
?>
