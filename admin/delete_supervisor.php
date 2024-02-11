<?php
include("../connection.php");

if (isset($_POST['superid'])) {
  $superid = $_POST['superid'];

  // Call the deleteSupervisor function to delete the supervisor
  $deleted = deleteSupervisor($database, $superid);

  if ($deleted) {
    echo "Supervisor deleted successfully.";
  } else {
    echo "Failed to delete supervisor.";
  }
} else {
  // Invalid request
  echo 'Invalid request';
}

function deleteSupervisor($database, $superid)
{
  // Check for related trainees before deleting the supervisor
  $hasRelatedTrainees = checkRelatedTrainees($database, $superid);

  if ($hasRelatedTrainees) {
    // If there are related trainees, return false to indicate failure
    return false;
  }

  // If no related trainees, proceed with deleting the supervisor
  $query = "DELETE FROM supervisor WHERE superid = '$superid'";
  $query_run = mysqli_query($database, $query);

  return $query_run;
}

function checkRelatedTrainees($database, $superid)
{
  // Perform a query to check if there are related trainees
  $query = "SELECT COUNT(*) AS count FROM trainee WHERE superid = '$superid'";
  $result = mysqli_query($database, $query);

  if ($result) {
    $row = mysqli_fetch_assoc($result);
    $count = $row['count'];

    // If there are related trainees, return true; otherwise, return false
    return ($count > 0);
  } else {
    // Error in query execution, assume there are related trainees
    return true;
  }
}
?>

