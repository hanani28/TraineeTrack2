<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Skydash Admin</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="../vendors/feather/feather.css">
    <link rel="stylesheet" href="../vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="../vendors/css/vendor.bundle.base.css">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="../vendors/select2/select2.min.css">
    <link rel="stylesheet" href="../vendors/select2-bootstrap-theme/select2-bootstrap.min.css">
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="../css/vertical-layout-light/style.css">
    <!-- endinject -->
    <link rel="shortcut icon" href="../images/favicon.png" />
</head>
</head>
<style>
    /* External CSS file for styles */

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f5f5f5;
        margin: 0;
        padding: 0;
    }

    .popup {
        display: none;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        max-width: 900px;
        /* Adjust this value to change the maximum width of the form */
        width: 900px;
        /* Adjust this value to change the actual width of the form */
        background-color: #D1C4E9;
        padding: 50px;
        border-radius: 50px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        z-index: 1000;
    }


    .popup label {
        color: #333;
        /* Dark text color */
        display: block;
        margin-bottom: 8px;
    }

    .popup input {
        width: 100%;
        padding: 10px;
        box-sizing: border-box;
        margin-bottom: 12px;
        border: 1px solid #D1C4E9;
        /* Light soft purple border */
        border-radius: 5px;
        background-color: #fff;
        /* White background color */
        color: #333;
        /* Dark text color */
    }

    .popup button {
        padding: 10px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        margin-top: 8px;
        color: #a778af;
        /* White text color */
    }

    .popup button.close {
        background-color: #a778af;
        /* Darker soft purple color for close button */
    }

    .popup button.submit {
        background-color: #673AB7;
        /* Dark soft purple color for submit button */
    }

    .comment-history {
        margin-top: 16px;
        background-color: #fff;
        /* White background color */
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .comment-history h4 {
        color: #673AB7;
        /* Dark soft purple text color */
        margin-bottom: 8px;
    }

    .comment-history ul {
        list-style-type: none;
        padding: 0;
    }

    .comment-history li {
        margin-bottom: 8px;
        color: #333;
        /* Dark text color */
    }

    /* Add this CSS to customize the scrollbar for the table */
    .table-responsive {
        max-height: 300px;
        /* Set a max height for the table container */
        overflow-y: auto;
        /* Enable vertical scrollbar */
    }

    /* Optional: Customize the scrollbar appearance */
    .table-responsive::-webkit-scrollbar {
        width: 12px;
    }

    .table-responsive::-webkit-scrollbar-thumb {
        background-color: #6c757d;
        /* Scrollbar thumb color */
        border-radius: 10px;
    }

    .table-responsive::-webkit-scrollbar-track {
        background-color: #f1f1f1;
        /* Scrollbar track color */
    }
    


    /* Add more styles as needed */
</style>

<body>
    <?php

    //learn from w3schools.com

    session_start();

    if (isset($_SESSION["user"])) {
        if (($_SESSION["user"]) == "" or $_SESSION['usertype'] != 's') {
            header("location: ../login.php");
        } else {
            $useremail = $_SESSION["user"];
        }
    } else {
        header("location: ../login.php");
    }


    //import database
    include("../connection.php");
    $userrow = $database->query("select * from supervisor where semail='$useremail'");

    if (!$userrow) {
        die("Database error: " . $database->error);
    }

    $userfetch = $userrow->fetch_assoc();

    $userid = $userfetch["superid"];
    $username = $userfetch["name"];


    //   echo $userid; 
    //echo $username;

    ?>

<div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
    <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
      <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
        <a class="navbar-brand brand-logo mr-5" href="index.pgp"><img src="../images/img/logo_bphb.png" class="mr-2" alt="logo" /></a>
        <!-- <span> class="sidebar-title">Trainee Track</span> -->
        <a class="navbar-brand brand-logo-mini" href="index.php">Trainee Track</a>
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
        <!-- <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
          <span class="icon-menu"></span>
        </button> -->
                <ul class="navbar-nav mr-lg-2">
                    <!-- <li class="nav-item nav-search d-none d-lg-block">
                        <div class="input-group">
                            <div class="input-group-prepend hover-cursor" id="navbar-search-icon">
                                <span class="input-group-text" id="search">
                                    <i class="icon-search"></i>
                                </span>
                            </div>
                            <input type="text" class="form-control" id="navbar-search-input" placeholder="Search now" aria-label="search" aria-describedby="search">
                        </div>
                    </li> -->
                </ul>
                <ul class="navbar-nav navbar-nav-right">
                    <li class="nav-item dropdown">

                        </a>
                        <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="notificationDropdown">
                            <p class="mb-0 font-weight-normal float-left dropdown-header">Notifications</p>
                            <a class="dropdown-item preview-item">

                                <div class="preview-item-content">

                                </div>
                            </a>
                            <a class="dropdown-item preview-item">
                                <div class="preview-thumbnail">
                                    <div class="preview-icon bg-warning">
                                        <i class="ti-settings mx-0"></i>
                                    </div>
                                </div>

                            </a>
                            <a class="dropdown-item preview-item">
                                <div class="preview-thumbnail">
                                    <div class="preview-icon bg-info">
                                        <i class="ti-user mx-0"></i>
                                    </div>
                                </div>

                        </div>
                        </a>
            </div>
            </li>

            </ul>
            <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
                <span class="icon-menu"></span>
            </button>
    </div>
    </nav>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">

        <div id="right-sidebar" class="settings-panel">
            <i class="settings-close ti-close"></i>
            <!-- <ul class="nav nav-tabs border-top" id="setting-panel" role="tablist"> -->
            <li class="nav-item">
                <!-- <a class="nav-link active" id="todo-tab" data-toggle="tab" href="#todo-section" role="tab" aria-controls="todo-section" aria-expanded="true">TO DO LIST</a> -->
            </li>
            <li class="nav-item">
                <!-- <a class="nav-link" id="chats-tab" data-toggle="tab" href="#chats-section" role="tab" aria-controls="chats-section">CHATS</a> -->
            </li>
            </ul>
            <div class="tab-content" id="setting-content">
                <div class="tab-pane fade show active scroll-wrapper" id="todo-section" role="tabpanel" aria-labelledby="todo-section">
                    <div class="add-items d-flex px-3 mb-0">
                        <form class="form w-100">
                            <div class="form-group d-flex">
                                <input type="text" class="form-control todo-list-input" placeholder="Add To-do">
                                <button type="submit" class="add btn btn-primary todo-list-add-btn" id="add-task">Add</button>
                            </div>
                        </form>
                    </div>
                    <div class="list-wrapper px-3">
                        <ul class="d-flex flex-column-reverse todo-list">
                            <li>
                                <div class="form-check">

                                </div>
                                <i class="remove ti-close"></i>
                            </li>
                            <li>

                                <i class="remove ti-close"></i>
                            </li>
                            <li>

                                <i class="remove ti-close"></i>
                            </li>
                            <li class="completed">

                                <i class="remove ti-close"></i>
                            </li>
                            <li class="completed">
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input class="checkbox" type="checkbox" checked>
                                        Project review
                                    </label>
                                </div>
                                <i class="remove ti-close"></i>
                            </li>
                        </ul>
                    </div>
                    <h4 class="px-3 text-muted mt-5 font-weight-light mb-0">Events</h4>

                </div>
                <!-- To do section tab ends -->

                <!-- chat tab ends -->
            </div>
        </div>
        <!-- partial -->
        <!-- partial:../../partials/_sidebar.html -->
        <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">


          <li class="nav-item">
            <a class="nav-link" href="index.php">
              <i class="icon-grid menu-icon"></i>
              <span class="menu-title">Dashboard</span>
            </a>
          </li>


          <li class="nav-item">
            <a class="nav-link" href="trainee.php">
              <i class="icon-head menu-icon"></i>
              <span class="menu-title">Trainee</span>
            </a>
          </li>


          <li class="nav-item">
            <a class="nav-link" href="cuba2.php">
              <i class="icon-columns menu-icon"></i>
              <span class="menu-title">Task</span>
            </a>
          </li>


          <li class="nav-item">
            <a class="nav-link" href="../logout.php">
              <i class="icon- menu-icon"></i>
              <span class="menu-title">LogOut</span>
            </a>
          </li>

          <!-- <li class="nav-item">
            <a class="nav-link" href="cuba2.php">
              <i class="icon- menu-icon"></i>
              <span class="menu-title">LogOut</span>
            </a>
          </li> -->




        </ul>
      </nav>


        <div class="main-panel">

            <div class="content-wrapper">
                <div class="row">
                    <form action="" method="GET">
                        <div class="input-group mb-3">
                            <button type="button" onclick="history.back()" class="btn btn-secondary">Back</button>
                            <input type="text" name="search" required value="<?php if (isset($_GET['search'])) {
                                                                                    echo $_GET['search'];
                                                                                } ?>" class="form-control" placeholder="Search for a task...">
                            <button type="submit" class="btn btn-outline-info btn-fw btn-icon-text">Search</button>
                        </div>
                    </form>


                </div>
            </div>

            <div class="content-wrapper">
                <div class="row">
                    <div class="col-lg-12 stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">List Of Task</h4>
                                <p class="card-description">
                                    <!-- Add class <code>.table</code> -->
                                </p>
                                <div class="table-responsive pt-3">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Name (Trainee)</th>
                                                <th>Title</th>
                                                <th>Description</th>
                                                <th>Start Date</th>
                                                <th>End Date</th>
                                                <th>Created</th>
                                                <th>Status</th>
                                                <th>Event</th>

                                            </tr>
                                        </thead>

                                        <?php
                                        include("../connection.php");

                                        // Default query to retrieve all data when no search filter is provided
                                        if ($database->connect_error) {
                                            die("Connection failed: " . $database->connect_error);
                                        }

                                        // Update the SQL query to retrieve events for the specific trainee (tid) under the supervisor (superid)
                                        // Update the SQL query to retrieve events for the specific trainee (tid)
                                        // Update the SQL query to retrieve events for trainees under the supervisor (superid)
                                        $sql = "SELECT e.*, t.name AS name
                                        FROM events e
                                        INNER JOIN trainee t ON e.tid = t.tid
                                        WHERE t.superid = $userid;
                                        ";
                                        $result = $database->query($sql);

                                        function handleDatabaseError($database, $error)
                                        {
                                            echo "Error: " . $error . "<br>" . $database->error;
                                        }



                                        // Check if the form is submitted and if "id" is set in the $_POST array
                                        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id"]) && isset($_POST["comment"])) {
                                            // Process form data
                                            $comment = $_POST["comment"];
                                            $id = $_POST["id"];
                                            $superid = $_POST["superid"];
                                            $timestamp = $_POST["timestamp"];

                                            // Check if the specified 'id' exists in the 'events' table
                                            $checkEventExists = $database->query("SELECT id FROM events WHERE id='$id'");

                                            if ($checkEventExists->num_rows > 0) {
                                                // The event with the specified 'id' exists, proceed to insert the comment
                                                $sql = "INSERT INTO comments (comment, timestamp, superid, id) VALUES ('$comment', '$timestamp', '$superid', '$id')";

                                                if ($database->query($sql) === TRUE) {
                                                    echo "Comment added successfully";
                                                } else {
                                                    echo "Error: " . $sql . "<br>" . $database->error;
                                                }
                                            } else {
                                                echo "Error: Event with id='$id' does not exist";
                                            }
                                        } else {
                                            // Handle the case when the form is not submitted or "id" or "comment" is not set
                                            echo "Error: Form not submitted or 'id' or 'comment' not set";
                                        }

                                        ?>







                                        <!-- <div class="text-right mt-3">
                                                <button class="btn btn-secondary" onclick="redirectToAddTask()">Add Task</button>
                                            </div> -->

                                        <body>
                                            <div class="container-scroller">
                                                <?php
                                                if ($result->num_rows > 0) {
                                                    while ($row = $result->fetch_assoc()) {
                                                        // Define $isChecked and set it to an empty string by default
                                                        $isChecked = "";

                                                        // Determine if the task is finished or not based on "status" value
                                                        if ($row["status"] == 1) {
                                                            $isChecked = "checked";
                                                        }
                                                ?>
                                                        <tr>
                                                            <td><?= $row["name"] ?></td>
                                                            <td><?= $row["title"] ?></td>
                                                            <td><?= $row["description"] ?></td>
                                                            <td><?= $row["start_date"] ?></td>
                                                            <td><?= $row["end_date"] ?></td>
                                                            <td><?= $row["created"] ?></td>
                                                            <td>
                                                                <?= $row['status'] == 1 ? 'Completed' : 'Pending'; ?>
                                                            </td>
                                                            <!-- ... your existing HTML/PHP code ... -->

                                                            <td>
                                                                <!-- Hidden pop-up form -->
                                                                <div id="commentPopup_<?= $row["id"] ?>" class="popup" style="display: none;">
                                                                    <form method="post" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>">
                                                                        <input type="hidden" name="id" value="<?= $row["id"] ?>">
                                                                        <input type="hidden" name="superid" value="<?= $userid ?>">
                                                                        <input type="hidden" name="timestamp" value="<?= date('Y-m-d H:i:s') ?>">
                                                                        <!-- Add an input field for the comment -->
                                                                        <label for="comment">Comment:</label>
                                                                        <input type="text" name="comment" value="">
                                                                        <br>
                                                                        <input type="submit" value="Submit Comment">
                                                                    </form>

                                                                    <button onclick="closePopup('commentPopup_<?= $row["id"] ?>')">Close</button>

                                                                    <div class="col-md-13 grid-margin stretch-card">
                                                                        <div class="table-responsive pt-3">
                                                                            <table class="table table-striped custom-table">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th>Comment</th>
                                                                                        <th>Timestamp</th>
                                                                                        <th>Supervisor</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    <?php
                                                                                    // Fetch comments for the current event
                                                                                    $commentsQuery = $database->query("SELECT c.*, s.name as supername FROM comments c INNER 
                                                                                    JOIN supervisor s ON c.superid = s.superid WHERE c.id = {$row['id']} ORDER BY c.timestamp DESC");

                                                                                    if ($commentsQuery) {
                                                                                        while ($comment = $commentsQuery->fetch_assoc()) {
                                                                                            echo "<tr>";
                                                                                            echo "<td>{$comment['comment']}</td>";
                                                                                            echo "<td>{$comment['timestamp']}</td>";
                                                                                            echo "<td>{$comment['supername']}</td>";
                                                                                            echo "</tr>";
                                                                                        }
                                                                                    } else {
                                                                                        echo "<tr><td colspan='3'>Error fetching comments: " . $database->error . "</td></tr>";
                                                                                    }
                                                                                    ?>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Button to open the pop-up form -->
                                                                <button onclick="openPopup('commentPopup_<?= $row["id"] ?>')">Comment</button>
                                                            </td>
                                                            <td>

                                                                <button onclick="viewTask('<?= $row["id"] ?>')" class="btn btn-view">View</button>
                                                            </td>


                                                            <!-- ... your existing HTML/PHP code ... -->

                                                        </tr>
                                                    <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <tr>
                                                        <td colspan='8'>No events found for this user.</td>
                                                    </tr>
                                                <?php
                                                }
                                                ?>

                                                <script>
                                                    // JavaScript functions to handle opening and closing of the pop-up form
                                                    function openPopup(popupId) {
                                                        document.getElementById(popupId).style.display = "block";
                                                    }

                                                    function closePopup(popupId) {
                                                        document.getElementById(popupId).style.display = "none";
                                                    }

                                                    function confirmDelete(commentId) {
                                                        if (confirm("Are you sure you want to delete this comment?")) {
                                                            // Redirect to delete_comment.php with the comment ID
                                                            window.location.href = "delete_comment.php?id=" + commentId;
                                                        }
                                                    }


                                                    function viewTask(eventId) {
                                                        window.location.href = 'view_task.php?event_id=' + eventId;
                                                    }
                                                </script>

                                                </tbody>
                                    </table>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>

                <!-- content-wrapper ends -->
                <!-- partial:../../partials/_footer.html -->
                <footer class="footer">
                    <div class="d-sm-flex justify-content-center justify-content-sm-between">
                        <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2021. Premium <a href=https://learn.microsoft.com/en-us/credentials/browse/" target="_blank">Hanani</a> All rights reserved.</span>
                        <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Hand-crafted & made with <i class="ti-heart text-danger ml-1"></i></span>
                    </div>
                </footer>
                <!-- partial -->
            </div>
            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
</body>

</html>