<?php
include("../connection.php");

$tid = $_GET['tid'];
$trainee_query = $database->prepare("SELECT * FROM trainee WHERE tid = ?");
if (!$trainee_query) {
    echo "Error in prepare statement: " . $database->error;
    exit;
}

$trainee_query->bind_param("i", $tid);
if (!$trainee_query->execute()) {
    echo "Error in query execution: " . $trainee_query->error;
    exit;
}

$result = $trainee_query->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $tid = $row['tid'];
    $traineenumber = $row["traineenumber"];
    $name = $row["name"];
    $temail = $row["temail"];
    $username = $row["username"];
    $tpassword = $row["tpassword"];
    $phone_num = $row["phone_num"];
    $startdate = $row["startdate"];
    $endate = $row["endate"];
    $courseofstudy = $row["courseofstudy"];
    $gender = $row["gender"];
    $status = $row["status"];
    $uni = $row["uni"];
    $scase = $row["scase"];

    // Fetch subsidiary data for populating the initial form
    $subsidary_query = $database->query("SELECT * FROM subsidiary");
    if (!$subsidary_query) {
        echo "Error fetching subsidiary data: " . $database->error;
        exit;
    }
    $subsidaries = $subsidary_query->fetch_all(MYSQLI_ASSOC);

    // Check if 'subid' key exists in the $row array
    $subid = isset($row['subid']) ? $row['subid'] : null;

    // Fetch departments based on the subsidiary of the selected trainee
    $department_query = $database->prepare("SELECT * FROM department WHERE subid = ?");
    if (!$department_query) {
        echo "Error in prepare statement: " . $database->error;
        exit;
    }
    $department_query->bind_param("i", $subid);
    if (!$department_query->execute()) {
        echo "Error fetching department data: " . $department_query->error;
        exit;
    }
    $departments = $department_query->get_result()->fetch_all(MYSQLI_ASSOC);

    // Fetch supervisors based on the department of the selected trainee
    $supervisor_query = $database->prepare("SELECT * FROM supervisor WHERE deptid = ? AND status = 1");
    $supervisor_query->bind_param("i", $deptid);

    if (!$supervisor_query->execute()) {
        echo "Error fetching supervisor data: " . $supervisor_query->error;
        exit;
    }

    $supervisors = $supervisor_query->get_result()->fetch_all(MYSQLI_ASSOC);

    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Update $tid to use POST data for consistency
        $tid = $_POST["tid"];
        $traineenumber = $_POST["traineenumber"];
        $name = $_POST["name"];
        $temail = $_POST["temail"];
        $username = $_POST["username"];
        $tpassword = password_hash($_POST["tpassword"], PASSWORD_DEFAULT);
        $phone_num = $_POST["phone_num"];
        $startdate = $_POST["startdate"];
        $endate = $_POST["endate"];
        $courseofstudy = $_POST["courseofstudy"];
        $gender = $_POST["gender"];
        $status = $_POST["status"];
        $uni = $_POST["uni"];

        $scase = isset($_POST["scase"]) ? $_POST["scase"] : '';

        // Update subsidiary, department, and supervisor based on the form data
        $subid = isset($_POST["subid"]) ? $_POST["subid"] : '';
        $deptid = isset($_POST["department"]) ? $_POST["department"] : '';
        $superid = isset($_POST["supervisor"]) ? $_POST["supervisor"] : '';

        // Update the trainee record in the database
        $sql_update_trainee = "UPDATE trainee SET 
        traineenumber='$traineenumber',
        name='$name',
        deptid='$deptid',
        temail='$temail',
        username='$username',
        tpassword='$tpassword',
        superid='$superid',
        phone_num='$phone_num',
        startdate='$startdate',
        endate='$endate',
        courseofstudy='$courseofstudy',
        gender='$gender',
        status='$status',
        uni='$uni',
        scase='$scase'
        WHERE tid=$tid";

        if ($database->query($sql_update_trainee) === TRUE) {
            // Update webuser table if temail is changed
            if ($_POST["temail"] !== $row["temail"]) {
                $newTemail = $_POST["temail"];
                $sql_update_webuser = "UPDATE webuser SET email='$newTemail' WHERE email='{$row['temail']}'";

                if ($database->query($sql_update_webuser) !== TRUE) {
                    echo '<script>
                        alert("Error updating webuser record: ' . $database->error . '");
                    </script>';
                    exit;
                }
            }

            echo '<script>
                alert("Record updated successfully");
                window.location.href = "trainee.php";
            </script>';
            exit;
        } else {
            echo '<script>
                alert("Error updating trainee record: ' . $database->error . '");
            </script>';
        }
    }
}

