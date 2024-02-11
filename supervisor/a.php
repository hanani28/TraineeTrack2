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
    body {
        font-family: Arial, sans-serif;
    }

    #popup {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: #fff;
        padding: 20px;
        border: 1px solid #ccc;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        z-index: 1000;
    }

    #overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 999;
    }
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
                        <a class="nav-link" href="task.php">
                            <i class="icon-paper menu-icon"></i>
                            <span class="menu-title">Task</span>
                        </a>
                    </li>


                    <li class="nav-item">
                        <a class="nav-link" href="cal/index.php">
                            <i class="icon-user menu-icon"></i>
                            <span class="menu-title">Calender</span>
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

                                            // Update the SQL query to retrieve events for the specific trainee (tid) under the supervisor (superid)
                                            // Update the SQL query to retrieve events for the specific trainee (tid)
                                            // Update the SQL query to retrieve events for trainees under the supervisor (superid)
                                            $sql = "SELECT e.*
        FROM events e
        INNER JOIN trainee t ON e.tid = t.tid
        WHERE t.superid = $userid";
                                            $result = $database->query($sql);

                                            function handleDatabaseError($database, $error)
                                            {
                                                echo "Error: " . $error . "<br>" . $database->error;
                                            }



                                            // Check if the form is submitted and if "id" is set in the $_POST array
                                            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id"])) {
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
                                                // Handle the case when the form is not submitted or "id" is not set
                                                echo "Error: Form not submitted or 'id' not set";
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
                                                                    <input type="checkbox" onclick="updateTaskStatus(this, <?= $row["id"] ?>)" <?= $isChecked ?>>
                                                                </td>



                                                                <td>

                                                                    <!-- <div style="display:flex;justify-content: center;"> -->
                                                                    <button class="btn btn-outline-success btn-fw btn-icon-text" onclick="openPopup('commentPopup_<?= $row["id"] ?>')">Add Comment</button>

                                                                    <!-- Hidden pop-up form -->
                                                                    <div id="commentPopup_<?= $row["id"] ?>" class="popup">
                                                                        <form method="post" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>">
                                                                            <input type="hidden" name="id" value="<?= $row["id"] ?>">
                                                                            <input type="hidden" name="superid" value="<?= $userid ?>">
                                                                            <input type="hidden" name="timestamp" value="<?= date('Y-m-d H:i:s') ?>">
                                                                            <label for="comment">Comment:</label>
                                                                            <input type="text" name="comment" value="<?= $row["comment"] ?>">
                                                                            <br>
                                                                            <input type="submit" value="Submit Comment">
                                                                        </form>
                                                                        <button onclick="closePopup('commentPopup_<?= $row["id"] ?>')">Close</button>
                                                                    </div>
                                                                    </form>


                                                </div>
                                                <script src="insertData.js"></script>
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

                    // Create a new XMLHttpRequest object
                    var xhr = new XMLHttpRequest();

                    // Set up a POST request to the PHP script
                    xhr.open("POST", "traineetask.php", true);
                    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

                    // Define the data to be sent in the request body
                    var data = "comment=" + encodeURIComponent(comment) + "&id=" + id;

                    // Set up a callback function to handle the response
                    xhr.onreadystatechange = function() {
                        if (xhr.readyState == 4 && xhr.status == 200) {
                            // Handle the response from the server (if needed)
                            console.log(xhr.responseText);
                            // Close the popup after inserting data
                            closePopup();
                        }
                    };

                    // Send the request with the data
                    xhr.send(data);
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