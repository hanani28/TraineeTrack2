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

// Process the form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include("../connection.php");
    // Retrieve data from the form
    $tid = $_POST["tid"];
    $title = $_POST["title"];
    $description = $_POST["description"];
    $start_date = $_POST["start_date"];
    $end_date = $_POST["end_date"];

    // Additional validation and sanitation may be needed here.

    // Insert event data into the 'events' table
    $sql = "INSERT INTO events (title, description, start_date, end_date, tid, created) VALUES (?, ?, ?, ?, ?, NOW())";

    $stmt = $database->prepare($sql);
    $stmt->bind_param("ssssi", $title, $description, $start_date, $end_date, $tid);

    if ($stmt->execute()) {
        // Event insertion successful
        // Redirect to task.php
        header("Location: task.php");
        exit; 
        // Make sure to exit after sending the header to prevent further script execution.
    } else {
        // Event insertion failed
        echo "Error creating event: " . $stmt->error;
    }

    $stmt->close();
    $database->close();
}
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
                            <input type="text" class="form-control" id="navbar-search-input" placeholder="Search now" aria-label="search" aria-describedby="search">
                        </div>
                    </li>
                </ul>
                <ul class="navbar-nav navbar-nav-right">
                    <li class="nav-item dropdown">

                        </a> -->
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
            </div>
        </div>
       
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


        <div class="main-panel">
            <div class="content-wrapper">
                <div class="row">


                    <div class="main-panel">
                        <div class="content-wrapper">
                            <div class="row">
                                <div class="col-12 grid-margin stretch-card">
                                    <div class="card">
                                        <div class="card-body">
                                            <h4 class="card-title">Add Task</h4>


                                            <form action="add_task.php" method="POST">
                                            <input type="hidden" name="tid" value="<?php echo $userid; ?>">


                                                <div class="row">
                                                    <div class="col-md-12 col-12">
                                                        <div class="form-group has-icon-left">
                                                            <label for="first-name-icon"> Title</label>
                                                            <div class="position-relative">
                                                                <input type="text" class="form-control"  name="title">
                                                                <div class="form-control-icon">
                                                                    <i class="fa fa-user"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>





                                                    <div class="col-md-12 col-12">
                                                        <div class="form-group has-icon-left">
                                                            <label for="first-name-icon">Description</label>
                                                            <div class="position-relative">
                                                                <input type="text" class="form-control"  name="description">
                                                                <div class="form-control-icon">
                                                                    <i class="fa fa-envelope"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div class="col-md-5 col-12">
                                                        <div class="form-group has-icon-left">
                                                            <label for="first-name-icon">Start Date</label>
                                                            <div class="position-relative">
                                                                <input type="date" class="form-control" name="start_date">
                                                                <div class="form-control-icon">
                                                                    <i class="fa fa-user"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-5 col-12">
                                                        <div class="form-group has-icon-left">
                                                            <label for="first-name-icon">End Date</label>
                                                            <div class="position-relative">
                                                                <input type="date" class="form-control" name="end_date">
                                                                <div class="form-control-icon">
                                                                    <i class="fa fa-user"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    

                                                    <!-- Your form fields here -->



                                                    <div class="col-12 d-flex justify-content-end">
                                                        <button type="submit" value="Create Task class="btn btn-primary me-1 mb-1">Submit</button>
                                                    </div>
                                            </form>


                                        </div>
                                        </form>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
    </div>



    <!-- content-wrapper ends -->
    <!-- partial:../../partials/_footer.html -->
    <footer class="footer">
        <div class="d-sm-flex justify-content-center justify-content-sm-between">
            <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2021. Premium <a href="https://www.bootstrapdash.com/" target="_blank">Bootstrap admin template</a> from BootstrapDash. All rights reserved.</span>
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
    <script src="../vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <script src="../vendors/typeahead.js/typeahead.bundle.min.js"></script>
    <script src="../vendors/select2/select2.min.js"></script>
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="../js/off-canvas.js"></script>
    <script src="../js/hoverable-collapse.js"></script>
    <script src="../js/template.js"></script>
    <script src="../js/settings.js"></script>
    <script src="../js/todolist.js"></script>
    <!-- endinject -->
    <!-- Custom js for this page-->
    <script src="../js/file-upload.js"></script>
    <script src="../js/typeahead.js"></script>
    <script src="../js/select2.js"></script>
    <!-- End custom js for this page-->
</body>

</html>