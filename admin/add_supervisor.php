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

// Check user session
if (isset($_SESSION["user"])) {
  if (($_SESSION["user"]) == "" or $_SESSION['usertype'] != 'a') {
    header("location: ../login.php");
  }
} else {
  header("location: ../login.php");
}

// Import database
include("../connection.php");

// Fetch subsidiary data
$subsidary_query = $database->query("SELECT * FROM subsidiary");
$subsidaries = $subsidary_query->fetch_all(MYSQLI_ASSOC);

function getDepartments($subsidaryId)
{
  global $database;
  $department_query = $database->query("SELECT * FROM department WHERE subid = $subsidaryId");
  return $department_query->fetch_all(MYSQLI_ASSOC);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Retrieve form data
  $name = $_POST["name"];
  $deptid = $_POST["department"];
  $semail = $_POST["semail"];
  $username = $_POST["username"];
  $spassword = password_hash($_POST["spassword"], PASSWORD_DEFAULT);
  $status = 1;

  // Insert data into the supervisor table
  $insertSupervisorQuery = "INSERT INTO supervisor (name, deptid, semail, username, spassword, status) VALUES (?, ?, ?, ?, ?, ?)";
  $stmt = $database->prepare($insertSupervisorQuery);

  if ($stmt) {
    $stmt->bind_param("sisssi", $name, $deptid, $semail, $username, $spassword, $status);

    if ($stmt->execute()) {
      // Insert data into the webuser table
      $insertWebUserQuery = "INSERT INTO webuser (email, usertype) VALUES (?, 's')";
      $stmt2 = $database->prepare($insertWebUserQuery);

      if ($stmt2) {
        $stmt2->bind_param("s", $semail);

        if ($stmt2->execute()) {
          // Close the prepared statement for webuser
          $stmt2->close();
          // Close the prepared statement for supervisor
          $stmt->close();
          // Close the database connection
          $database->close();
          // Redirect to add_user.php with a success message in a popup
          echo '<script>alert("Record is Inserted");</script>';
          echo '<script>window.location.href = "add_supervisor.php";</script>';
          exit();
        } else {
          echo "Error inserting data into webuser table: " . $stmt2->error;
        }
      } else {
        echo "Error preparing statement for webuser table: " . $database->error;
      }
    } else {
      echo "Error inserting data into supervisor table: " . $stmt->error;
    }

    // Close the prepared statement for supervisor
    $stmt->close();
  } else {
    echo "Error preparing statement for supervisor table: " . $database->error;
  }
}
?>


<body>
  <div class="container-scroller">
    <!-- partial:../../partials/_navbar.html -->
    <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
      <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
        <a class="navbar-brand brand-logo mr-5" href="../index.html"><img src="../images/img/logo_bphb.png" class="mr-2" alt="logo" /></a>
        <a class="navbar-brand brand-logo-mini" href="../index.html"><img src=../images/img/logo_bphb.png alt="logo" /></a>
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
          <span class="icon-menu"></span>
        </button>
        <ul class="navbar-nav mr-lg-2">



        </ul>

      </div>
    </nav>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">

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


            <div class="main-panel">
              <div class="content-wrapper">
                <div class="row">
                  <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                      <div class="card-body">
                        <h4 class="card-title">Add Supervisor</h4>


                        <form class="form" method="post" onsubmit="redirectToAddUser()">
                          <div class="row">
                            <div class="col-md-12 col-12">
                              <div class="form-group has-icon-left">
                                <label for="first-name-icon">Name</label>
                                <div class="position-relative">
                                  <input type="text" class="form-control" placeholder="Full name" name="name">
                                  <div class="form-control-icon">
                                    <i class="fa fa-user"></i>
                                  </div>
                                </div>
                              </div>
                            </div>

                            <div class="col-md-12 col-12">
                              <label for="subsidiary">Company:</label>
                              <select id="subsidiary"class="form-control" name="subsidiary" onchange="getDepartments()">
                                <?php foreach ($subsidaries as $subsidiary) : ?>
                                  <option value="<?php echo $subsidiary['subid']; ?>"><?php echo $subsidiary['subname']; ?></option>
                                <?php endforeach; ?>
                              </select>
                            </div>

                            <div class="col-md-12 col-12">
                              <label for="department">Division:</label>
                              <select id="department" class="form-control"name="department" onchange="getSupervisors()">
                                <!-- Options will be populated using JavaScript -->
                              </select>
                            </div>

                            <div class="col-md-8 col-12">
                              <div class="form-group has-icon-left">
                                <label for="first-name-icon">Email</label>
                                <div class="position-relative">
                                  <input type="text" class="form-control" placeholder="Email" name="semail">
                                  <div class="form-control-icon">
                                    <i class="fa fa-envelope"></i>
                                  </div>
                                </div>
                              </div>
                            </div>

                            <div class="col-md-9 col-12">
                              <div class="form-group has-icon-left">
                                <label for="first-name-icon">Username</label>
                                <div class="position-relative">
                                  <input type="text" class="form-control" placeholder="Username" name="username">
                                  <div class="form-control-icon">
                                    <i class="fa fa-user"></i>
                                  </div>
                                </div>
                              </div>
                            </div>

                            <div class="col-md-9 col-12">
                              <div class="form-group has-icon-left">
                                <label for="first-name-icon">Password</label>
                                <div class="position-relative">
                                  <input type="password" class="form-control" placeholder="Password" name="spassword">
                                  <div class="form-control-icon">
                                    <i class="fa fa-key"></i>
                                  </div>
                                </div>
                              </div>
                            </div>

                            <!-- Your form fields here -->

                            <div class="col-12 d-flex justify-content-end">
                              <button type="submit" class="btn btn-primary me-1 mb-1">Submit</button>
                            </div>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>



            <script>
              // JavaScript or jQuery code for populating dropdowns dynamically
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
            </script>


            <script>
              function redirectToAddUser() {
                // You can add any necessary form validation here before redirection

                // Redirect to add_user.php
                window.location.href = "add_supervisor.php";
              }
            </script>
          </div>
          </form>

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