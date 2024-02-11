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
// Import the database and other necessary files here (e.g., connection.php)
include("../connection.php");
session_start();

if (isset($_SESSION["user"])) {
    if ($_SESSION["user"] == "" or $_SESSION['usertype'] != 'a') {
        header("location: ../login.php");
        exit(); // Make sure to exit after calling header()
    }
} else {
    header("location: ../login.php");
    exit(); // Make sure to exit after calling header()
}

$trainee = array(); // Initialize the $trainee array

// Check if the 'tid' parameter is set in the URL
if (isset($_GET["tid"])) {
    $tid = $_GET["tid"];

    // Query to retrieve trainee information based on the 'tid'
    $query = "SELECT t.tid, t.name AS trainee_name, t.temail, t.deptid, d.namedept, 
                t.superid, s.name AS supervisor_name, t.username, t.tpassword,
                t.phone_num, t.startdate, t.endate, t.courseofstudy, t.gender, t.status, t.uni, t.image_data, t.specialCaseExplanation
                FROM trainee AS t
                JOIN department AS d ON t.deptid = d.deptid
                JOIN supervisor AS s ON t.superid = s.superid
                WHERE t.tid = $tid";


    $result = $database->query($query);

    if ($result) {
        if (mysqli_num_rows($result) == 1) {
            $trainee = mysqli_fetch_assoc($result);
        }
    }
}

function updateTrainee($database, $tid, $newData)
{
    // Prepare the SQL query to update trainee information
    $updateQuery = "UPDATE trainee SET 
    name = '{$newData['name']}',
    temail = '{$newData['temail']}',
    deptid = {$newData['deptid']},
    supername = {$newData['supername']},
    username = '{$newData['username']}',
    tpassword = '{$newData['tpassword']}',
    phone_num = '{$newData['phone_num']}',
    startdate = '{$newData['startdate']}',
    endate = '{$newData['endate']}',
    courseofstudy = '{$newData['courseofstudy']}',
    gender = '{$newData['gender']}',
    status = {$newData['status']},
    uni = '{$newData['uni']}',
    specialCaseExplanation = '{$newData['specialCaseExplanation']}'";




    if (!empty($_FILES['new_image']['tmp_name']) && $_FILES['new_image']['error'] === 0) {
        // Read the new image file
        $newImageData = file_get_contents($_FILES['new_image']['tmp_name']);

        // Escape the binary data for database insertion (using mysqli_real_escape_string)
        $image_data = mysqli_real_escape_string($database, $newImageData);

        // Add the image_data field to the query
        $updateQuery .= ", image_data = '{$image_data}'";
    }

    // Complete the WHERE clause of the query
    $updateQuery .= " WHERE tid = $tid";

    // Execute the update query
    $result = $database->query($updateQuery);

    if ($result) {
        return true; // Update successful
    } else {
        // Handle the update error (e.g., display an error message)
        return "Update failed: " . $database->error;
    }
}

