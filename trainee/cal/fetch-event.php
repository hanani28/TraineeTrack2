<?php
session_start();

if (isset($_SESSION["user"])) {
    if ($_SESSION["user"] == "" or $_SESSION['usertype'] != 't') {
        header("location: ../login.php");
    } else {
        $useremail = $_SESSION["user"];
        // import database
        include("../connection.php");
        $userrow = $database->query("SELECT * FROM trainee WHERE temail='$useremail'");

        if (!$userrow) {
            die("Database error: " . $database->error);
        }

        // Fetch the trainee's ID (tid) from the database result
        $traineeData = $userrow->fetch_assoc();
        $tid = $traineeData['id'];

        // Now, fetch events based on the trainee's tid
        $json = array();
        $sqlQuery = "SELECT * FROM events WHERE tid = $tid ORDER BY id";

        $result = mysqli_query($conn, $sqlQuery);
        $eventArray = array();
        while ($row = mysqli_fetch_assoc($result)) {
            array_push($eventArray, $row);
        }
        mysqli_free_result($result);

        mysqli_close($conn);
        echo json_encode($eventArray);
    }
} else {
    header("location: ../login.php");
}
?>