// Close the database connection
$database->close();
?>





<!DOCTYPE html>
<html lang="en">

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
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

</head>


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

            <li class="nav-item">

            </li>
            <li class="nav-item">



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
                <div class="row">



                    <div class="col-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body" style="max-height: 600px; overflow-y: auto;">
                                <h2>Update Trainee Information</h2>

                                <form action="update_form.php?tid=<?php echo $tid; ?>" method="post">
                                    <input type="hidden" name="tid" value="<?php echo $tid; ?>">


                                    <label for="traineenumber">Trainee Number:</label>
                                    <input type="text" class="form-control" id="traineenumber" name="traineenumber" value="<?php echo $traineenumber; ?>" readonly><br>

                                    <label for="name">Name:</label>
                                    <input type="text" class="form-control" id="name" name="name" value="<?php echo $name; ?>" required><br>

                                    <label for="gender">Gender:</label>
                                    <select id="gender" class="form-control" name="gender">
                                        <option value="female" <?php echo ($gender === 'female') ? 'selected' : ''; ?>>Female</option>
                                        <option value="male" <?php echo ($gender === 'male') ? 'selected' : ''; ?>>Male</option>
                                    </select><br>

                                    <label for="temail">Email:</label>
                                    <input type="text" class="form-control" id="temail" name="temail" value="<?php echo $temail; ?>" required><br>

                                    <label for="username">Username:</label>
                                    <input type="text" class="form-control" id="username" name="username" value="<?php echo $username; ?>" required><br>

                                    <div class="row">
                                        <div class="col-md-6 col-12">
                                            <label for="tpassword">Password:</label>
                                            <input type="password" class="form-control" id="tpassword" name="tpassword" value="<?php echo $tpassword; ?>" required>
                                        </div>
                                        <div  class="col-md-6 col-12 d-flex align-items-end">
                                            <!-- Add a reset button -->
                                            <button type="button" onclick="resetPassword()" class="form-control"class="btn btn-warning">Reset Password</button>
                                        </div>
                                    </div>



                                    <label for="phone_num">Phone Number:</label>
                                    <input type="text" class="form-control" id="phone_num" name="phone_num" value="<?php echo $phone_num; ?>"><br>

                                    <label for="uni">Institute:</label>
                                    <input type="text" class="form-control" id="uni" name="uni" value="<?php echo $uni; ?>"><br>

                                    <label for="courseofstudy">Program:</label>
                                    <input type="text" class="form-control" id="courseofstudy" name="courseofstudy" value="<?php echo $courseofstudy; ?>"><br>

                                    <label for="startdate">Start Date:</label>
                                    <input type="date" class="form-control" id="startdate" name="startdate" value="<?php echo $startdate; ?>"><br>

                                    <label for="endate">End Date:</label>
                                    <input type="date" class="form-control" id="endate" name="endate" value="<?php echo $endate; ?>"><br>


                                    <!-- Existing HTML code for subsidiary dropdown -->
                                    <label for="subsidiary">Company:</label>
                                    <select id="subsidiary" class="form-control" name="subsidiary" onchange="getDepartments()">
                                        <?php foreach ($subsidaries as $subsidiary) : ?>
                                            <option value="<?php echo $subsidiary['subid']; ?>" <?php echo (isset($traineeData['subid']) && $subsidiary['subid'] == $traineeData['subid']) ? 'selected' : ''; ?>>
                                                <?php echo $subsidiary['subname']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select><br>


                                    <!-- Existing HTML code for department dropdown -->
                                    <label for="department">Division:</label>
                                    <select id="department" class="form-control" name="department" onchange="getSupervisors()">
                                        <?php foreach ($departments as $department) : ?>
                                            <option value="<?php echo $department['deptid']; ?>" <?php echo ($department['deptid'] == $traineeData['deptid']) ? 'selected' : ''; ?>>
                                                <?php echo $department['namedept']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select><br>


                                    <!-- Existing HTML code for supervisor dropdown -->
                                    <label for="supervisor">Supervisor:</label>
                                    <select id="supervisor" class="form-control" name="supervisor" required>
                                        <?php foreach ($supervisors as $supervisor) : ?>
                                            <option value="<?php echo $supervisor['superid']; ?>" <?php echo ($supervisor['superid'] == $traineeData['superid']) ? 'selected' : ''; ?>>
                                                <?php echo $supervisor['name']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select><br>


                                    <label for="status">Status:</label>
                                    <select name="status" class="form-control" id="status" onchange="toggleScase()">
                                        <option value="1" <?= ($status == 1) ? 'selected' : ''; ?>>Active</option>
                                        <option value="2" <?= ($status == 2) ? 'selected' : ''; ?>>Inactive</option>
                                        <option value="3" <?= ($status == 3) ? 'selected' : ''; ?>>Special Case</option>
                                    </select><br>

                                    <!-- Special Case -->
                                    <label for="scase">Special Case:</label>
                                    <select name="scase" class="form-control" id="scase" <?php echo ($status == 3) ? '' : 'disabled'; ?>>
                                        <option value="Not Applicable" <?= ($scase == 'Not Applicable') ? 'selected' : '' ?>>Not Applicable</option>
                                        <option value="Health Issues" <?= ($scase == 'Health Issues') ? 'selected' : '' ?>>Health Issues</option>
                                        <option value="Personal Reasons" <?= ($scase == 'Personal Reasons') ? 'selected' : '' ?>>Personal Reasons</option>
                                        <option value="Family Emergency" <?= ($scase == 'Family Emergency') ? 'selected' : '' ?>>Family Emergency</option>
                                        <option value="Death in the Family" <?= ($scase == 'Death in the Family') ? 'selected' : '' ?>>Death in the Family</option>
                                        <option value="Death of Trainee" <?= ($scase == 'Death of Trainee') ? 'selected' : '' ?>>Death of Trainee</option>
                                        <option value="Job Opportunity" <?= ($scase == 'Job Opportunity') ? 'selected' : '' ?>>Job Opportunity</option>
                                        <option value="Financial Constraints" <?= ($scase == 'Financial Constraints') ? 'selected' : '' ?>>Financial Constraints</option>
                                        <option value="Change of Career Path" <?= ($scase == 'Change of Career Path') ? 'selected' : '' ?>>Change of Career Path</option>
                                        <option value="Graduation" <?= ($scase == 'Graduation') ? 'selected' : '' ?>>Graduation</option>
                                        <option value="Military Service" <?= ($scase == 'Military Service') ? 'selected' : '' ?>>Military Service</option>
                                        <option value="Relocation" <?= ($scase == 'Relocation') ? 'selected' : '' ?>>Relocation</option>
                                        <option value="Personal Development" <?= ($scase == 'Personal Development') ? 'selected' : '' ?>>Personal Development</option>
                                        <!-- Add more options as needed -->
                                        <option value="Other" <?= ($scase == 'Other') ? 'selected' : '' ?>>Other</option>
                                    </select><br>

                                    <button type="submit">Update Information</button>
                                </form>

                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var startDateInput = document.getElementById('startdate');
            var endDateInput = document.getElementById('endate');

            // Add an event listener to the start date input
            startDateInput.addEventListener('input', function() {
                // Get the selected start date
                var startDate = new Date(startDateInput.value);

                // Get the selected end date
                var endDate = new Date(endDateInput.value);

                // Check if the end date is before the start date
                if (endDate < startDate) {
                    alert('End date cannot be before the start date. Please select a valid end date.');
                    endDateInput.value = ''; // Clear the invalid end date
                }
            });

            // Add an event listener to the end date input
            endDateInput.addEventListener('input', function() {
                // Get the selected start date
                var startDate = new Date(startDateInput.value);

                // Get the selected end date
                var endDate = new Date(endDateInput.value);

                // Check if the end date is before the start date
                if (endDate < startDate) {
                    alert('End date cannot be before the start date. Please select a valid end date.');
                    endDateInput.value = ''; // Clear the invalid end date
                }
            });
        });
    </script>

    <script>
        function toggleScase() {
            var statusSelect = document.getElementById('status');
            var scaseSelect = document.getElementById('scase');

            if (statusSelect.value == '3') {
                scaseSelect.disabled = false;
            } else {
                scaseSelect.disabled = true;
                // Set the value to "Not Applicable" when status is 1 or 2
                scaseSelect.value = 'Not Applicable';
            }
        }

        // Initial call to set the initial state based on the current value of 'status'
        toggleScase();
    </script>

    <script>
        function resetPassword() {
            // Create a new text input field
            var newPasswordInput = document.createElement("input");
            newPasswordInput.type = "text";
            newPasswordInput.name = "tpassword"; // Ensure the name attribute matches the expected POST key
            newPasswordInput.value = '1224';

            // Replace the existing password input with the new one
            var existingPasswordInput = document.getElementById('tpassword');
            existingPasswordInput.parentNode.replaceChild(newPasswordInput, existingPasswordInput);

            // Wait for 1 second and then replace the text input with a password input
            setTimeout(function() {
                var newPasswordInput = document.createElement("input");
                newPasswordInput.type = "password";
                newPasswordInput.name = "tpassword"; // Ensure the name attribute matches the expected POST key
                newPasswordInput.value = '1224';

                existingPasswordInput.parentNode.replaceChild(newPasswordInput, existingPasswordInput);
            }, 1000);
        }
    </script>

    <script>
        $(document).ready(function() {
            // Populate the 'subsidiary' dropdown on page load
            getDepartments();
        });

        function getDepartments() {
            var subsidiaryId = $('#subsidiary').val();

            // Perform an AJAX request to get departments for the selected subsidiary
            $.ajax({
                url: 'get_data.php?departments', // Update the URL to include 'departments'
                method: 'GET',
                dataType: 'json',
                data: {
                    subsidiaryId: subsidiaryId
                },
                success: function(data) {
                    // Populate the 'department' dropdown with the retrieved data
                    var departmentDropdown = $('#department');
                    departmentDropdown.empty(); // Clear existing options

                    // Append new options based on the retrieved data
                    $.each(data, function(index, item) {
                        departmentDropdown.append($('<option>', {
                            value: item.deptid,
                            text: item.namedept
                        }));
                    });

                    // Trigger the 'change' event to populate the 'supervisor' dropdown
                    departmentDropdown.trigger('change');
                },
                error: function(error) {
                    console.error('Error fetching department data:', error);
                }
            });
        }

        function getSupervisors() {
            var departmentId = $('#department').val();

            // Perform an AJAX request to get supervisors for the selected department
            $.ajax({
                url: 'get_supervisor.php?supervisors', // Update the URL to include 'supervisors'
                method: 'GET',
                dataType: 'json',
                data: {
                    departmentId: departmentId
                },
                success: function(data) {
                    // Populate the 'supervisor' dropdown with the retrieved data
                    var supervisorDropdown = $('#supervisor');
                    supervisorDropdown.empty(); // Clear existing options

                    // Append new options based on the retrieved data
                    $.each(data, function(index, item) {
                        supervisorDropdown.append($('<option>', {
                            value: item.superid,
                            text: item.name
                        }));
                    });
                },
                error: function(error) {
                    console.error('Error fetching supervisor data:', error);
                }
            });
        }
    </script>








</body>

</html>