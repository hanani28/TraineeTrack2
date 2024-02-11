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

include("../connection.php"); // Make sure to include the file that establishes the database connection

$userrow = $database->query("select * from trainee where temail='$useremail'");

if (!$userrow) {
    die("Database error: " . $database->error);
}

$userfetch = $userrow->fetch_assoc();
$userid = $userfetch["tid"];
$username = $userfetch["name"];
?>
<?php
if (isset($_POST['event_id'])) {
    $event_id = $_POST['event_id'];

    // Process subtask submissions if needed
    if (isset($_POST['subtask']) && isset($_POST['status'])) {
        $subtasks = $_POST['subtask'];
        $numSubtasks = count($subtasks); // Get the number of subtasks

        for ($i = 0; $i < $numSubtasks; $i++) {
            $subtask = $subtasks[$i];

            if (!empty($subtask)) {
                $query = "INSERT INTO minitask (subtask, status, id) VALUES ('$subtask', 0, $event_id)";
                if (mysqli_query($database, $query)) {
                    echo "Subtask added successfully!";
                } else {
                    echo "Error: " . mysqli_error($database);
                }
            } else {
                echo "Please fill out the subtask for subtask " . ($i + 1) . ".";
            }
        }
    }
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





                    </ul>
                </nav>

                <div class="content-wrapper">
                    <div class="row">
                    <button type="button" onclick="history.back()" class="btn btn-outline-success btn-fw btn-icon-text">Back</button>
                        <div class="col-lg-12 stretch-card">
                            
                            <div class="card">


                                <div class="card-body">


                                    <form method="post" action="">


                                        <input type="hidden" name="event_id" value="<?php echo $event_id; ?>">
                                        <div id="subtask-container">
                                            <div class="subtask-row">
                                                <div class="col-md-10 col-12">
                                                    <div class="form-group has-icon-left">
                                                        <label for="subtask">Subtask</label>
                                                        <div class="position-relative">
                                                            <input class="form-control" type="text" name="subtask[]" id="subtask" required>
                                                            <div class="form-control-icon">
                                                                <i class="fa fa-user"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <input type="hidden" name="status[]" value="1">
                                                <button type="button" class="btn btn-danger" onclick="removeSubtask(this)">Remove</button>
                                            </div>
                                        </div>
                                        <tr>
                                            <div class="col-md-12 col-12">
                                                <button type="button" class="btn btn-inverse-primary btn-fw" onclick="addSubtask()">Add Subtask</button>

                                                <input type="submit" class="btn btn-inverse-warning btn-fw" name="submit" value="Submit" onclick="showSuccessMessage()">
                                            </div>
                                    </form>
                                    <div id="success-message" style="display: Success;"></div> <!-- Success message div -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                    function addSubtask() {
                        const subtaskContainer = document.getElementById('subtask-container');
                        const subtaskRow = document.querySelector('.subtask-row').cloneNode(true);

                        // Clear the input field in the cloned row
                        subtaskRow.querySelector('input[name="subtask[]"]').value = '';

                        subtaskContainer.appendChild(subtaskRow);

                        // Reset the success message
                        const successMessage = document.getElementById('success-message');
                        successMessage.style.display = 'none';
                    }



                    function removeSubtask(button) {
                        const subtaskContainer = document.getElementById('subtask-container');
                        const subtaskRow = button.parentNode; // Use parentNode to get the parent of the button

                        if (subtaskContainer.contains(subtaskRow) && subtaskContainer.childElementCount > 1) {
                            // Check if the row is inside the container and there's more than one row
                            subtaskContainer.removeChild(subtaskRow);
                        }

                        // Reset the success message
                        const successMessage = document.getElementById('success-message');
                        successMessage.style.display = 'none';
                    }



                    function showSuccessMessage() {
                        const successMessage = document.getElementById('success-message');
                        successMessage.innerHTML = 'Subtask added successfully!';
                        successMessage.style.display = 'block';
                    }
                </script>




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
    </body>

</html>