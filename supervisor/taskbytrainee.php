<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Supervisor</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="../vendors/feather/feather.css">
    <link rel="stylesheet" href="../vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="../vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="../vendors/datatables.net-bs4/dataTables.bootstrap4.css">
    <link rel="stylesheet" href="../vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" type="../text/css" href="../js/select.dataTables.min.css">
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="../css/vertical-layout-light/style.css">
    <link rel="stylesheet" href="../css/vertical-layout-light/style.css">
    <!-- endinject -->
    <link rel="shortcut icon" href="../images/favicon.png" />
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

<body>
    <div class="container-scroller">
        <!-- partial:../../partials/_navbar.html -->
        <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
            <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
                <a class="navbar-brand brand-logo mr-5" href="../index.html"><img src="../images/logo.svg" class="mr-2" alt="logo" /></a>
                <a class="navbar-brand brand-logo-mini" href="../index.html"><img src="../images/logo-mini.svg" alt="logo" /></a>
            </div>
            <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
                <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
                    <span class="icon-menu"></span>
                </button>
                <ul class="navbar-nav mr-lg-2">
                    <li class="nav-item nav-search d-none d-lg-block">
                        <div class="input-group">
                            <div class="input-group-prepend hover-cursor" id="navbar-search-icon">
                                <span class="input-group-text" id="search">
                                    <i class="icon-search"></i>
                                </span>
                            </div>
                            <input type="text" name="search" required value="<?php if (isset($_GET['search'])) {
                                                                                    echo $_GET['search'];
                                                                                } ?>" class="form-control" placeholder="Search data">
                    </li>
                </ul>

                <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
                    <span class="icon-menu"></span>
                </button>
            </div>
        </nav>
        <!-- partial -->
        <div class="container-fluid page-body-wrapper">
            <!-- partial:../../partials/_settings-panel.html -->
            <div class="theme-setting-wrapper">
                <div id="settings-trigger"><i class="ti-settings"></i></div>
                <div id="theme-settings" class="settings-panel">
                    <i class="settings-close ti-close"></i>
                    <p class="settings-heading">SIDEBAR SKINS</p>
                    <div class="sidebar-bg-options selected" id="sidebar-light-theme">
                        <div class="img-ss rounded-circle bg-light border mr-3"></div>Light
                    </div>
                    <div class="sidebar-bg-options" id="sidebar-dark-theme">
                        <div class="img-ss rounded-circle bg-dark border mr-3"></div>Dark
                    </div>
                    <p class="settings-heading mt-2">HEADER SKINS</p>
                    <div class="color-tiles mx-0 px-4">
                        <div class="tiles success"></div>
                        <div class="tiles warning"></div>
                        <div class="tiles danger"></div>
                        <div class="tiles info"></div>
                        <div class="tiles dark"></div>
                        <div class="tiles default"></div>
                    </div>
                </div>
            </div>
            <div id="right-sidebar" class="settings-panel">
                <i class="settings-close ti-close"></i>

                <div class="tab-content" id="setting-content">
                    <div class="tab-pane fade show active scroll-wrapper" id="todo-section" role="tabpanel" aria-labelledby="todo-section">
                        <div class="add-items d-flex px-3 mb-0">

                        </div>
                        <div class="list-wrapper px-3">

                        </div>
                        <h4 class="px-3 text-muted mt-5 font-weight-light mb-0">Events</h4>
                        <div class="events pt-4 px-3">
                            <div class="wrapper d-flex mb-2">
                                <i class="ti-control-record text-primary mr-2"></i>
                                <span>Feb 11 2018</span>
                            </div>
                            <p class="mb-0 font-weight-thin text-gray">Creating component page build a js</p>
                            <p class="text-gray mb-0">The total number of sessions</p>
                        </div>
                        <div class="events pt-4 px-3">
                            <div class="wrapper d-flex mb-2">
                                <i class="ti-control-record text-primary mr-2"></i>
                                <span>Feb 7 2018</span>
                            </div>
                            <p class="mb-0 font-weight-thin text-gray">Meeting with Alisa</p>
                            <p class="text-gray mb-0 ">Call Sarah Graves</p>
                        </div>
                    </div>
                    <!-- To do section tab ends -->

                    <!-- chat tab ends -->
                </div>
            </div>
            <!-- partial -->
            <!-- partial:../../partials/_sidebar.html -->
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





                </ul>
            </nav>

            <!-- partial -->
            <div class="main-panel">

                <div class="content-wrapper">
                    <div class="row">
                        <form action="" method="GET">
                            <div class="input-group mb-3">
                                <button type="button" onclick="history.back()" class="btn btn-secondary">Back</button>
                                <input type="text" name="search" required value="<?php if (isset($_GET['search'])) {
                                                                                        echo $_GET['search'];
                                                                                    } ?>" class="form-control" placeholder="Search for a supervisor...">
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

                                            // Assume $userid is the superid from the URL parameter
                                            $superid = $userid; // Assign $superid with the value of $userid

                                            $tid = isset($_GET['tid']) ? intval($_GET['tid']) : 0;

                                            // Check if $tid is a valid integer
                                            if ($tid <= 0) {
                                                // Invalid or missing tid, handle the error or redirect to an error page
                                                echo "Invalid or missing tid";
                                                exit;
                                            }

                                            // Now, you can use $tid in your code
                                            $traineeTid = $tid;

                                            $sql = "SELECT e.*
                                                    FROM events e
                                                    INNER JOIN trainee t ON e.tid = t.tid
                                                    WHERE t.superid = $superid AND t.tid = $traineeTid";

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

                                                                    <button onclick="viewTask('<?= $row["id"] ?>')" class="btn btn-view">View</button>
                                                                </td>


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
                                                    </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>
                        </div>




                        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> 

                        <!-- content-wrapper ends -->
                        <!-- partial:../../partials/_footer.html -->
                        <footer class="footer">
                            <div class="d-sm-flex justify-content-center justify-content-sm-between">
                                <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2021. Premium <a href="https://learn.microsoft.com/en-us/credentials/browse/" target="_blank">Bootstrap admin template</a> from BootstrapDash. All rights reserved.</span>
                                <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Hand-crafted & made with <i class="ti-heart text-danger ml-1"></i></span>
                            </div>
                        </footer>
                        <!-- partial -->
                    </div>
                    <!-- main-panel ends -->
                </div>
                <!-- page-body-wrapper ends -->
            </div>
            <!-- container-scroller -->
            <!-- plugins:js -->

            <script src="../../vendors/js/vendor.bundle.base.js"></script>
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

            <script>
                function redirectToAddTask() {
                    window.location.href = "add_task.php";
                }
            </script>
            <script>
                function insertData(id) {
                    // Get the comment value from the input field
                    var commentElement = document.getElementById("comment");
                    var comment = commentElement.value;

                    // Log the comment value to the console for debugging
                    console.log("Comment:", comment);

                    // Create a new FormData object to send form data
                    var formData = new FormData();
                    formData.append("comment", comment);
                    formData.append("id", id);
                    formData.append("superid", <?= $userid ?>);
                    formData.append("timestamp", '<?= date('Y-m-d H:i:s') ?>');

                    // Create a new XMLHttpRequest object
                    var xhr = new XMLHttpRequest();

                    // Set up a POST request to the PHP script
                    xhr.open("POST", "traineetask.php", true);

                    // Set up a callback function to handle the response
                    xhr.onreadystatechange = function() {
                        if (xhr.readyState == 4 && xhr.status == 200) {
                            // Handle the response from the server (if needed)
                            console.log(xhr.responseText);
                            // Close the popup after inserting data
                            closePopup();
                        }
                    };

                    // Send the request with the form data
                    xhr.send(formData);
                }



                function openPopup() {
                    document.getElementById("overlay").style.display = "block";
                    document.getElementById("popup").style.display = "block";
                }

                function closePopup() {
                    document.getElementById("overlay").style.display = "none";
                    document.getElementById("popup").style.display = "none";
                }
            </script>





            <!-- plugins:js -->
            <script src="../vendors/js/vendor.bundle.base.js"></script>
            <!-- endinject -->
            <!-- Plugin js for this page -->
            <script src="../vendors/chart.js/Chart.min.js"></script>
            <script src="../vendors/datatables.net/jquery.dataTables.js"></script>
            <script src="../vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>
            <script src="../js/dataTables.select.min.js"></script>

            <!-- End plugin js for this page -->
            <!-- inject:js -->
            <script src="../js/off-canvas.js"></script>
            <script src="../js/hoverable-collapse.js"></script>
            <script src="../js/template.js"></script>
            <script src="../js/settings.js"></script>
            <script src="../js/todolist.js"></script>
            <!-- endinject -->
            <!-- Custom js for this page-->
            <script src="../js/dashboard.js"></script>
            <script src="../js/Chart.roundedBarCharts.js"></script>
            <!-- End custom js for this page-->
            <!-- endinject -->
            <!-- Custom js for this page-->
            <!-- End custom js for this page-->
</body>

</html>