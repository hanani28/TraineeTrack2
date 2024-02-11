<?php
include("../connection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $subid = $_POST["subsidiaryId"];

    // Fetch departments based on the selected subsidiary
    $sql = "SELECT * FROM department WHERE subid = $subid";
    $result = $database->query($sql);

    // Generate the HTML options for the department dropdown
    $options = '';
    while ($row = $result->fetch_assoc()) {
        $deptid = $row['deptid'];
        $namedept = $row['namedept'];
        $options .= "<option value=\"$deptid\">$namedept</option>";
    }

    echo $options;
}

$database->close();
?>
