<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
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
  <!-- endinject -->
  <link rel="shortcut icon" href="images/favicon.png" />
</head>
<style>
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

  /* Additional styling for the initials */
  .initials-container {
    background-color: #aebaf5;
    /* Add your desired background color */
    color: #FFFFFF;
    /* Add your desired text color */
    border-radius: 50%;
    width: 40px;
    /* Add your desired width */
    height: 40px;
    /* Add your desired height */
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 17px;
    /* Add your desired font size */
    font-weight: bold;
  }
</style>

<?php

//learn from w3schools.com

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


//   echo $userid;
//echo $username;

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
            <div class="col-md-12 grid-margin">
              <div class="row">
                <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                  <h3 class="font-weight-bold">Welcome <?php echo $username; ?></h3>
                  <h6 class="font-weight-normal mb-0">All systems are running smoothly! You have <span class="text-primary">3 unread alerts!</span></h6>
                </div>

              </div>
            </div>
          </div>
          <div class="row">
            <?php
            // Set the timezone to Malaysia
            date_default_timezone_set('Asia/Kuala_Lumpur');

            // Get the current time in Malaysia
            $current_time = date('H:i'); // Format it as HH:mm

            // Replace 'YOUR_API_KEY' with your actual OpenWeatherMap API key
            $api_key = 'd56341dd7d83f104ae364931491f57b3';

            // Function to fetch weather data from the API
            function getWeatherData($url)
            {
              $response = file_get_contents($url);
              return json_decode($response, true);
            }

            // Get weather data for your location (replace 'Your_Location' with your actual location)
            $location = 'Bintulu,Sarawak'; // e.g., 'Bintulu,MY'
            $weather_url = "http://api.openweathermap.org/data/2.5/weather?q=$location&units=metric&appid=$api_key";
            $weather_data = getWeatherData($weather_url);

            // Extract the current temperature
            $temperature = $weather_data['main']['temp'];
            $temperature = round($temperature); // Round to the nearest whole number



            ?>

            <div class="col-md-6 grid-margin stretch-card">
              <div class="card tale-bg">
                <div class="card-people mt-auto">
                  <img src="../images/dashboard/people.svg" alt="people">
                  <div class="weather-info">
                    <div class="d-flex">
                      <div>
                        <h2 class="mb-0 font-weight-normal"><i class="icon-sun mr-2"></i><?php echo $current_time; ?></h2>
                      </div>
                      <div class="ml-2">
                        <h4 class="location font-weight-normal"><?php echo $location; ?></h4>
                        <h6 class="font-weight-normal">Bintulu</h6>
                        <p class="font-weight-normal"><?php echo $temperature; ?><sup>°C</sup></p> <!-- Display the current temperature -->
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <?php
            // Include your database connection code here
            include("../connection.php");

            // // Query to count the number of trainees
            // $queryTrainees = "SELECT COUNT(*) AS traineeCount FROM trainee";
            // $resultTrainees = mysqli_query($database, $queryTrainees);
            // $rowTrainees = mysqli_fetch_assoc($resultTrainees);
            // $traineeCount = $rowTrainees['traineeCount'];

            // Query to count the number of supervisors
            $querySupervisors = "SELECT COUNT(*) AS supervisorCount FROM supervisor";
            $resultSupervisors = mysqli_query($database, $querySupervisors);
            $rowSupervisors = mysqli_fetch_assoc($resultSupervisors);
            $supervisorCount = $rowSupervisors['supervisorCount'];

            // Default query to retrieve all data when no search filter is provided
            $query = "SELECT t.tid, t.status
                            FROM trainee AS t";

            $query_run = mysqli_query($database, $query);

            if ($query_run) {
              $activeCount = 0;
              $notActiveCount = 0;

              while ($items = mysqli_fetch_assoc($query_run)) {
                $status = $items['status'];

                if ($status == 1) {
                  // Active status
                  $activeCount++;
                } elseif ($status == 2) {
                  // Not Active status
                  $notActiveCount++;
                }
              }
            }

            // Query to count the number of trainees under the supervisor
            $query = "SELECT COUNT(*) AS traineeCount FROM trainee WHERE superid = $userid";

            $result = mysqli_query($database, $query);

            // Assuming the query will always succeed, you can directly fetch and display the count
            $row = mysqli_fetch_assoc($result);
            $traineeCount = $row['traineeCount'];

            // Display the trainee count in the HTML
            // echo "<p class='fs-30 mb-2'>$traineeCount</p>";

            // Close the database connection
            mysqli_close($database);


            ?>



            <div class="col-md-6 grid-margin transparent">
              <div class="row">
                <div class="col-md-6 mb-4 stretch-card transparent">
                  <div class="card card-tale">
                    <div class="card-body">
                      <p class="mb-4">Trainee </p>
                      <p class="fs-30 mb-2"><?php echo $traineeCount; ?></p>
                      <!-- <p>10.00% (30 days)</p> -->
                    </div>
                  </div>
                </div>
                <div class="col-md-6 mb-4 stretch-card transparent">
                  <div class="card card-dark-blue">
                    <div class="card-body">
                      <p class="mb-4">Supervisor</p>
                      <p class="fs-30 mb-2"><?php echo $supervisorCount; ?></p>
                      <p>22.00% (30 days)</p>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6 mb-4 mb-lg-0 stretch-card transparent">
                  <div class="card card-light-blue">
                    <div class="card-body">
                      <p class="mb-4">Active Trainees</p>
                      <p class="fs-30 mb-2"><?php echo $activeCount; ?></p>
                    </div>
                  </div>
                </div>
                <div class="col-md-6 stretch-card transparent">
                  <div class="card card-light-danger">
                    <div class="card-body">
                      <p class="mb-4">Not Active Trainees</p>
                      <p class="fs-30 mb-2"><?php echo $notActiveCount; ?></p>
                    </div>
                  </div>
                </div>
              </div>

            </div>


          </div>

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