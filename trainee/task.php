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

<?php

//learn from w3schools.com

session_start();



if (isset($_SESSION["user"])) {
    if (($_SESSION["user"]) == "" or $_SESSION['usertype'] != 't') {
        header("location: ../login.php");
    } else {
        $useremail = $_SESSION["user"];
    }
} else {
    header("location: ../login.php");
}


//import database
include("../connection.php");
$userrow = $database->query("select * from trainee where temail='$useremail'");

if (!$userrow) {
    die("Database error: " . $database->error);
}

$userfetch = $userrow->fetch_assoc();

$userid = $userfetch["tid"];
$username = $userfetch["name"];


//   echo $ ;
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
                <ul class="navbar-nav navbar-nav-right">
                    <li class="nav-item dropdown">
                        <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#" data-toggle="dropdown">
                            <i class="icon-bell mx-0"></i>
                            <span class="count"></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="notificationDropdown">
                            <p class="mb-0 font-weight-normal float-left dropdown-header">Notifications</p>
                            <a class="dropdown-item preview-item">
                                <div class="preview-thumbnail">
                                    <div class="preview-icon bg-success">
                                        <i class="ti-info-alt mx-0"></i>
                                    </div>
                                </div>
                                <div class="preview-item-content">
                                    <h6 class="preview-subject font-weight-normal">Application Error</h6>
                                    <p class="font-weight-light small-text mb-0 text-muted">
                                        Just now
                                    </p>
                                </div>
                            </a>
                            <a class="dropdown-item preview-item">

                            </a>
                            <a class="dropdown-item preview-item">
                                <div class="preview-thumbnail">
                                    <div class="preview-icon bg-info">
                                        <i class="ti-user mx-0"></i>
                                    </div>
                                </div>
                                <div class="preview-item-content">

                                </div>
                            </a>
                        </div>
                    </li>
                    <li class="nav-item nav-profile dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
                            <img src="../../images/faces/face28.jpg" alt="profile" />
                        </a>

                    </li>
                    <li class="nav-item nav-settings d-none d-lg-flex">

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


                </div>
            </div>
            <div id="right-sidebar" class="settings-panel">
                <i class="settings-close ti-close"></i>
                <ul class="nav nav-tabs border-top" id="setting-panel" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="todo-tab" data-toggle="tab" href="#todo-section" role="tab" aria-controls="todo-section" aria-expanded="true">TO DO LIST</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="chats-tab" data-toggle="tab" href="#chats-section" role="tab" aria-controls="chats-section">CHATS</a>
                    </li>
                </ul>
                <div class="tab-content" id="setting-content">
                    <div class="tab-pane fade show active scroll-wrapper" id="todo-section" role="tabpanel" aria-labelledby="todo-section">
                        <div class="add-items d-flex px-3 mb-0">

                        </div>

                        <h4 class="px-3 text-muted mt-5 font-weight-light mb-0">Events</h4>


                    </div>
                    <!-- To do section tab ends -->
                    <div class="tab-pane fade" id="chats-section" role="tabpanel" aria-labelledby="chats-section">


                    </div>
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
                    <li class="nav-item">
                        <a class="nav-link" href="../logout.php">
                            <i class="icon-paper menu-icon"></i>
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
                                <button type="button" onclick="history.back()" class="btn btn-outline-success btn-fw btn-icon-text">Back</button>
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
                                    <h4 class="card-title">List Of Task :></h4>
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
                                            $userid = $userfetch["tid"];


                                            $sql = "SELECT * FROM events WHERE tid = $userid";
                                            $result = $database->query($sql);



                                            ?>





                                            <div class="text-right mt-3">
                                                <button class="btn btn-outline-success btn-fw btn-icon-text" onclick="redirectToAddTask()">Add Task</button>
                                            </div>

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
                                                                <td> <?= $row['status'] == 1 ? 'Completed' : 'Pending'; ?></td>



                                                                <td>

                                                                    <div style="display:flex;justify-content: center;">
                                                                        <form action="viewtask.php" method="post">
                                                                            <input type="hidden" name="event_id" value="<?= $row["id"] ?>">
                                                                            <button type="submit" class="btn btn-outline-info btn-fw btn-icon-text">View</button>
                                                                        </form>

                                                                        <!-- Add Subtask button with a form to submit the event ID to add_subtask.php -->
                                                                        <form action="subtask.php" method="post">
                                                                            <input type="hidden" name="event_id" value="<?= $row["id"] ?>">
                                                                            <button type="submit" class="btn btn-outline-warning btn-fw btn-icon-text">Add Subtask</button>
                                                                        </form>
                                                                       
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
            <!-- container-scroller -->
            <!-- plugins:js -->
            <script src="../../vendors/js/vendor.bundle.base.js"></script>

            <script>
                function redirectToAddTask() {
                    window.location.href = "add_task.php";
                }
            </script>
            <script>
                function updateTaskStatus(checkbox, taskId) {
                    const status = checkbox.checked ? 1 : 0;

                    // Send an AJAX request to update the task status
                    // You can use a library like jQuery or fetch for this purpose
                    // Example using fetch:
                    fetch("update_status.php", {
                            method: "POST",
                            body: JSON.stringify({
                                taskId,
                                status
                            }),
                            headers: {
                                "Content-Type": "application/json"
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                console.log("Status updated successfully.");
                            } else {
                                console.error("Status update failed.");
                            }
                        })
                        .catch(error => {
                            console.error("Error:", error);
                        });
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