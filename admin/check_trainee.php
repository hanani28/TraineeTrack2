<?php
include("../connection.php");

if (isset($_POST['superid'])) {
  $superid = $_POST['superid'];

  // Fetch related trainees
  $query = "SELECT name FROM trainee WHERE superid = '$superid'";
  $result = mysqli_query($database, $query);

  $traineeList = array();
  while ($row = mysqli_fetch_assoc($result)) {
    $traineeList[] = $row['name'];
  }

  // Check if there are related trainees
  $hasRelatedTrainees = !empty($traineeList);

  // Return JSON response
  echo json_encode(array('hasRelatedTrainees' => $hasRelatedTrainees, 'traineeList' => $traineeList));
} else {
  // Invalid request
  echo 'Invalid request';
}
?>
