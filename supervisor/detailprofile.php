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
<style>
  /* styles.css */

  /* Profile image container */
  .profile-image {
    text-align: center;
    margin-bottom: 20px;
  }

  /* Profile image */
  .profile-image img {
    max-width: 100%;
    height: auto;
    border-radius: 50%;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
  }

  /* No image available message */
  .no-image {
    font-size: 18px;
    color: #ccc;
  }

  /* Table styles */
  .table {
    width: 100%;
  }

  /* Label column */
  .table .label {
    font-weight: bold;
    width: 30%;
  }

  /* Value column */
  .table .value {
    width: 70%;
  }

  /* Status badge */
  .status .badge {
    font-size: 14px;
    padding: 5px 10px;
    border-radius: 20px;
  }

  /* Back button */
  .back-button {
    text-align: right;
    margin-top: 20px;
  }

  /* Card background color */
  .card {
    background-color: #f5f5f5;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
  }

  /* Card title styles */
  .card-title {
    font-size: 24px;
    margin-bottom: 10px;
  }

  /* Card description styles */
  .card-description {
    font-size: 20px;
    margin-bottom: 20px;
  }
</style>
<?php

//learn from w3schools.com

session_start();

if(isset($_SESSION["user"])){
    if(($_SESSION["user"])=="" or $_SESSION['usertype']!='s'){
        header("location: ../login.php");
    }else{
        $useremail=$_SESSION["user"];
    }

}else{
    header("location: ../login.php");
}


//import database
include("../connection.php");
$userrow = $database->query("select * from supervisor where semail='$useremail'");

if (!$userrow) {
    die("Database error: " . $database->error);
}

$userfetch = $userrow->fetch_assoc();

$userid= $userfetch["superid"];
$username=$userfetch["name"];


//   echo $userid;
//echo $username;

?>

<?php


// Include your database connection code here
include("../connection.php");

// Check if the 'tid' parameter is set in the URL
if (isset($_GET["tid"])) {
  $tid = $_GET["tid"];

  // Query to retrieve trainee information based on the 'tid'
  $query = "SELECT t.tid, t.name AS trainee_name, t.temail, t.deptid, d.namedept, 
  t.superid, s.name AS supervisor_name, t.username, t.tpassword,
  t.phone_num, t.startdate, t.endate, t.courseofstudy, t.gender, t.status, t.uni, t.image_data
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
// Display the trainee's information here in HTML format
?>
<?php
// Include your database connection code here
include("../connection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $tid = $_POST["tid"];
  $newStatus = $_POST["status"];

  // Validate the new status (optional)
  if (!in_array($newStatus, [1, 2, 3])) {
    // Handle invalid status here (e.g., display an error message)
    echo "Invalid status value";
    exit();
  }

  // Update the trainee's status in the database
  $updateQuery = "UPDATE trainee SET status = $newStatus WHERE tid = $tid";
  $result = $database->query($updateQuery);

  if ($result) {
    // Redirect back to the trainee information page or a success page
    header("Location: trainee.php?tid=$tid");
    exit();
  } else {
    // Handle the update error (e.g., display an error message)
    echo "Update failed";
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
            <a class="nav-link" href="Trainee.php">
              <i class="icon-paper menu-icon"></i>
              <span class="menu-title">Trainee</span>
            </a>
          </li>


          <li class="nav-item">
            <a class="nav-link" href="calender.php">
              <i class="icon-user menu-icon"></i>
              <span class="menu-title">Task</span>
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
                      <h4 class="card-title">Trainee Information</h4>
                      <h3 class="card-description">
                        Details for Trainee <?= $trainee['trainee_name']; ?>
                      </h3>

                      <div class="row">
                        <div class="col-md-4">
                          <div class="profile-image">
                            <?php
                            $imageData = $trainee['image_data'];

                            if ($imageData) {
                              echo '<img src="data:image/jpeg;base64,' . base64_encode($imageData) . '" class="img-fluid" alt="Profile Image" />';
                            } else {
                              echo '<div class="no-image">No image available</div>';
                            }
                            ?>
                          </div>
                        </div>
                        <div class="col-md-8">
                          <table class="table">
                            <tr>
                              <td class="label">Name:</td>
                              <td class="value"><?= $trainee['trainee_name']; ?></td>
                            </tr>
                            <tr>
                              <td class="label">Email:</td>
                              <td class="value"><?= $trainee['temail']; ?></td>
                            </tr>
                            <tr>
                              <td class="label">Phone Number:</td>
                              <td class="value"><?= $trainee['phone_num']; ?></td>
                            </tr>
                            <tr>
                              <td class="label">Supervisor:</td>
                              <td class="value"><?= $trainee['supervisor_name']; ?></td>
                            </tr>
                            <tr>
                              <td class="label">Department:</td>
                              <td class="value"><?= $trainee['namedept']; ?></td>
                            </tr>

                            <tr>
                              <td class="label">Username:</td>
                              <td class="value"><?= $trainee['username']; ?></td>
                            </tr>
                            <tr>
                              <td class="label">University:</td>
                              <td class="value"><?= $trainee['uni']; ?></td>
                            </tr>

                            <tr>
                              <td class="label">Start Date:</td>
                              <td class="value"><?= $trainee['startdate']; ?></td>
                            </tr>
                            <tr>
                              <td class="label">End Date:</td>
                              <td class="value"><?= $trainee['endate']; ?></td>
                            </tr>

                            <tr>
                              <td class="label">Course of Study:</td>
                              <td class="value"><?= $trainee['courseofstudy']; ?></td>
                            </tr>
                            <tr>
                              <td class="label">Gender:</td>
                              <td class="value"><?= $trainee['gender']; ?></td>
                            </tr>
                            <tr>
                              <td class="label">Status:</td>
                              <td class="value">
                                <?php
                                $status = $trainee['status']; // Assuming $trainee['status'] holds the status value.

                                // Use a switch statement to determine the text representation of the status.
                                switch ($status) {
                                  case 1:
                                    echo "Active";
                                    break;
                                  case 2:
                                    echo "Non-Active";
                                    break;
                                  case 3:
                                    echo "Special Case";
                                    break;
                                  default:
                                    echo "Unknown";
                                    break;
                                }
                                ?>
                              </td>
                            </tr>


                            <!-- Back button -->
                            <div style="position: fixed; bottom: 20px; right: 20px;">
                              <a href="javascript:history.back()" class="btn btn-primary">Done</a>
                            </div>

                          </table>

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