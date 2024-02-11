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

if(isset($_SESSION["user"])){
    if($_SESSION["user"] == "" || $_SESSION['usertype'] != 't'){
        header("location: ../login.php");
        exit;
    } else {
        $useremail = $_SESSION["user"];
    }
} else {
    header("location: ../login.php");
    exit;
}

include("../connection.php");

// Fetch the user's information from the database
$userrow = $database->query("SELECT t.*, s.name AS supervisor_name FROM trainee t
                             LEFT JOIN supervisor s ON t.superid = s.superid
                             WHERE t.temail='$useremail'");
$userfetch = $userrow->fetch_assoc(); // Fetch the user data

// Fetch the department name associated with the user's department ID
$deptID = $userfetch['deptid'];
$deptRow = $database->query("SELECT namedept FROM department WHERE deptid='$deptID'");
$deptfetch = $deptRow->fetch_assoc();
$namedept = $deptfetch['namedept'];

// Check if the form is submitted for updating the profile and/or username
if (isset($_POST["update_profile"])) {
    // Get the submitted data
    $phone_num = $_POST["phone_num"];
    $startdate = $_POST["startdate"];
    $endate = $_POST["endate"];
    $courseofstudy = $_POST["courseofstudy"];
    $gender = $_POST["gender"];
    $new_username = $_POST["new_username"];

    // Update the user's other information in the database
    $update_query = "UPDATE trainee SET phone_num = '$phone_num', 
                                       startdate = '$startdate', 
                                       endate = '$endate', 
                                       courseofstudy = '$courseofstudy', 
                                       gender = '$gender',
                                       username = '$new_username'
                     WHERE temail = '$useremail'";

    if ($database->query($update_query)) {
        echo "Profile information and username updated successfully.";
        $userfetch['username'] = $new_username; // Update $userfetch['username']
    } else {
        echo "Error updating profile information and username: " . $database->error;
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
    <!-- partial:../../partials/_settings-panel.html -->
    <!-- <div class="theme-setting-wrapper">
        <div id="settings-trigger"><i class="ti-settings"></i></div>
        <div id="theme-settings" class="settings-panel">
          <i class="settings-close ti-close"></i>
          <p class="settings-heading">SIDEBAR SKINS</p>
          <div class="sidebar-bg-options selected" id="sidebar-light-theme"><div class="img-ss rounded-circle bg-light border mr-3"></div>Light</div>
          <div class="sidebar-bg-options" id="sidebar-dark-theme"><div class="img-ss rounded-circle bg-dark border mr-3"></div>Dark</div>
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
      </div> -->
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


    <div class="main-panel">
      <div class="content-wrapper">
        <div class="row">
          <?php
          include("../connection.php");

          // Check connection
          if ($database->connect_error) {
            die("Connection failed: " . $database->connect_error);
          }

          // Process the form submission if it's a POST request
          if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Retrieve data from the form
            $deptid = $_POST["deptid"];
            $superid = $_POST["superid"];
            $name = $_POST["name"];
            $temail = $_POST["temail"];
            $username = $_POST["username"];
            $tpassword = $_POST["tpassword"];
            // Default status value
            

            // Insert trainee data into the 'trainee' table
            $status =  "1"; // Set the status to an integer value
            $sql = "INSERT INTO trainee (deptid, superid, name, temail, username, tpassword, status) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $database->prepare($sql);
            $stmt->bind_param("iisssss", $deptid, $superid, $name, $temail, $username, $tpassword, $status);
            

            if ($stmt->execute()) {
              // Insertion into 'trainee' successful
              echo "Trainee registration successful.";

              // Insert data into the 'webuser' table
              $webuser_email = $temail;
              $usertype = 't'; // Use 't' as the usertype

              $sql_webuser = "INSERT INTO webuser (email, usertype) VALUES (?, ?)";
              $stmt_webuser = $database->prepare($sql_webuser);
              $stmt_webuser->bind_param("ss", $webuser_email, $usertype);

              if ($stmt->execute()) {
                // Insertion into 'trainee' successful
                echo "Trainee registration successful.";
            
                // Insert data into the 'webuser' table
                $webuser_email = $temail;
                $usertype = 't'; // Use 't' as the usertype
            
                $sql_webuser = "INSERT INTO webuser (email, usertype) VALUES (?, ?)";
                $stmt_webuser = $database->prepare($sql_webuser);
                $stmt_webuser->bind_param("ss", $webuser_email, $usertype);
            
                if ($stmt_webuser->execute()) {
                    // Insertion into 'webuser' successful
                    echo "Webuser registration successful.";
            
                    // Use JavaScript to redirect to the trainee.php page
                    echo '<script>window.location.href = "trainee.php";</script>';
                } else {
                    // Insertion into 'webuser' failed
                    echo "Error inserting data into 'webuser': " . $stmt_webuser->error;
                }
            
                // Close the 'webuser' statement
                $stmt_webuser->close();
            } else {
                // Insertion into 'trainee' failed
                echo "Error inserting data into 'trainee': " . $stmt->error;
            }
            
            // Close the 'trainee' statement
            $stmt->close();
            
            // Close the database connection
            $database->close();
            
          }
            
          }
          ?>

          <div class="main-panel">
            <div class="content-wrapper">
              <div class="row">
                <div class="col-12 grid-margin stretch-card">
                  <div class="card">
                    <div class="card-body">
                      <h4 class="card-title">Add Supervisor</h4>

                      <!-- Basic multiple Column Form section start -->
                      <section id="single-column-form">
                        <div class="row match-height">
                          <div class="col-12">
                            <div class="card">
                              <div class="card-content">
                                <div class="card-body">
                                <form action="profile.php" method="POST">
                            <div class="mb-3">
                                <label for="name" class="form-label">
                                    <i class="fa fa-user"></i> Name:
                                </label>
                                <input type="text" name="name" value="<?php echo $userfetch['name']; ?>" class="form-control" readonly>
                            </div>

                            <div class="mb-3">
                                <label for="temail" class="form-label">
                                    <i class="fa fa-envelope"></i> Email:
                                </label>
                                <input type="text" name="temail" value="<?php echo $userfetch['temail']; ?>" class="form-control" readonly>
                            </div>

                            <!-- Supervisor Name Field -->
                                <div class="mb-3">
                                <label for="supervisor" class="form-label">
                                    <i class="fa fa-user"></i> Supervisor:
                                </label>
                                <input type="text" name="supervisor" value="<?php echo $userfetch['supervisor_name']; ?>" class="form-control" readonly>
                            </div>


                              <!-- Department Name Field -->
                              <div class="mb-3">
                                <label for="namedept" class="form-label">
                                    <i class="fa fa-building"></i> Department:
                                </label>
                                <input type="text" name="namedept" value="<?php echo $namedept; ?>" class="form-control" readonly>
                            </div>


                            <div class="mb-3">
                                <label for="new_username" class="form-label">
                                    <i class="fa fa-user"></i> Username:
                                </label>
                                <input type="text" name="new_username" value="<?php echo $userfetch['username']; ?>" class="form-control">
                            </div>


                        


                            <div class="mb-3">
                                <label for="phone_num" class="form-label">
                                    <i class="fa fa-phone"></i> Phone Number:
                                </label>
                                <input type="text" name="phone_num" value="<?php echo $userfetch['phone_num']; ?>" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label for="startdate" class="form-label">
                                    <i class="fa fa-calendar"></i> Start Date:
                                </label>
                                <input type="date" name="startdate" value="<?php echo $userfetch['startdate']; ?>" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label for="endate" class="form-label">
                                    <i class="fa fa-calendar"></i> End Date:
                                </label>
                                <input type="date" name="endate" value="<?php echo $userfetch['endate']; ?>" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label for="courseofstudy" class="form-label">
                                    <i class="fa fa-graduation-cap"></i> Course of Study:
                                </label>
                                <input type="text" name="courseofstudy" value="<?php echo $userfetch['courseofstudy']; ?>" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label for="gender" class="form-label">
                                    <i class="fa fa-venus-mars"></i> Gender:
                                </label>
                                <select name="gender" class="form-select">
                                    <option value="Male" <?php if ($userfetch['gender'] == 'Male') echo 'selected'; ?>>Male</option>
                                    <option value="Female" <?php if ($userfetch['gender'] == 'Female') echo 'selected'; ?>>Female</option>
                                    <option value="Other" <?php if ($userfetch['gender'] == 'Other') echo 'selected'; ?>>Other</option>
                                </select>
                            </div>

                            <div class="col-12 d-flex justify-content-end">
                                <button type="submit" name="update_profile" value="Update Profile" class="btn btn-primary me-1 mb-1">
                                    <i class="fa fa-check"></i> Submit
                                </button>
                            </div>
                            </div>
                        </form>
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
                <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.5.0/js/bootstrap.bundle.min.js"></script>

    <!-- Your custom JavaScript -->
    <script>
        // JavaScript function to show the success modal
        function showSuccessModal() {
            $('#successModal').modal('show'); // Show the modal
        }

        // Check if the form submission was successful and call the function to show the modal
        $(document).ready(function () {
            $('#updateProfileForm').submit(function (event) {
                event.preventDefault(); // Prevent the default form submission
                $.ajax({
                    type: 'POST',
                    url: 'profile.php',
                    data: $('#updateProfileForm').serialize(),
                    success: function (response) {
                        if (response === "success") {
                            showSuccessModal(); // Call the function to show the modal
                        }
                    }
                });
            });
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
  <!-- End custom js for this page-->
</body>

</html>