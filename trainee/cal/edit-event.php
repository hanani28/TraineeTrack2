<?php
require_once "../../connection.php";

$id = $_POST['id'];
$title = $_POST['title'];
$description = $_POST['description'];
$start_date = $_POST['start_date'];
$end_date = $_POST['end_date'];

$sqlUpdate = "UPDATE events SET title='" . $title . "', description='" . $description . "', start_date='" . $start_date . "', end_date='" . $end_date . "' WHERE id=" . $id;
mysqli_query($conn, $sqlUpdate);
mysqli_close($conn);
?>
