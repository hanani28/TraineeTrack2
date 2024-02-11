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
        max-width: 150px;
        /* Adjust the maximum width as needed */
        height: auto;
        border-radius: 50%;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        display: block;
        /* Center the image inside the container */
        margin: 0 auto;
        /* Center the image horizontally */
    }


    .trainee-image {
        width: 150px;
        /* Adjust the width as needed */
        height: 150px;
        /* Adjust the height as needed */
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
        width: 60%;
    }

    /* Value column */
    .table .value {
        width: 80%;
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
        font-size: 30px;
        margin-bottom: 10px;
    }

    /* Card description styles */
    .card-description {
        font-size: 30px;
        margin-bottom: 20px;
    }

    .profile-icon {
        max-width: 32px;
        /* Adjust the max-width as needed */
        max-height: 32px;
        /* Adjust the max-height as needed */
        border-radius: 50%;
        /* Ensure it remains circular */
    }

    /* Style for the overlay background */
    .overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        justify-content: center;
        align-items: center;
    }

    /* Style for the popup container */
    .popup {
        background: #fff;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
    }
</style>
<?php

session_start();

if (isset($_SESSION["user"])) {
  if (($_SESSION["user"]) == "" or $_SESSION['usertype'] != 's') {
    header("location: ../login.php");
  } else {
    $useremail = $_SESSION["user"];
  }
} else {
  header("location: ../login.php");
}


//import database
include("../connection.php");
$userrow = $database->query("select * from supervisor where semail='$useremail'");

if (!$userrow) {
  die("Database error: " . $database->error);
}

$userfetch = $userrow->fetch_assoc();

$userid = $userfetch["superid"];
$username = $userfetch["name"];




    // Query to retrieve trainee information based on the 'tid'
    $sql = "SELECT s.*, d.namedept, sub.subname, d.status as dept_status
    FROM supervisor s
    INNER JOIN department d ON s.deptid = d.deptid
    LEFT JOIN subsidiary sub ON d.subid = sub.subid
    WHERE s.superid = $userid";

    $result = $database->query($sql);

    if ($result) {
        if (mysqli_num_rows($result) == 1) {
            $supervisor = mysqli_fetch_assoc($result);
        } else {
            // Handle the case where no results were found (e.g., show an error message or redirect).
            echo "Supervisor not found";
            exit();
        }
    } else {
        // Handle the query execution error (e.g., show an error message).
        echo "Query error: " . $database->error;
        exit();
    }


