<?php
include("../connection.php");
session_start();



// Check if the 'tid' parameter is set in the URL
$tid = isset($_GET['tid']) ? $_GET['tid'] : null;

if ($tid === null) {
    // Handle the case where 'tid' is not set
    echo "Trainee data not found: 'tid' parameter is missing.";
    exit();
}
// Fetch trainee data based on the provided tid
$sql_trainee = "SELECT * FROM trainee WHERE tid = ?";
$stmt_trainee = $database->prepare($sql_trainee);
$stmt_trainee->bind_param("i", $tid);
$stmt_trainee->execute();
$result_trainee = $stmt_trainee->get_result();

// Check if trainee data exists
if ($result_trainee->num_rows > 0) {

    $row_trainee = $result_trainee->fetch_assoc();
} else {
    // Handle the case where trainee data is not found
    echo "Trainee data not found for tid: " . $tid;
    exit();
}

$stmt_trainee->close();




if (isset($_SESSION["user"]) && $_SESSION["user"] != "" && $_SESSION['usertype'] == 'a') {

    // $image_data = isset($_POST["image_data"]) ? $_POST["image_data"] : $row_trainee['image_data'];
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST["name"];

        $deptid = $_POST["deptid"];

        $superid = $_POST["superid"];

        $temail = $_POST["temail"];

        $tpassword = password_hash($_POST["tpassword"], PASSWORD_DEFAULT); 

        $phone_number = $_POST["phone_number"];
        $startdate = $_POST["startdate"];
        $endate = $_POST["endate"];
        $courseofstudy = $_POST["courseofstudy"];
        $gender = $_POST["gender"];
        $status = $_POST["status"];
        $uni = $_POST["uni"];

        $image_data = isset($_POST["image_data"]) ? $_POST["image_data"] : $row_trainee['image_data'];
        $scase = isset($_POST["scase"]) ? $_POST["scase"] : (isset($row_trainee['scase']) ? $row_trainee['scase'] : null);
        // $image_data = $_POST["image_data"];
        // $created_at = $_POST["created_at"];
        // $scase = $_POST["scase"];
        // Check if 'image_data' is set in $_FILES and if it's not empty


        // Check if 'image_data' is not empty
        if (!empty($_FILES['image_data']['name'])) {
            // Check if the file upload is successful
            if ($_FILES['image_data']['error'] === UPLOAD_ERR_OK) {
                $image_data = file_get_contents($_FILES['image_data']['tmp_name']);
            } else {
                // Handle file upload error if needed
                $image_data = null;
                echo 'File upload error: ' . $_FILES['image_data']['error'];
            }
        } else {
            // If 'image_data' is empty, use the existing image data
            $image_data = $row_trainee['image_data'];
        }
    } else {
        // If 'image_data' is not set, use the existing image data
        $image_data = $row_trainee['image_data'];
    }



    // Update trainee information in the database
    $stmt = $database->prepare("UPDATE trainee 
            SET name = ?, 
                deptid = ?, 
                superid = ?, 
                temail = ?, 
                tpassword = ?, 
                phone_num = ?, 
                startdate = ?, 
                endate = ?,
                courseofstudy = ?, 
                gender = ?, 
                status = ?, 
                uni = ?, 
                image_data = ?, 
                created_at = ?, 
                scase = ? 
            WHERE tid = ?");

    $stmt->bind_param(
        "siississsssssssi", // Adjust types and count as per your columns
        $name,
        $deptid,
        $superid,
        $temail,
        $tpassword,
        $phone_number, // Fixed variable name
        $startdate,
        $endate,
        $courseofstudy,
        $gender,
        $status,
        $uni,
        $image_data,
        $created_at,
        $scase,
        $tid
    );

    // Execute the query
    if ($stmt->execute()) {
        $updateSuccess = true;
        // Redirect to the view page with the updated tid
        header("Location: view_trainee.php?tid=" . $tid);
        exit();
    } else {
        // Handle errors, display an error message, or redirect to an error page
        echo "Error processing form or updating database.";
    }
    // If email is updated, update the corresponding webuser record
    if ($temail != $row_trainee['temail']) {
        $updateWebUserStmt = $database->prepare("UPDATE webuser SET email = ? WHERE email = ?");
        $updateWebUserStmt->bind_param("ss", $temail, $row_trainee['temail']);
        $updateWebUserStmt->execute();
        $updateWebUserStmt->close();
    }
}

// Close the statement after execution
$stmt->close();



?>

<!DOCTYPE html>
<html>

