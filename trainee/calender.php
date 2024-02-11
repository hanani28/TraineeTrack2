<?php
session_start();

if (isset($_SESSION["user"])) {
    if ($_SESSION["user"] == "" or $_SESSION['usertype'] != 't') {
        header("location: ../login.php");
        exit;
    } else {
        $useremail = $_SESSION["user"];
    }
} else {
    header("location: ../login.php");
    exit;
}

// Import database
include("../connection.php");
$userrow = $database->query("SELECT * FROM trainee WHERE temail='$useremail'");

if (!$userrow) {
    die("Database error: " . $database->error);
}

$userfetch = $userrow->fetch_assoc();

// Define $userid based on $userfetch or your logic
if (isset($userfetch["tid"])) {
    $userid = $userfetch["tid"];
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    // Set a default value for $userid
    $userid = 0; // You can choose a different default value if needed
}

$username = $userfetch["name"];

// Use prepared statements to fetch events safely
$tid = $userid;
$sql = "SELECT id, title, start_date, end_date FROM events WHERE tid = ?";
$stmt = $database->prepare($sql);
$stmt->bind_param("i", $tid); // "i" represents an integer
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    die("Query failed: " . mysqli_error($database));
}

$events = array();
while ($row = mysqli_fetch_assoc($result)) {
    var_dump($row); // Debug: Print the contents of each row
    $event = array(
        'id' => $row['id'],
        'title' => $row['title'],
        'start' => $row['start_date'],
        'end' => $row['end_date']
    );
    $events[] = $event;
}


// JSON encode the dynamic events data
$events_json = json_encode($events);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8' />
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.10.1/main.css' />
    <script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js'></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js"></script>
    <script src="https://unpkg.com/fullcalendar@5.10.1/dist/main.min.js"></script>
    <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css'>

    



</head>

<body>
<div class="container">
    <h1 class="text-center">My Calendar</h1>
    <div id='calendar'></div>
</div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('calendar');
            var events = <?php echo $events_json; ?>;

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: events
            });

            calendar.render();
        });
    </script>
</body>
</html>
