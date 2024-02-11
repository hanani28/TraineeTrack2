<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Trainee Track</title>
    <!-- plugins:css -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
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
.card-container {
    display: flex;
    flex-wrap: wrap;
}

.card {
    flex: 0 0 calc(33.333% - 1rem);
    margin: 0.5rem;
    box-sizing: border-box;
    overflow: hidden;
}

.card-img-top {
    width: 100%;
    height: 150px; /* Set a fixed height for images */
    object-fit: cover;
    border-radius: 5px;
}

.card-body {
    flex: 1;
    overflow: hidden;
}

.card-title {
    font-size: 1.25rem;
    white-space: pre-wrap;
    overflow: hidden;
    text-overflow: ellipsis;
    margin-bottom: 0;
}

.list-star {
    font-size: 1rem;
    height: 3em; /* Set a fixed height for list items */
    overflow: hidden;
}

.card-footer {
    display: flex;
    justify-content: space-between;
}

.profile-icon {
    max-width: 32px;
    max-height: 32px;
    border-radius: 50%;
}

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

.popup {
    background: #fff;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
}

.initials-container {
    background-color: #aebaf5;
    color: #FFFFFF;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 17px;
    font-weight: bold;
}
</style>




<?php

// Learn from w3schools.com

session_start();

if (isset($_SESSION["user"])) {
    if (($_SESSION["user"]) == "" or $_SESSION['usertype'] != 's') {
        header("location: ../login.php");
        exit; // Add an exit to prevent further executio
    } else {
        $useremail = $_SESSION["user"];
    }
} else {
    header("location: ../login.php");
    exit;
}

// Import database
include("../connection.php");

// Fetch supervisor data
$userrow = $database->query("SELECT * FROM supervisor WHERE semail='$useremail'");

if (!$userrow) {
    die("Database error: " . $database->error);
}

$userfetch = $userrow->fetch_assoc();

$userid = $userfetch["superid"];
$username = $userfetch["name"];

// Query to fetch trainee data associated with the logged-in supervisor

$traineeQuery = "SELECT *
FROM trainee
WHERE superid = $userid
ORDER BY
    CASE
        WHEN status = 1 THEN 0
        WHEN status = 3 THEN 1
        WHEN status = 2 THEN 2
        ELSE 3
    END,
    startdate ASC;
";
$traineeResult = $database->query($traineeQuery);

if (!$traineeResult) {
    die("Database error: " . $database->error);
}

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
                    <div class="main-panel">
                        <h3 class="card-title text-bold">Trainee assigned to "<?php echo $username; ?>"</h3>

                        <div class="card-columns ">
                            <!-- Loop through trainee data and display each trainee -->
                            <?php
                            while ($traineeData = $traineeResult->fetch_assoc()) {
                                $traineeName = $traineeData["name"];
                                $traineeEmail = $traineeData["temail"];
                                $enddate = $traineeData["endate"];
                                $tid = $traineeData["tid"];
                                $status = $traineeData["status"];

                                switch ($status) {
                                    case 1:
                                        $statusText = "Active";
                                        break;
                                    case 2:
                                        $statusText = "Inactive";
                                        break;
                                    case 3:
                                        $statusText = "Special Case";
                                        break;
                                    default:
                                        $statusText = "Unknown Status";
                                        break;
                                }


                                // Assuming 'id' is the Trainee ID
                                $imageData = base64_encode($traineeData["image_data"]); //  image_data is a BLOB
                            ?>
                                <div class="card">
                                    <div class="card-body">
                                        <div class="card-img-container">
                                            <img class="card-img-top rounded-circle" src="data:image/jpeg;base64,<?php echo $imageData; ?>" alt="Trainee Image" />
                                        </div>
                                        <h4 class="card-title text-info"><?php echo $traineeName; ?></h4>
                                        <ul class="list-star">
                                            <li class="list-star text-primary">Email: <?php echo $traineeEmail; ?></li>
                                            <li class="list-star text-primary">End Of Training: <?php echo $enddate; ?></li>
                                            <li class="list-star text-primary">Status: <?php echo $statusText; ?></li>
                                           
                                        </ul>
                                    </div>
                                    <div class="card-footer">
                                        <!-- Include the 'tid' as a URL parameter in the 'href' -->
                                        <a href="detailprofile.php?tid=<?php echo $tid;?>" class="btn btn-primary">Profile</a>
                                        <a href="taskbytrainee.php?tid=<?php echo $tid;?>" class="btn btn-success">Task</a>
                                    </div>
                                </div>
                            <?php
                            }
                            ?>
                            <!-- End of loop -->
                        </div>
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
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const cardContainer = document.getElementById("cardContainer");
            const prevBtn = document.getElementById("prevBtn");
            const nextBtn = document.getElementById("nextBtn");
            const cardWidth = 300; // Adjust this value based on your card width

            let scrollPosition = 0;

            // Scroll to the previous set of cards
            prevBtn.addEventListener("click", function() {
                scrollPosition -= cardWidth * 3; // Scroll 3 cards at a time
                if (scrollPosition < 0) {
                    scrollPosition = 0;
                }
                cardContainer.style.transform = `translateX(-${scrollPosition}px)`;
            });

            // Scroll to the next set of cards
            nextBtn.addEventListener("click", function() {
                const maxScroll = cardContainer.scrollWidth - cardContainer.clientWidth;
                scrollPosition += cardWidth * 3; // Scroll 3 cards at a time
                if (scrollPosition > maxScroll) {
                    scrollPosition = maxScroll;
                }
                cardContainer.style.transform = `translateX(-${scrollPosition}px)`;
            });
        });
    </script>
    <!-- End custom js for this page-->
</body>

</html>