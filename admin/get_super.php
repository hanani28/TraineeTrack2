<?php
// Include your database connection code
include("../connection.php");

if (isset($_POST['departmentId'])) {
    $departmentId = $_POST["departmentId"];

    // Fetch supervisors based on the selected department
    $sql = "SELECT * FROM supervisor WHERE deptid = $departmentId";
    $stmt = $database->prepare($sql);
    $stmt->bind_param("i", $departmentId);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    // Build the Supervisor dropdown options
    $options = '';
    while ($row = $result->fetch_assoc()) {
        $supervisorId = $row['superid'];
        $supervisorName = $row['name'];
        $options .= "<option value=\"$supervisorId\">$supervisorName</option>";
    }

    echo $options;
} else {
    // Handle the case where departmentId is not set
    echo 'Error: departmentId not set.';
}

// Close your database connection if needed
$database->close();
?>
