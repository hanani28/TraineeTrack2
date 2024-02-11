<?php
// Include your database connection code here
include('../connection.php');

// Start or resume a session
session_start();

// Fetch the existing department details for editing
$editDeptID = $_GET['deptid'];

// Initialize variables for success message and error message
$successMessage = '';
$errorMessage = '';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get values from the POST request
    $deptId = $_POST['deptId'];
    $updatedDeptnumber = $_POST['updatedDeptnumber'];
    $updatedNameDept = $_POST['updatedNameDept'];
    $updatedSubId = $_POST['updatedSubId'];
    $updatedStatus = $_POST['status']; // Retrieve status from the form

    // Check if the updated deptnumber is unique (excluding the current department being edited)
    $checkUniqueQuery = "SELECT deptid FROM department WHERE deptnumber = '$updatedDeptnumber' AND deptid != '$deptId'";
    $result = mysqli_query($database, $checkUniqueQuery);

    if ($result && mysqli_num_rows($result) > 0) {
        // Deptnumber is not unique
        $errorMessage = 'Error: Department number must be unique.';
    } else {
        // Perform the database update using mysqli
        $updateQuery = "UPDATE department 
                        SET deptnumber = '$updatedDeptnumber', namedept = '$updatedNameDept', subid = '$updatedSubId', status = '$updatedStatus' 
                        WHERE deptid = '$deptId'";

        if (mysqli_query($database, $updateQuery)) {
            // Update successful
            $successMessage = 'Department updated successfully.';
        } else {
            // Update failed
            $errorMessage = 'Error updating department: ' . mysqli_error($database);
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Retrieve the existing department details from the database using mysqli
    $selectDeptQuery = "SELECT * FROM department WHERE deptid='$editDeptID'";
    $result = mysqli_query($database, $selectDeptQuery);

    // Check if the department ID is valid
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        // Assign values to variables for pre-populating the form
        $existingDeptnumber = $row['deptnumber'];
        $existingNamedept = $row['namedept'];
        $existingSubid = $row['subid'];
        $existingStatus = $row['status']; // Retrieve status from the database

        // Check if there are related records in supervisor or trainee tables
        $relatedRecordsQuery = "SELECT COUNT(*) as count FROM supervisor WHERE deptid='$editDeptID' UNION SELECT COUNT(*) FROM trainee WHERE deptid='$editDeptID'";
        $relatedRecordsResult = mysqli_query($database, $relatedRecordsQuery);
        $relatedRecords = mysqli_fetch_assoc($relatedRecordsResult);

        // Store form values and related records count in session
        $_SESSION['formValues'] = [
            'updatedDeptnumber' => $existingDeptnumber,
            'updatedNameDept' => $existingNamedept,
            'updatedSubId' => $existingSubid,
            'updatedStatus' => $existingStatus,
            'relatedSupervisorRecords' => $relatedRecords['count'],
        ];
    } else {
        // Handle the case where the department ID is not valid
        echo "Invalid department ID.";
        exit();
    }
} else {
    // Invalid request method
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Trainee Track</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="../vendors/feather/feather.css">
    <link rel="stylesheet" href="../vendors/ti-icons/css/themify-icons.css">

    <link rel="stylesheet" href="../vendors/css/vendor.bundle.base.css">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="../vendors/datatables.net-bs4/dataTables.bootstrap4.css">
    <link rel="stylesheet" href="../vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" type="../text/css" href="../js/select.dataTables.min.css">
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="../css/vertical-layout-light/style.css">
    <!-- <link rel="stylesheet" href="../../css/vertical-layout-light/style.css"> -->
    <!-- endinject -->
    <link rel="shortcut icon" href="images/favicon.png" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Edit Department Form</title>

    <script>
        // Check if subid is modified and not empty
        var updatedSubId = "<?php echo isset($existingSubid) ? addslashes($existingSubid) : ''; ?>";
        var originalSubId = "<?php echo isset($existingSubid) ? addslashes($existingSubid) : ''; ?>";

        if (updatedSubId && updatedSubId !== originalSubId) {
            // Check uniqueness directly in PHP
            <?php
            $checkUniqueQuery = "SELECT deptid FROM department WHERE subid = '$updatedSubId' AND deptid != '$editDeptID'";
            $result = mysqli_query($database, $checkUniqueQuery);

            if ($result && mysqli_num_rows($result) > 0) {
                echo 'alert("Error: Subsidiary ID must be unique.");';
                echo 'window.location.href = "edit_department.php?deptid=' . $editDeptID . '";'; // Redirect to the same page
            }
            ?>
        }
    </script>


</head>

<body>
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
                    <li class="nav-item nav-search d-none d-lg-block">
                        <div class="input-group">
                            <div class="input-group-prepend hover-cursor" id="navbar-search-icon">
                                <span class="input-group-text" id="search">
                                    <!-- <i class="icon-search"></i> -->
                                </span>
                            </div>
                            <!-- <input type="text" class="form-control" id="navbar-search-input" placeholder="Search now" aria-label="search" aria-describedby="search"> -->
                        </div>
                    </li>
                </ul>
                <!-- Inside the navbar, after the search input -->
                <ul class="navbar-nav ml-auto">
                    <!-- Add a nav-item for the profile dropdown -->

                </ul>


                </ul>

            </div>
        </nav>
        <!-- partial -->
        <div class="container-fluid page-body-wrapper">

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
                                        <label class="form-check-label">
                                            <input class="checkbox" type="checkbox">
                                            Team review meeting at 3.00 PM
                                        </label>
                                    </div>
                                    <i class="remove ti-close"></i>
                                </li>
                                <li>
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input class="checkbox" type="checkbox">
                                            Prepare for presentation
                                        </label>
                                    </div>
                                    <i class="remove ti-close"></i>
                                </li>
                                <li>
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input class="checkbox" type="checkbox">
                                            Resolve all the low priority tickets due today
                                        </label>
                                    </div>
                                    <i class="remove ti-close"></i>
                                </li>
                                <li class="completed">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input class="checkbox" type="checkbox" checked>
                                            Schedule meeting for next week
                                        </label>
                                    </div>
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
                        <a class="nav-link" href="supervisor.php">
                            <i class="icon-columns menu-icon"></i>
                            <span class="menu-title">Supervisor</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="trainee.php">
                            <i class="icon-columns menu-icon"></i>
                            <span class="menu-title">Trainee</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="formdisplay.php">
                            <i class="icon-columns menu-icon"></i>
                            <span class="menu-title">Manage Form</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="../logout.php">
                            <i class="icon-head menu-icon"></i>
                            <span class="menu-title">LogOut</span>
                        </a>
                    </li>
                </ul>
            </nav>
            <div class="main-panel">
                <div class="content-wrapper">

                    <h2>Update Trainee Data </h2>
                    <div class="row">
                        <div class="col-14 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <?php
                                    echo $successMessage ? '<p style="color: green;">' . $successMessage . '</p>' : '';
                                    echo $errorMessage ? '<p style="color: red;">' . $errorMessage . '</p>' : '';

                                    // Check if there are related records in supervisor or trainee tables
                                    if ($_SESSION['formValues']['relatedSupervisorRecords'] > 0) {
                                        $warningColor = 'orange'; // Set the color for the warning message

                                        echo '<p style="color: ' . $warningColor . ';">Warning: There are related supervisor or trainee records.</p>';

                                        // Display the related supervisor records with bullet points
                                        $relatedSupervisorQuery = "SELECT superid, name FROM supervisor WHERE deptid='$editDeptID'";
                                        $relatedSupervisorResult = mysqli_query($database, $relatedSupervisorQuery);

                                        if ($relatedSupervisorResult && mysqli_num_rows($relatedSupervisorResult) > 0) {
                                            echo '<p style="color: ' . $warningColor . ';">Related Supervisor Records:</p>';
                                            echo '<ul style="color: ' . $warningColor . ';">';

                                            while ($supervisorRow = mysqli_fetch_assoc($relatedSupervisorResult)) {
                                                echo '<li>Supervisor: ' . $supervisorRow['name'] . '</li>';
                                            }

                                            echo '</ul>';
                                        }
                                    }
                                    ?>



                                    <form action="" method="post" onsubmit="return checkUnique()">

                                        <label for="deptnumber">Department ID:</label>
                                        <input type="text" name="updatedDeptnumber" value="<?php echo isset($existingDeptnumber) ? htmlspecialchars($existingDeptnumber) : ''; ?>" required><br>

                                        <label for="namedept">Department Name:</label>
                                        <input type="text" name="updatedNameDept" value="<?php echo isset($existingNamedept) ? htmlspecialchars($existingNamedept) : ''; ?>" required><br>

                                        <label for="subid">Select Subsidiary:</label>
                                        <select name="updatedSubId" required>
                                            <?php
                                            // Include your database connection code here
                                            include('../connection.php');

                                            // Fetch subsidiary data from the database
                                            $subsidiaryQuery = "SELECT subid, subname FROM subsidiary";
                                            $subsidiaryResult = mysqli_query($database, $subsidiaryQuery);

                                            // Populate the dropdown list with subsidiary options
                                            while ($row = mysqli_fetch_assoc($subsidiaryResult)) {
                                                $subid = $row['subid'];
                                                $subname = $row['subname'];
                                                // Check if the current subsidiary ID matches the existingSubid
                                                $selected = ($existingSubid == $subid) ? 'selected' : '';
                                            ?>
                                                <option value='<?php echo $subid; ?>' <?php echo $selected; ?>><?php echo $subname; ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select><br>



                                        <label for="status">Status:</label>
                                        <select name="status" required>
                                            <option value="1" <?php echo isset($existingStatus) && $existingStatus == 1 ? 'selected' : ''; ?>>Active</option>
                                            <option value="2" <?php echo isset($existingStatus) && $existingStatus == 2 ? 'selected' : ''; ?>>Inactive</option>
                                        </select><br>

                                        <!-- Hidden input for department ID -->
                                        <input type="hidden" name="deptId" value="<?php echo $editDeptID; ?>">

                                        <input type="submit" value="Update Department">
                                    </form>

                                    <!-- JavaScript for displaying success message and redirection -->
                                    <script>
                                        // Check if a success message is present, then display it and redirect
                                        var successMessage = "<?php echo $successMessage; ?>";
                                        if (successMessage) {
                                            alert(successMessage);
                                            window.location.href = "formdisplay.php";
                                        }

                                        // Check if an error message is present, then display it and redirect after a delay
                                        var errorMessage = "<?php echo $errorMessage; ?>";
                                        if (errorMessage) {
                                            alert(errorMessage);
                                            window.location.href = "formdisplay.php";
                                        }
                                    </script>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <footer class="footer">
        <div class="d-sm-flex justify-content-center justify-content-sm-between">
            <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2023. Hanani </a> All rights reserved.</span>

        </div>
    </footer>
    </div>

    </div>

</body>

</html>