// Display the trainee's information here in HTML format
?>

         
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

          <?php
          $userid = $userfetch["superid"];
          $username = $userfetch["name"];

          // Assuming $conn is your database connection
          $sql = "SELECT image_data FROM supervisor WHERE tid = $userid";
          $result = mysqli_query($database, $sql);

          if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $profileImage = base64_encode($row['image_data']); // Assuming the image is stored as BLOB
          } else {
            $profileImage = null;
          }

          ?>


          <!-- Inside the navbar, after the search input -->
          <ul class="navbar-nav navbar-nav-right">
            <!-- Add a nav-item for the profile dropdown -->
            <li class="nav-item nav-profile dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <div class="profile-picture-container">
                  <?php if ($profileImage) : ?>
                    <img src="data:image/jpeg;base64,<?= $profileImage ?>" class="img-avatar profile-icon" alt="Profile" id="profilePicture">
                  <?php else : ?>
                    <div class="initials" id="profileInitials"></div>
                  <?php endif; ?>
                </div>
                <!-- <i class="fas fa-cog ml-1"></i> -->
              </a>
              <div class=" dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
                <!-- <a class="dropdown-item">
                <i href="profile.php" class="ti-settings text-primary"></i>
                Profile
              
              </a> -->

                <a class="dropdown-item" href="view_profile.php?tid=<?php echo $userid; ?>">
                  <i class="mdi mdi-account-box"></i>
                  Profile
                </a>

                <a class="dropdown-item" href="#" id="openPopupButton">
                  <i class="mdi mdi-account-key"></i>
                  Change Password
                </a>


              </div>
              <!-- Popup overlay -->

              <div class="overlay" id="popupOverlay">
                <!-- Popup container -->
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title">Update Status</h4>
                      <button id="closePopupButton"> X </button>
                    </div>
                    <div class="modal-body">
                      <div class="popup" id="popupContainer">
                        <form action="changepassword.php?tid=<?php echo $userid; ?>" method="post" id="changePasswordForm">
                          <div class="form-check form-check-inline">
                            <label for="current_password">Current Password:</label>
                            <input type="password" id="current_password" name="current_password" required><br>
                          </div>
                          <div class="form-check form-check-inline">
                            <label for="new_password">New Password:</label>
                            <input type="password" id="new_password" name="new_password" required><br>
                          </div>
                          <div class="form-check form-check-inline">
                            <label for="confirm_password">Confirm New Password:</label>
                            <input type="password" id="confirm_password" name="confirm_password" required><br>
                          </div>

                          <input type="submit" class="btn btn-primary" value="Change Password">
                        </form>

                      </div>
                    </div>
                  </div>
                </div>

            </li>
          </ul>



          <!-- JavaScript to generate initials if the profile picture is not available -->
          <script>
            document.addEventListener("DOMContentLoaded", function() {
              var profilePicture = document.getElementById("profilePicture");
              var profileInitials = document.getElementById("profileInitials");

              // Check if the profile picture is not available
              if (!profilePicture || !profilePicture.complete || typeof profilePicture.naturalWidth === "undefined" || profilePicture.naturalWidth === 0) {
                // Get the account name from PHP
                var accountName = "<?php echo $username; ?>"; // Use PHP variable

                // Generate initials from the account name
                var initials = accountName.split(" ").map(function(word) {
                  return word.charAt(0);
                }).join("").toUpperCase();

                // Display initials with styling
                profileInitials.innerHTML = `<div class="initials-container">${initials}</div>`;
              }
            });


            document.addEventListener("DOMContentLoaded", function() {
              // Get references to elements
              var openPopupButton = document.getElementById("openPopupButton");
              var closePopupButton = document.getElementById("closePopupButton");
              var popupOverlay = document.getElementById("popupOverlay");

              // Function to open the popup
              function openPopup() {
                popupOverlay.style.display = "flex";
              }

              // Function to close the popup
              function closePopup() {
                popupOverlay.style.display = "none";
              }

              // Attach click event handlers
              openPopupButton.addEventListener("click", openPopup);
              closePopupButton.addEventListener("click", closePopup);

              // Optional: Close the popup when clicking outside the popup container
              popupOverlay.addEventListener("click", function(event) {
                if (event.target === popupOverlay) {
                  closePopup();
                }
              });
            });
          </script>

        </ul>

      </div>
    </nav>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">

      <!-- partial:partials/_sidebar.html -->
      <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">


          <li class="nav-item">
            <a class="nav-link" href="index.php">
              <i class="icon-grid menu-icon"></i>
              <span class="menu-title">Dashboard</span>
            </a>
          </li>


          <li class="nav-item">
            <a class="nav-link" href="trainee.php">
              <i class="icon-head menu-icon"></i>
              <span class="menu-title">Trainee</span>
            </a>
          </li>


          <li class="nav-item">
            <a class="nav-link" href="cuba2.php">
              <i class="icon-columns menu-icon"></i>
              <span class="menu-title">Task</span>
            </a>
          </li>


          <li class="nav-item">
            <a class="nav-link" href="../logout.php">
              <i class="icon- menu-icon"></i>
              <span class="menu-title">LogOut</span>
            </a>
          </li>

          <!-- <li class="nav-item">
            <a class="nav-link" href="cuba2.php">
              <i class="icon- menu-icon"></i>
              <span class="menu-title">LogOut</span>
            </a>
          </li> -->




        </ul>
      </nav>

            <!-- partial -->
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="row">

                        <h2>Profile Details</h2>

                        <div class="main-panel">
                            <div class="content-wrapper">
                                <div class="row">
                                    <div class="col-20 grid-margin stretch-card">
                                        <div class="card">
                                            <div class="card-body">
                                                <!-- <h1 class="card-title text-success">Trainee Information</h1> -->
                                                <h2 class="card-description text-primary">
                                                    Account User Details <?= $supervisor['name']; ?>
                                                </h2>

                                                
                                                    <div class="col-md-10">
                                                        <table class="table">
                                                            <tr>
                                                                <td class="label">Name:</td>
                                                                <td class="value"><?= $supervisor['name']; ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="label">Email:</td>
                                                                <td class="value"><?= $supervisor['semail']; ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="label">Phone Number:</td>
                                                                <td class="value"><?= $supervisor['username']; ?></td>
                                                            </tr>
                                                          
                                                            <tr>
                                                                <td class="label">Department:</td>
                                                                <td class="value"><?= $supervisor['namedept'] . '(' . ($supervisor['dept_status'] == 1 ? 'Active' : 'Inactive') . ')'; ?></td>
                                                            </tr>

                                                            <tr>
                                                                <td class="label">Username:</td>
                                                                <td class="value"><?= $supervisor['username']; ?></td>
                                                            </tr>
                                                            
                                                            
                                                    
                                                            <tr>
                                                                <td class="label">Status:</td>
                                                                <td class="value">
                                                                    <?php
                                                                    $status = $supervisor['status']; // Assuming $supervisor['status'] holds the status value.

                                                                    // Use a switch statement to determine the text representation of the status.
                                                                    switch ($status) {
                                                                        case 1:
                                                                            echo "Active";
                                                                            break;
                                                                        case 2:
                                                                            echo "Inactive";
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

                                                            <?php if ($status == 3 && !empty($supervisor['scase'])) : ?>
                                                                <tr>
                                                                    <td class="label">Special Case Explanation:</td>
                                                                    <td class="value">
                                                                        <?php echo $supervisor['scase']; ?>
                                                                    </td>
                                                                </tr>
                                                            <?php endif; ?>




                                                            <!-- Back button -->
                                                            <div style="position: fixed; bottom: 20px; right: 20px;">
                                                                <a href="javascript:history.back()" class="btn btn-primary">Done</a>
                                                            </div>

                                                        </table>

                                                        <!-- content-wrapper ends -->
                                                        <!-- partial:partials/_footer.html -->
                                                        <footer class="footer">
                                                            <div class="d-sm-flex justify-content-center justify-content-sm-between">
                                                                <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2023. Hanani </a> All rights reserved.</span>

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
</body>

</html>