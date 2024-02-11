<?php
// Set headers for CSV download
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="trainee_list_' . date('Y-m-d_H-i-s') . '.csv"');

// Open the output stream
$output = fopen('php://output', 'w');

// Sample data for the header row
$header = array('Name', 'Email', 'Department', 'Supervisor Name','Username','Phone Number', 'Start Date', 'End Date', 'Course of Study', 'Gender', 'Status', 'Institute');
fputcsv($output, $header);

// Database connection parameters
include('../connection.php');

// Check connection
if ($database->connect_error) {

    // Handle the error, e.g., display an error message

    echo "Connection failed: " . $database->connect_error;

    // You can also log the error for debugging


    error_log("Connection failed: " . $database->connect_error);

    exit; // Exit the script  without generating the CSV
}

// Query to fetch all data from the 'trainee' table
$sql = "SELECT t.tid, t.name AS trainee_name, t.temail, t.deptid, d.namedept, 
        t.superid, s.name AS supervisor_name, t.username,
        t.phone_num, t.startdate, t.endate, t.courseofstudy, t.gender, t.status, t.uni
        FROM trainee AS t
        JOIN department AS d ON t.deptid = d.deptid
        JOIN supervisor AS s ON t.superid = s.superid";

$result = $database->query($sql);
// ...

while ($row = $result->fetch_assoc()) {
    $data = array(
        $row['trainee_name'],
        $row['temail'],
        $row['namedept'],
        $row['supervisor_name'],
        $row['username'],
        // $row['tpassword'],
        $row['phone_num'],
        sprintf('%02d/%02d/%04d', date('d', strtotime($row['startdate'])), date('m', strtotime($row['startdate'])), date('Y', strtotime($row['startdate']))), // Format startdate as dd/mm/yyyy
        sprintf('%02d/%02d/%04d', date('d', strtotime($row['endate'])), date('m', strtotime($row['endate'])), date('Y', strtotime($row['endate']))), // Format endate as dd/mm/yyyy
        $row['courseofstudy'],
        $row['gender'],
        ($row['status'] == 1) ? 'Active' : 'Inactive',
        $row['uni']
    );
    fputcsv($output, $data);
}
// Close the database connection
$database->close();

// Close the output stream
fclose($output);
?>