// Handle form submission (updating trainee information)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tid = $_POST["tid"];

    // Gather the new trainee data from the form
    $newData = array(
        "name" => isset($_POST["name"]) ? $_POST["name"] : '',
        "temail" => isset($_POST["temail"]) ? $_POST["temail"] : '',
        "deptid" => isset($_POST["deptid"]) ? $_POST["deptid"] : 0,
        "superid" => isset($_POST["superid"]) ? $_POST["superid"] : 0,
        "username" => isset($_POST["username"]) ? $_POST["username"] : '',
        "tpassword" => isset($_POST["tpassword"]) ? $_POST["tpassword"] : '',
        "phone_num" => isset($_POST["phone_num"]) ? $_POST["phone_num"] : '',
        "startdate" => isset($_POST["startdate"]) ? $_POST["startdate"] : '',
        "endate" => isset($_POST["endate"]) ? $_POST["endate"] : '',
        "courseofstudy" => isset($_POST["courseofstudy"]) ? $_POST["courseofstudy"] : '',
        "gender" => isset($_POST["gender"]) ? $_POST["gender"] : '',
        "status" => isset($_POST["status"]) ? $_POST["status"] : 0,
        "uni" => isset($_POST["uni"]) ? $_POST["uni"] : '',
        "image_data" => isset($_POST["image_data"]) ? $_POST["image_data"] : '',
        "specialCaseExplanation" => isset($_POST["special_case_explanation"]) ? $_POST["special_case_explanation"] : ''
    );


    echo '<pre>';
    print_r($_POST);
    print_r($_GET);
    echo '</pre>';


    // Call the updateTrainee function to update trainee information
    $updateResult = updateTrainee($database, $tid, $newData);

    if ($updateResult === true) {
        // Redirect to view_trainee.php with the updated trainee ID
        header("Location: view_trainee.php?tid=" . $tid);
        exit();
    } else {
        // Handle the update error (e.g., display an error message)
        echo $updateResult;
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
                    <a class="nav-link" href="supervisor.php">
                        <i class="icon-paper menu-icon"></i>
                        <span class="menu-title">Supervisor</span>
                    </a>
                </li>


                <li class="nav-item">
                    <a class="nav-link" href="trainee.php">
                        <i class="icon-paper menu-icon"></i>
                        <span class="menu-title">Trainee</span>
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


        <div class="main-panel">
            <div class="content-wrapper">
                <div class="row">
                    <div class="main-panel">
                        <div class="content-wrapper">
                            <div class="row">
                                <div class="col-12 grid-margin stretch-card">
                                    <div class="card">
                                        <div class="card-body">
                                            <h4 class="card-title">Update Trainee</h4>

                                            <!-- Basic multiple Column Form section start -->
                                            <section id="single-column-form">
                                                <div class="row match-height">
                                                    <div class="col-12">
                                                        <div class="card">
                                                            <div class="card-content">
                                                                <div class="card-body">



                                                                    <form method="post" action="update_trainee.php" enctype="multipart/form-data">
                                                                        <input type="hidden" name="tid" value="<?php echo $tid; ?>">

                                                                        <div class="col-md-8 col-12">
                                                                            <div class="form-group has-icon-left">
                                                                                <label for="first-name-icon">Upload new Image</label>
                                                                                <div class="position-relative">
                                                                                    <input type="file" class="form-control" id=new_image placeholder="Image" name="new_image">
                                                                                    <div class="form-control-icon">
                                                                                        <i class="fa fa-envelope"></i>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <!-- Trainee Name -->
                                                                        <div class="col-md-8 col-12">
                                                                            <div class="form-group has-icon-left">
                                                                                <label for="first-name-icon">Name</label>
                                                                                <div class="position-relative">
                                                                                    <input type="text" class=form-control id="name" name="name" value="<?php echo $trainee['trainee_name']; ?>">
                                                                                    <div class="form-control-icon">
                                                                                        <i class="fa fa-envelope"></i>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>


                                                                        <!-- Trainee Email -->

                                                                        <div class="col-md-8 col-12">
                                                                            <div class="form-group has-icon-left">
                                                                                <label for="first-name-icon">Email</label>
                                                                                <div class="position-relative">
                                                                                    <input type="email" class="form-control" id="temail" name="temail" value="<?php echo $trainee['temail']; ?>"><br>
                                                                                    <div class="form-control-icon">
                                                                                        <i class="fa fa-envelope"></i>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>



                                                                        <div class="col-md-8 col-12">
                                                                            <div class="form-group has-icon-left">
                                                                                <label for="first-name-icon">Username</label>
                                                                                <div class="position-relative">
                                                                                    <input type="text" class="form-control" id="username" name="username" value="<?php echo $trainee['username']; ?>">
                                                                                    <div class="form-control-icon">
                                                                                        <i class="fa fa-envelope"></i>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>


                                                                        <!-- Phone Number -->
                                                                        <div class="col-md-8 col-12">
                                                                            <div class="form-group has-icon-left">
                                                                                <label for="first-name-icon">Phone Number</label>
                                                                                <div class="position-relative">
                                                                                    <input type="tel" class="form-control" id="phone_num" name="phone_num" value="<?php echo $trainee['phone_num']; ?>"><br>
                                                                                    <div class="form-control-icon">
                                                                                        <i class="fa fa-envelope"></i>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>


                                                                        <div class="col-md-8 col-12">
                                                                            <label for="gender">Gender:</label>
                                                                            <select type="text" id="gender" class="form-control" name="gender" value="<?php echo $trainee['gender']; ?>">
                                                                                <option value="Male" <?php if ($trainee['gender'] == 'Male') echo 'selected'; ?>>Male</option>
                                                                                <option value="Female" <?php if ($trainee['gender'] == 'Female') echo 'selected'; ?>>Female</option>
                                                                                <option value="Other" <?php if ($trainee['gender'] == 'Other') echo 'selected'; ?>>Other</option>
                                                                            </select>
                                                                        </div>


                                                                        <div class="col-md-8 col-12">
                                                                            <div class="form-group has-icon-left">
                                                                                <label for="first-name-icon">Institute</label>
                                                                                <div class="position-relative">
                                                                                    <input type="text" class="form-control" id="uni" name="uni" value="<?php echo $trainee['uni']; ?>"><br>
                                                                                    <div class="form-control-icon">
                                                                                        <i class="fa fa-envelope"></i>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>


                                                                        <div class="col-md-8 col-12">
                                                                            <div class="form-group has-icon-left">
                                                                                <label for="first-name-icon">Programme</label>
                                                                                <div class="position-relative">
                                                                                    <input type="text" class="form-control" id="courseofstudy" name="courseofstudy" value="<?php echo $trainee['courseofstudy']; ?>"><br>
                                                                                    <div class="form-control-icon">
                                                                                        <i class="fa fa-envelope"></i>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-md-8 col-12">
                                                                            <div class="form-group has-icon-left">
                                                                                <label for="first-name-icon">Department</label>
                                                                                <div class="position-relative">
                                                                                    <input type="text" class="form-control" id="deptid" name="deptid" value="<?php echo $trainee['deptid']; ?>"><br>
                                                                                    <div class="form-control-icon">
                                                                                        <i class="fa fa-envelope"></i>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-md-8 col-12">
                                                                            <div class="form-group has-icon-left">
                                                                                <label for="first-name-icon">Supervisor ID</label>
                                                                                <div class="position-relative">
                                                                                    <input type="text" class="form-control" id="superid" name="superid" value="<?php echo $trainee['superid']; ?>"><br>
                                                                                    <div class="form-control-icon">
                                                                                        <i class="fa fa-envelope"></i>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-md-8 col-12">
                                                                            <div class="form-group has-icon-left">
                                                                                <label for="first-name-icon">Password</label>
                                                                                <div class="position-relative">
                                                                                    <input type="password" class="form-control" id="tpassword" name="tpassword" value="<?php echo $trainee['tpassword']; ?>" readonly><br>
                                                                                    <div class="form-control-icon">
                                                                                        <i class="fa fa-envelope"></i>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-md-8 col-12">
                                                                            <div class="form-group has-icon-left">
                                                                                <label for="first-name-icon">Start Date</label>
                                                                                <div class="position-relative">
                                                                                    <input type="date" id="startdate" class="form-control" name="startdate" value="<?php echo $trainee['startdate']; ?>"><br>
                                                                                    <div class="form-control-icon">
                                                                                        <i class="fa fa-envelope"></i>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-md-8 col-12">
                                                                            <div class="form-group has-icon-left">
                                                                                <label for="first-name-icon">End Date</label>
                                                                                <div class="position-relative">
                                                                                    <input type="date" class="form-control" id="endate" name="endate" value="<?php echo $trainee['endate']; ?>"><br>
                                                                                    <div class="form-control-icon">
                                                                                        <i class="fa fa-envelope"></i>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>




                                                                        <div class="col-md-8 col-12">
                                                                            <div class="form-group has-icon-left">
                                                                                <label for="first-name-icon">Status</label>
                                                                                <div class="position-relative">
                                                                                    <select name="status" id="status" class="form-control">
                                                                                        <option value="1" <?php if ($trainee['status'] == 1) echo 'selected'; ?>>Active</option>
                                                                                        <option value="2" <?php if ($trainee['status'] == 2) echo 'selected'; ?>>Inactive</option>
                                                                                        <option value="3" <?php if ($trainee['status'] == 3) echo 'selected'; ?>>Special Case</option>
                                                                                    </select>
                                                                                    <br>
                                                                                    <div class="form-control-icon">
                                                                                        <i class="fa fa-envelope"></i>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-md-8 col-12" id="specialCaseExplanationSection" style="display: none;">


                                                                            <div class="form-group">
                                                                                <div class="col-md-8 col-12">
                                                                                    <div class="form-group">
                                                                                        <label for="specialCaseExplanationSection">Special Case Type:</label><br>
                                                                                        <input type="radio" name="specialCaseType" value="Health Issue" <?php
                                                                                                                                                        if (isset($trainee['specialCaseExplanation']) && $trainee['specialCaseExplanation'] == 'Health Issue') echo 'checked';
                                                                                                                                                        ?>> Health Issue
                                                                                        <label>
                                                                                            <input type="radio" name="specialCaseType" value="Dropout" <?php
                                                                                                                                                        if (isset($trainee['specialCaseExplanation']) && $trainee['specialCaseExplanation'] == 'Dropout') echo 'checked';
                                                                                                                                                        ?>> Dropout
                                                                                        </label>
                                                                                        <label>
                                                                                            <input type="radio" name="specialCaseType" value="Fired" <?php
                                                                                                                                                        if (isset($trainee['specialCaseExplanation']) && $trainee['specialCaseExplanation'] == 'Fired') echo 'checked';
                                                                                                                                                        ?>> Fired
                                                                                        </label>
                                                                                        <label>
                                                                                            <input type="radio" name="specialCaseType" value="Others" <?php
                                                                                                                                                        if (isset($trainee['specialCaseExplanation']) && $trainee['specialCaseExplanation'] == 'Others') echo 'checked';
                                                                                                                                                        ?>> Others
                                                                                        </label>

                                                                                    </div>
                                                                                </div>

                                                                            </div>
                                                                        </div>


                                                                        <div class="col-md-8 col-12">
                                                                            <div class="form-group has-icon-left">
                                                                                <button type="submit" class="btn btn-primary" name="updateButton">Update Trainee</button>
                                                                            </div>
                                                                        </div>
                                                                    </form>

                                                                    <script>
                                                                        // JavaScript to show/hide the special case explanation textarea based on selected status
                                                                        document.getElementById("status").addEventListener("change", function() {
                                                                            var statusSelect = document.getElementById("status");
                                                                            var explanationSection = document.getElementById("specialCaseExplanationSection");
                                                                            var selectedStatus = statusSelect.value;

                                                                            if (selectedStatus === "3") {
                                                                                explanationSection.style.display = "block";
                                                                            } else {
                                                                                explanationSection.style.display = "none";
                                                                            }
                                                                        });
                                                                    </script>



                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </section>



                                            <!-- // Basic multiple Column Form section end -->
                                        </div>

                                    </div>
                                </div>
                                <script src="assets/js/feather-icons/feather.min.js"></script>
                                <script src="assets/vendors/perfect-scrollbar/perfect-scrollbar.min.js"></script>
                                <script src="assets/js/app.js"></script>

                                <script src="assets/js/main.js"></script>
                                <script>
                                    // JavaScript to load supervisors based on department selection
                                    document.getElementById("deptid").addEventListener("change", function() {
                                        var deptid = this.value;
                                        var supervisorDiv = document.getElementById("supervisor_div");
                                        var supervisorSelect = document.getElementById("superid");

                                        // Clear the current supervisor options
                                        supervisorSelect.innerHTML = "";

                                        // Fetch supervisors based on the selected department using AJAX
                                        var xhr = new XMLHttpRequest();
                                        xhr.open("GET", "get_supervisor.php?deptid=" + deptid, true);

                                        xhr.onload = function() {
                                            if (xhr.status === 200) {
                                                var supervisors = JSON.parse(xhr.responseText);
                                                supervisors.forEach(function(supervisor) {
                                                    var option = document.createElement("option");
                                                    option.value = supervisor.superid;
                                                    option.textContent = supervisor.name;
                                                    supervisorSelect.appendChild(option);
                                                });
                                            }
                                        };

                                        xhr.send();
                                    });
                                </script>
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