<head>

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
                    <div class="col-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Update Trainee</h4>

                                <form id="updateForm" action="updateform.php?tid=<?= $tid ?>" method="post" enctype="multipart/form-data">

                                    <!-- Allow the user to update the image if needed -->
                                    <label for="image">Update Image:</label>
                                    <input type="file" class="form-control" name="image_data" id="image_data" accept="image/*"><br>

                                    <div class="form-group has-icon-left">
                                        <label for="name">Name:</label>
                                        <input type="text" class="form-control" name="name" id="name" value="<?= $row_trainee['name']; ?>"><br>

                                        <div class="form-control-icon" style="position: absolute;">
                                        </div>
                                        <!-- Gender -->
                                        <label for="gender">Gender:</label>
                                        <select name="gender" class="form-control" id="gender">
                                            <option value="Male" <?= ($row_trainee['gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                                            <option value="Female" <?= ($row_trainee['gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                                    
                                        </select><br>

                                        <!-- Phone Number -->

                                        <label for="phone_number">Phone Number:</label>
                                        <input type="text" class="form-control" name="phone_number" id="phone_numb" value="<?= $row_trainee['phone_num']; ?>"><br>


                                        <div class="form-group has-icon-left">
                                            <label for="name">Email:</label>
                                            <input type="text" class=form-control name="temail" id="temail" value="<?= $row_trainee['temail']; ?>"><br>
                                            <div class="form-control-icon" style="position: absolute;">
                                            </div>

                                            <label for="tpassword">Password:</label>
                                            <div class="input-group">
                                                <input type="password" class="form-control" name="tpassword" id="tpassword" value="<?= $row_trainee['tpassword']; ?>" readonly>
                                                <button type="button" class="btn btn-secondary" id="togglePassword">Show</button>
                                            

                                            <!-- Add the Reset Password Button -->
                                            <button type="button" class="btn btn-warning" id="resetPasswordBtn">Reset Password to 1223</button>

                                            </div>
                                            <br>
                                            <script>
                                                document.getElementById('togglePassword').addEventListener('click', function() {
                                                    var passwordField = document.getElementById('tpassword');
                                                    var passwordButton = document.getElementById('togglePassword');

                                                    if (passwordField.type === 'password') {
                                                        passwordField.type = 'text';
                                                        passwordButton.textContent = 'Hide';
                                                    } else {
                                                        passwordField.type = 'password';
                                                        passwordButton.textContent = 'Show';
                                                    }
                                                });

                                                document.getElementById('resetPasswordBtn').addEventListener('click', function() {
                                                    // Assuming you want to set the password to "1223"
                                                    var newPassword = '1223';
                                                    document.getElementById('tpassword').value = newPassword;
                                                });
                                            </script>






                                            <!-- University -->
                                            <label for="uni">University:</label>
                                            <input type="text" class="form-control" name="uni" id="uni" value="<?= $row_trainee['uni']; ?>"><br>

                                            <label for="courseofstudy">Program:</label>
                                            <input type="text" class="form-control" name="courseofstudy" id="courseofstudy" value="<?= $row_trainee['courseofstudy']; ?>"><br>


                                            <!-- Department Selection -->

                                            <div class="form-group has-icon-left">
                                                <label for="deptid">Department:</label>
                                                <div class="position-relative">
                                                    <select name="deptid" class="form-control" id="deptid">
                                                        <!-- Fetch departments from the database -->
                                                        <?php
                                                        $sql = "SELECT deptid, namedept FROM department";
                                                        $result = $database->query($sql);

                                                        if ($result->num_rows > 0) {
                                                            while ($row = $result->fetch_assoc()) {
                                                                $selected = ($row_trainee['deptid'] == $row['deptid']) ? 'selected' : '';
                                                                echo "<option value='" . $row['deptid'] . "' $selected>" . $row['namedept'] . "</option>";
                                                            }
                                                        }
                                                        ?>
                                                        <div class="form-control-icon" style="position: absolute;">
                                                    </select><br>
                                                </div>


                                                <!-- Supervisor Selection -->
                                                <div class="form-group has-icon-left">
                                                    <label for="superid">Supervisor</label>
                                                    <div class="position-relative">
                                                        <select name="superid" class="form-control" id="superid">
                                                            <?php
                                                            $sqlSupervisor = "SELECT superid, name FROM supervisor WHERE deptid = ?";
                                                            $stmtSupervisor = $database->prepare($sqlSupervisor);
                                                            $stmtSupervisor->bind_param("i", $row_trainee['deptid']);
                                                            $stmtSupervisor->execute();
                                                            $resultSupervisor = $stmtSupervisor->get_result();

                                                            if ($resultSupervisor->num_rows > 0) {
                                                                while ($rowSupervisor = $resultSupervisor->fetch_assoc()) {
                                                                    $selectedSuper = ($row_trainee['superid'] == $rowSupervisor['superid']) ? 'selected' : '';
                                                                    echo "<option value='" . $rowSupervisor['superid'] . "' $selectedSuper>" . $rowSupervisor['name'] . "</option>";
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                        <div class="form-control-icon" style="position: absolute;">
                                                            <i class=""></i>
                                                        </div>
                                                    </div>
                                                </div>



                                                <!-- Start Date -->
                                                <!-- Status Selection -->


                                                <label for="endate">Start Date:</label>
                                                <input type="date" class="form-control" name="startdate" id="startdate" value="<?= $row_trainee['startdate']; ?>"><br>

                                                <label for="endate">End Date:</label>
                                                <input type="date" class="form-control" name="endate" id="endate" value="<?= $row_trainee['endate']; ?>"><br>
                                                <!-- Course of Study -->

                                                <!-- Created At -->
                                                <label for="created_at">Created At:</label>
                                                <input type="datetime-local" class="form-control" name="created_at" id="created_at" value="<?= $row_trainee['created_at']; ?>" readonly><br>


                                                <label for="status">Status:</label>
                                                <select name="status" class="form-control" id="status" onchange="toggleScase()">
                                                    <option value="1" <?= ($row_trainee['status'] == 1) ? 'selected' : ''; ?>>Active</option>
                                                    <option value="2" <?= ($row_trainee['status'] == 2) ? 'selected' : ''; ?>>Inactive</option>
                                                    <option value="3" <?= ($row_trainee['status'] == 3) ? 'selected' : ''; ?>>Special Case</option>
                                                </select><br>
                                                <!-- Special Case -->
                                                <label for="scase">Special Case:</label>
                                                <select name="scase" class="form-control" id="scase" disabled>
                                                    <option value="Not Applicable" <?= ($row_trainee['scase'] == 'Not Applicable') ? 'selected' : '' ?>>Not Applicable</option>
                                                    <option value="Health Issues" <?= ($row_trainee['scase'] == 'Health Issues') ? 'selected' : '' ?>>Health Issues</option>
                                                    <option value="Personal Reasons" <?= ($row_trainee['scase'] == 'Personal Reasons') ? 'selected' : '' ?>>Personal Reasons</option>
                                                    <option value="Family Emergency" <?= ($row_trainee['scase'] == 'Family Emergency') ? 'selected' : '' ?>>Family Emergency</option>
                                                    <option value="Death in the Family" <?= ($row_trainee['scase'] == 'Death in the Family') ? 'selected' : '' ?>>Death in the Family</option>
                                                    <option value="Death of Trainee" <?= ($row_trainee['scase'] == 'Death of Trainee') ? 'selected' : '' ?>>Death of Trainee</option>
                                                    <option value="Job Opportunity" <?= ($row_trainee['scase'] == 'Job Opportunity') ? 'selected' : '' ?>>Job Opportunity</option>
                                                    <option value="Financial Constraints" <?= ($row_trainee['scase'] == 'Financial Constraints') ? 'selected' : '' ?>>Financial Constraints</option>
                                                    <option value="Change of Career Path" <?= ($row_trainee['scase'] == 'Change of Career Path') ? 'selected' : '' ?>>Change of Career Path</option>
                                                    <option value="Graduation" <?= ($row_trainee['scase'] == 'Graduation') ? 'selected' : '' ?>>Graduation</option>
                                                    <option value="Military Service" <?= ($row_trainee['scase'] == 'Military Service') ? 'selected' : '' ?>>Military Service</option>
                                                    <option value="Relocation" <?= ($row_trainee['scase'] == 'Relocation') ? 'selected' : '' ?>>Relocation</option>
                                                    <option value="Personal Development" <?= ($row_trainee['scase'] == 'Personal Development') ? 'selected' : '' ?>>Personal Development</option>
                                                    <!-- Add more options as needed -->
                                                    <option value="Other" <?= ($row_trainee['scase'] == 'Other') ? 'selected' : '' ?>>Other</option>
                                                </select><br>
                                                <!-- <button onclick="closeScaseModal(event)">Close</button> -->




                                                <input type="submit" value="Update Information">
                                </form>
                            </div>
                        </div>
                    </div>
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

        // Trigger the change event to load supervisors for the initial department
        document.getElementById("deptid").dispatchEvent(new Event('change'));
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


    <!-- End custom js for this page-->

</body>

</html>

<!-- https://www.google.com/url?sa=t&rct=j&q=&esrc=s&source=web&cd=&cad=rja&uact=8&ved=2ahUKEwiX3uqL2OOCAxVlxzgGHUj0CzYQwqsBegQICRAG&url=https%3A%2F%2Fwww.youtube.com%2Fwatch%3Fv%3D9RfU6KGNkfE&usg=AOvVaw1qZ2G0AoqeiIlxw7sEBLUD&opi=89978449 
https://www.google.com/url?sa=t&rct=j&q=&esrc=s&source=web&cd=&ved=2ahUKEwiX3uqL2OOCAxVlxzgGHUj0CzYQwqsBegQIDBAG&url=https%3A%2F%2Fwww.youtube.com%2Fwatch%3Fv%3Db50hB7cfsfg&usg=AOvVaw1XNjMsjBVTb_pmKlPEgRff&opi=89978449-->
<?php
include("../connection.php");
session_start();



// Check if the 'tid' parameter is set in the URL
$tid = isset($_GET['tid']) ? $_GET['tid'] : null;

if ($tid === null) {
    // Handle the case where 'tid' is not set
    echo "Trainee data not found: 'tid' parameter is missing.";
    exit();
}
// Fetch trainee data based on the provided tid
$sql_trainee = "SELECT * FROM trainee WHERE tid = ?";
$stmt_trainee = $database->prepare($sql_trainee);
$stmt_trainee->bind_param("i", $tid);
$stmt_trainee->execute();
$result_trainee = $stmt_trainee->get_result();

// Check if trainee data exists
if ($result_trainee->num_rows > 0) {

    $row_trainee = $result_trainee->fetch_assoc();
} else {
    // Handle the case where trainee data is not found
    echo "Trainee data not found for tid: " . $tid;
    exit();
}

$stmt_trainee->close();




if (isset($_SESSION["user"]) && $_SESSION["user"] != "" && $_SESSION['usertype'] == 'a') {

    // $image_data = isset($_POST["image_data"]) ? $_POST["image_data"] : $row_trainee['image_data'];
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST["name"];

        $deptid = $_POST["deptid"];

        $superid = $_POST["superid"];

        $temail = $_POST["temail"];

        $tpassword = password_hash($_POST["tpassword"], PASSWORD_DEFAULT); 

        $phone_number = $_POST["phone_number"];
        $startdate = $_POST["startdate"];
        $endate = $_POST["endate"];
        $courseofstudy = $_POST["courseofstudy"];
        $gender = $_POST["gender"];
        $status = $_POST["status"];
        $uni = $_POST["uni"];

        $image_data = isset($_POST["image_data"]) ? $_POST["image_data"] : $row_trainee['image_data'];
        $scase = isset($_POST["scase"]) ? $_POST["scase"] : (isset($row_trainee['scase']) ? $row_trainee['scase'] : null);
        // $image_data = $_POST["image_data"];
        // $created_at = $_POST["created_at"];
        // $scase = $_POST["scase"];
        // Check if 'image_data' is set in $_FILES and if it's not empty


        // Check if 'image_data' is not empty
        if (!empty($_FILES['image_data']['name'])) {
            // Check if the file upload is successful
            if ($_FILES['image_data']['error'] === UPLOAD_ERR_OK) {
                $image_data = file_get_contents($_FILES['image_data']['tmp_name']);
            } else {
                // Handle file upload error if needed
                $image_data = null;
                echo 'File upload error: ' . $_FILES['image_data']['error'];
            }
        } else {
            // If 'image_data' is empty, use the existing image data
            $image_data = $row_trainee['image_data'];
        }
    } else {
        // If 'image_data' is not set, use the existing image data
        $image_data = $row_trainee['image_data'];
    }



    // Update trainee information in the database
    $stmt = $database->prepare("UPDATE trainee 
            SET name = ?, 
                deptid = ?, 
                superid = ?, 
                temail = ?, 
                tpassword = ?, 
                phone_num = ?, 
                startdate = ?, 
                endate = ?,
                courseofstudy = ?, 
                gender = ?, 
                status = ?, 
                uni = ?, 
                image_data = ?, 
                created_at = ?, 
                scase = ? 
            WHERE tid = ?");

    $stmt->bind_param(
        "siississsssssssi", // Adjust types and count as per your columns
        $name,
        $deptid,
        $superid,
        $temail,
        $tpassword,
        $phone_number, // Fixed variable name
        $startdate,
        $endate,
        $courseofstudy,
        $gender,
        $status,
        $uni,
        $image_data,
        $created_at,
        $scase,
        $tid
    );

    // Execute the query
    if ($stmt->execute()) {
        $updateSuccess = true;
        // Redirect to the view page with the updated tid
        header("Location: view_trainee.php?tid=" . $tid);
        exit();
    } else {
        // Handle errors, display an error message, or redirect to an error page
        echo "Error processing form or updating database.";
    }
    // If email is updated, update the corresponding webuser record
    if ($temail != $row_trainee['temail']) {
        $updateWebUserStmt = $database->prepare("UPDATE webuser SET email = ? WHERE email = ?");
        $updateWebUserStmt->bind_param("ss", $temail, $row_trainee['temail']);
        $updateWebUserStmt->execute();
        $updateWebUserStmt->close();
    }
}

// Close the statement after execution
$stmt->close();



?>

<!DOCTYPE html>
<html>

<head>

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
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    </head>
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
                    <div class="col-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Update Trainee</h4>

                                <form id="updateForm" action="updateform.php?tid=<?= $tid ?>" method="post" enctype="multipart/form-data">

                                    <!-- Allow the user to update the image if needed -->
                                    <label for="image">Update Image:</label>
                                    <input type="file" class="form-control" name="image_data" id="image_data" accept="image/*"><br>

                                    <div class="form-group has-icon-left">
                                        <label for="name">Name:</label>
                                        <input type="text" class="form-control" name="name" id="name" value="<?= $row_trainee['name']; ?>"><br>

                                        <div class="form-control-icon" style="position: absolute;">
                                        </div>
                                        <!-- Gender -->
                                        <label for="gender">Gender:</label>
                                        <select name="gender" class="form-control" id="gender">
                                            <option value="Male" <?= ($row_trainee['gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                                            <option value="Female" <?= ($row_trainee['gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                                    
                                        </select><br>

                                        <!-- Phone Number -->

                                        <label for="phone_number">Phone Number:</label>
                                        <input type="text" class="form-control" name="phone_number" id="phone_numb" value="<?= $row_trainee['phone_num']; ?>"><br>


                                        <div class="form-group has-icon-left">
                                            <label for="name">Email:</label>
                                            <input type="text" class=form-control name="temail" id="temail" value="<?= $row_trainee['temail']; ?>"><br>
                                            <div class="form-control-icon" style="position: absolute;">
                                            </div>

                                            <label for="tpassword">Password:</label>
                                            <div class="input-group">
                                                <input type="password" class="form-control" name="tpassword" id="tpassword" value="<?= $row_trainee['tpassword']; ?>" readonly>
                                                <button type="button" class="btn btn-secondary" id="togglePassword">Show</button>
                                            

                                            <!-- Add the Reset Password Button -->
                                            <button type="button" class="btn btn-warning" id="resetPasswordBtn">Reset Password to 1223</button>

                                            </div>
                                            <br>
                                            <script>
                                                document.getElementById('togglePassword').addEventListener('click', function() {
                                                    var passwordField = document.getElementById('tpassword');
                                                    var passwordButton = document.getElementById('togglePassword');

                                                    if (passwordField.type === 'password') {
                                                        passwordField.type = 'text';
                                                        passwordButton.textContent = 'Hide';
                                                    } else {
                                                        passwordField.type = 'password';
                                                        passwordButton.textContent = 'Show';
                                                    }
                                                });

                                                document.getElementById('resetPasswordBtn').addEventListener('click', function() {
                                                    // Assuming you want to set the password to "1223"
                                                    var newPassword = '1223';
                                                    document.getElementById('tpassword').value = newPassword;
                                                });
                                            </script>






                                            <!-- University -->
                                            <label for="uni">University:</label>
                                            <input type="text" class="form-control" name="uni" id="uni" value="<?= $row_trainee['uni']; ?>"><br>

                                            <label for="courseofstudy">Program:</label>
                                            <input type="text" class="form-control" name="courseofstudy" id="courseofstudy" value="<?= $row_trainee['courseofstudy']; ?>"><br>


                                            <!-- Department Selection -->

                                            <div class="form-group has-icon-left">
                                                <label for="deptid">Department:</label>
                                                <div class="position-relative">
                                                    <select name="deptid" class="form-control" id="deptid">
                                                        <!-- Fetch departments from the database -->
                                                        <?php
                                                        $sql = "SELECT deptid,namedept FROM department";
                                                        $result = $database->query($sql);

                                                        if ($result->num_rows > 0) {
                                                            while ($row = $result->fetch_assoc()) {
                                                                $selected = ($row_trainee['deptid'] == $row['deptid']) ? 'selected' : '';
                                                                echo "<option value='" . $row['deptid'] . "' $selected>" . $row['namedept'] . "</option>";
                                                            }
                                                        }
                                                        ?>
                                                        <div class="form-control-icon" style="position: absolute;">
                                                    </select><br>
                                                </div>


                                                <!-- Supervisor Selection -->
                                                <div class="form-group has-icon-left">
                                                    <label for="superid">Supervisor</label>
                                                    <div class="position-relative">
                                                        <select name="superid" class="form-control" id="superid">
                                                            <?php
                                                            $sqlSupervisor = "SELECT superid, name FROM supervisor WHERE deptid = ?";
                                                            $stmtSupervisor = $database->prepare($sqlSupervisor);
                                                            $stmtSupervisor->bind_param("i", $row_trainee['deptid']);
                                                            $stmtSupervisor->execute();
                                                            $resultSupervisor = $stmtSupervisor->get_result();

                                                            if ($resultSupervisor->num_rows > 0) {
                                                                while ($rowSupervisor = $resultSupervisor->fetch_assoc()) {
                                                                    $selectedSuper = ($row_trainee['superid'] == $rowSupervisor['superid']) ? 'selected' : '';
                                                                    echo "<option value='" . $rowSupervisor['superid'] . "' $selectedSuper>" . $rowSupervisor['name'] . "</option>";
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                        <div class="form-control-icon" style="position: absolute;">
                                                            <i class=""></i>
                                                        </div>
                                                    </div>
                                                </div>



                                                <!-- Start Date -->
                                                <!-- Status Selection -->


                                                <label for="endate">Start Date:</label>
                                                <input type="date" class="form-control" name="startdate" id="startdate" value="<?= $row_trainee['startdate']; ?>"><br>

                                                <label for="endate">End Date:</label>
                                                <input type="date" class="form-control" name="endate" id="endate" value="<?= $row_trainee['endate']; ?>"><br>
                                                <!-- Course of Study -->

                                                <!-- Created At -->
                                                <label for="created_at">Created At:</label>
                                                <input type="datetime-local" class="form-control" name="created_at" id="created_at" value="<?= $row_trainee['created_at']; ?>" readonly><br>


                                                <label for="status">Status:</label>
                                                <select name="status" class="form-control" id="status" onchange="toggleScase()">
                                                    <option value="1" <?= ($row_trainee['status'] == 1) ? 'selected' : ''; ?>>Active</option>
                                                    <option value="2" <?= ($row_trainee['status'] == 2) ? 'selected' : ''; ?>>Inactive</option>
                                                    <option value="3" <?= ($row_trainee['status'] == 3) ? 'selected' : ''; ?>>Special Case</option>
                                                </select><br>
                                                <!-- Special Case -->
                                                <label for="scase">Special Case:</label>
                                                <select name="scase" class="form-control" id="scase" disabled>
                                                    <option value="Not Applicable" <?= ($row_trainee['scase'] == 'Not Applicable') ? 'selected' : '' ?>>Not Applicable</option>
                                                    <option value="Health Issues" <?= ($row_trainee['scase'] == 'Health Issues') ? 'selected' : '' ?>>Health Issues</option>
                                                    <option value="Personal Reasons" <?= ($row_trainee['scase'] == 'Personal Reasons') ? 'selected' : '' ?>>Personal Reasons</option>
                                                    <option value="Family Emergency" <?= ($row_trainee['scase'] == 'Family Emergency') ? 'selected' : '' ?>>Family Emergency</option>
                                                    <option value="Death in the Family" <?= ($row_trainee['scase'] == 'Death in the Family') ? 'selected' : '' ?>>Death in the Family</option>
                                                    <option value="Death of Trainee" <?= ($row_trainee['scase'] == 'Death of Trainee') ? 'selected' : '' ?>>Death of Trainee</option>
                                                    <option value="Job Opportunity" <?= ($row_trainee['scase'] == 'Job Opportunity') ? 'selected' : '' ?>>Job Opportunity</option>
                                                    <option value="Financial Constraints" <?= ($row_trainee['scase'] == 'Financial Constraints') ? 'selected' : '' ?>>Financial Constraints</option>
                                                    <option value="Change of Career Path" <?= ($row_trainee['scase'] == 'Change of Career Path') ? 'selected' : '' ?>>Change of Career Path</option>
                                                    <option value="Graduation" <?= ($row_trainee['scase'] == 'Graduation') ? 'selected' : '' ?>>Graduation</option>
                                                    <option value="Military Service" <?= ($row_trainee['scase'] == 'Military Service') ? 'selected' : '' ?>>Military Service</option>
                                                    <option value="Relocation" <?= ($row_trainee['scase'] == 'Relocation') ? 'selected' : '' ?>>Relocation</option>
                                                    <option value="Personal Development" <?= ($row_trainee['scase'] == 'Personal Development') ? 'selected' : '' ?>>Personal Development</option>
                                                    <!-- Add more options as needed -->
                                                    <option value="Other" <?= ($row_trainee['scase'] == 'Other') ? 'selected' : '' ?>>Other</option>
                                                </select><br>
                                                <!-- <button onclick="closeScaseModal(event)">Close</button> -->




                                                <input type="submit" value="Update Information">
                                </form>
                            </div>
                        </div>
                    </div>
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

        // Trigger the change event to load supervisors for the initial department
        document.getElementById("deptid").dispatchEvent(new Event('change'));
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


    <!-- End custom js for this page-->

</body>

</html>

<!-- https://www.google.com/url?sa=t&rct=j&q=&esrc=s&source=web&cd=&cad=rja&uact=8&ved=2ahUKEwiX3uqL2OOCAxVlxzgGHUj0CzYQwqsBegQICRAG&url=https%3A%2F%2Fwww.youtube.com%2Fwatch%3Fv%3D9RfU6KGNkfE&usg=AOvVaw1qZ2G0AoqeiIlxw7sEBLUD&opi=89978449 
https://www.google.com/url?sa=t&rct=j&q=&esrc=s&source=web&cd=&ved=2ahUKEwiX3uqL2OOCAxVlxzgGHUj0CzYQwqsBegQIDBAG&url=https%3A%2F%2Fwww.youtube.com%2Fwatch%3Fv%3Db50hB7cfsfg&usg=AOvVaw1XNjMsjBVTb_pmKlPEgRff&opi=89978449-->
<?php
include("../connection.php");

$tid = $_GET['tid'];

$sql = "SELECT * FROM trainee WHERE tid = $tid";
$result = $database->query($sql);

// Add this code to fetch subsidiary information
$sql_subsidiary = "SELECT * FROM subsidiary";
$result_subsidiary = $database->query($sql_subsidiary);


if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $tid = $row['tid'];
    // Assign fetched values to variables for pre-filling the form
    $traineenumber = $row["traineenumber"];
    $name = $row["name"];
    $deptid = $row["deptid"];
    $temail = $row["temail"];
    $username = $row["username"];
    $tpassword = $row["tpassword"];
    $superid = $row["superid"];
    $phone_num = $row["phone_num"];
    $startdate = $row["startdate"];
    $endate = $row["endate"];
    $courseofstudy = $row["courseofstudy"];
    $gender = $row["gender"];
    $status = $row["status"];
    $uni = $row["uni"];
    $scase = $row["scase"];
} else {
    // Handle the case when no trainee is found with the given tid
    echo "Trainee not found.";
    // You might want to redirect the user to the previous page or show an error message
    exit;
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tid = $_POST["tid"];
    // Handle form submission, update database, etc.
    // Make sure to validate and sanitize user input to prevent SQL injection
    $traineenumber = $_POST["traineenumber"];
    $name = $_POST["name"];
    $temail = $_POST["temail"];
    $username = $_POST["username"];
    $phone_num = $_POST["phone_num"];
    $startdate = $_POST["startdate"];
    $endate = $_POST["endate"];
    $courseofstudy = $_POST["courseofstudy"];
    $gender = $_POST["gender"];
    $status = $_POST["status"];
    $uni = $_POST["uni"];
    $scase = isset($_POST["scase"]) ? $_POST["scase"] : '';
    $tpassword = isset($_POST["tpassword"]) ? password_hash($_POST["tpassword"], PASSWORD_DEFAULT) : '';

    // Check if deptid and superid are set in the form data
    $deptid = isset($_POST["deptid"]) ? $_POST["deptid"] : null;
    $superid = isset($_POST["superid"]) ? $_POST["superid"] : null;

    if ($database->connect_error) {
        die("Connection failed: " . $database->connect_error);
    }

    // Use prepared statement to avoid SQL injection
    $sql_update = "UPDATE trainee SET 
        traineenumber=?,
        name=?,
        deptid=?,
        temail=?,
        username=?,
        tpassword=?,
        superid=?,
        phone_num=?,
        startdate=?,
        endate=?,
        courseofstudy=?,
        gender=?,
        status=?,
        uni=?,
        scase=?
        WHERE tid=?";

    $updateStmt = $database->prepare($sql_update);
    $updateStmt->bind_param(
        "ssisssisssssissi",
        $traineenumber,
        $name,
        $deptid,
        $temail,
        $username,
        $tpassword,
        $superid,
        $phone_num,
        $startdate,
        $endate,
        $courseofstudy,
        $gender,
        $status,
        $uni,
        $scase,
        $tid
    );

    if ($updateStmt->execute()) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . $updateStmt->error;
    }

    $updateStmt->close();

    if ($temail != $row['temail']) {
        $updateWebUserStmt = $database->prepare("UPDATE webuser SET email = ? WHERE email = ?");
        $updateWebUserStmt->bind_param("ss", $temail, $row['temail']);
        $updateWebUserStmt->execute();
        $updateWebUserStmt->close();
    }
}

$database->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Trainee Information</title>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

</head>

<body>

    <h2>Update Trainee Information</h2>

    <form action="updateform.php?tid=<?php echo $tid; ?>" method="post">
        <input type="hidden" name="tid" value="<?php echo $tid; ?>">


        <label for="traineenumber">Trainee Number:</label>
        <input type="text" id="traineenumber" name="traineenumber" value="<?php echo $traineenumber; ?>" required><br>

        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?php echo $name; ?>" required><br>


        <label for="subid">Subsidiary:</label>
        <select name="subid" class="form-control" id="subid" onchange="updateDepartments()">
            <?php
            // Fetch subsidiary information from the database and populate the dropdown
            while ($row_subsidiary = $result_subsidiary->fetch_assoc()) {
                $subid = $row_subsidiary['subid'];
                $subname = $row_subsidiary['subname'];
                echo "<option value=\"$subid\">$subname</option>";
            }
            ?>
        </select><br>
        
        <label for="deptid">Department:</label>
        <select name="deptid" class="form-control" id="deptid" onchange="updateSupervisors()">
            <!-- Options will be dynamically populated using AJAX -->
        </select><br>

        <label for="superid">Supervisor:</label>
        <select name="superid" class="form-control" id="superid">
            <!-- Options will be dynamically populated using AJAX -->
        </select><br>



        <label for="temail">Email:</label>
        <input type="text" id="temail" name="temail" value="<?php echo $temail; ?>" required><br>

        <label for="username">Username:</label>
        <input type="text" id="username" name="username" value="<?php echo $username; ?>" required><br>

        <label for="tpassword">Password:</label>
        <input type="password" id="tpassword" name="tpassword" value="<?php echo $tpassword; ?>" required>

        <!-- Add a reset button -->
        <button type="button" onclick="resetPassword()">Reset Password</button><br>




        <label for="phone_num">Phone Number:</label>
        <input type="text" id="phone_num" name="phone_num" value="<?php echo $phone_num; ?>"><br>

        <label for="startdate">Start Date:</label>
        <input type="text" id="startdate" name="startdate" value="<?php echo $startdate; ?>"><br>

        <label for="endate">End Date:</label>
        <input type="text" id="endate" name="endate" value="<?php echo $endate; ?>"><br>

        <label for="courseofstudy">Course of Study:</label>
        <input type="text" id="courseofstudy" name="courseofstudy" value="<?php echo $courseofstudy; ?>"><br>

        <label for="gender">Gender:</label>
        <input type="text" id="gender" name="gender" value="<?php echo $gender; ?>"><br>


        <label for="uni">University:</label>
        <input type="text" id="uni" name="uni" value="<?php echo $uni; ?>"><br>

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
            newPasswordInput.value = '1224';

            // Replace the existing password input with the new one
            var existingPasswordInput = document.getElementById('tpassword');
            existingPasswordInput.parentNode.replaceChild(newPasswordInput, existingPasswordInput);

            // Wait for 1 second and then replace the text input with a password input
            setTimeout(function() {
                var newPasswordInput = document.createElement("input");
                newPasswordInput.type = "password";
                newPasswordInput.value = '1224';

                existingPasswordInput.parentNode.replaceChild(newPasswordInput, existingPasswordInput);
            }, 1000);
        }
    </script>

    <script>
     function updateDepartments() {
    var subsidiaryId = document.getElementById('subid').value;
    
    // Use AJAX to fetch departments based on the selected subsidiary
    $.ajax({
        type: 'POST',
        url: 'get_depart.php',
        data: { subsidiaryId: subsidiaryId },
        success: function(response) {
            $('#deptid').html(response);
            updateSupervisors(); // Trigger supervisor update after updating departments
        }
    });
}

function updateSupervisors() {
    var departmentId = document.getElementById('deptid').value;
    
    // Use AJAX to fetch supervisors based on the selected department
    $.ajax({
        type: 'POST',
        url: 'get_super.php',
        data: { departmentId: departmentId },
        success: function(response) {
            $('#superid').html(response);
        }
    });
}

    </script>





</body>

</html>