<?php
// Your database connection code
include '../connection.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and get the values from the form
    $deptid = $_POST['deptid'];
    $newDeptNumber = $_POST['newDeptNumber'];
    $newNameDept = $_POST['newNameDept'];

    // Update the information in the database
    $sql = "UPDATE your_table_name SET deptnumber = ?, namedept = ? WHERE deptid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssi', $newDeptNumber, $newNameDept, $deptid);
    
    if ($stmt->execute()) {
        // Success
        echo json_encode(array('success' => true));
    } else {
        // Error
        echo json_encode(array('success' => false, 'error' => $stmt->error));
    }

    $stmt->close();
    $conn->close();
    exit();
}
?>
