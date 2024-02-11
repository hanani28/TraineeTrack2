<?php
// Include your database connection code here
include('../connection.php');

// Check if dept_id is set in the POST data
if (isset($_POST['dept_id'])) {
    // Retrieve dept_id from POST data
    $deptId = $_POST['dept_id'];

    // Fetch department data based on dept_id
    $query = "SELECT * FROM department WHERE dept_id = '$deptId'";
    $result = mysqli_query($database, $query);

    if ($result) {
        // Fetch the data as an associative array
        $data = mysqli_fetch_assoc($result);

        // Convert the array to JSON and echo it
        echo json_encode($data);
    } else {
        // Handle the error (you can customize this based on your needs)
        echo json_encode(['error' => 'Failed to fetch department data']);
    }
} else {
    // Handle the case where dept_id is not set in the POST data
    echo json_encode(['error' => 'dept_id is not set']);
}

// Close the database connection
mysqli_close($database);
?>
