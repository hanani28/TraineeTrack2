<?php
session_start();

if (isset($_SESSION["user"])) {
  if ($_SESSION["user"] == "" or $_SESSION['usertype'] != 't') {
    header("location: ../login.php");
    exit;
  } else {
    $useremail = $_SESSION["user"];
  }
} else {
  header("location: ../login.php");
  exit;
}

include("../connection.php"); // Make sure to include the file that establishes the database connection

$userrow = $database->query("select * from trainee where temail='$useremail'");

if (!$userrow) {
  die("Database error: " . $database->error);
}

$userfetch = $userrow->fetch_assoc();
$userid = $userfetch["tid"];
$username = $userfetch["name"];

if (!$database) {
  die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['event_id'])) {
  $event_id = $_POST['event_id'];
} elseif ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['event_id'])) {
  $event_id = $_GET['event_id'];
} else {
  echo "Event ID not provided in the query string or POST data.";
  exit;
}

$event_query = "SELECT * FROM events WHERE id = $event_id";
$event_result = mysqli_query($database, $event_query);

if (!$event_result) {
  die("Event query failed: " . mysqli_error($database));
}

if (mysqli_num_rows($event_result) > 0) {
  $event_row = mysqli_fetch_assoc($event_result);
} else {
  echo "Event not found.";
  exit;
}

$checkQuery = "SELECT COUNT(*) as unchecked_count FROM `minitask` WHERE `id` = $event_id AND `status` = 0";

$result = mysqli_query($database, $checkQuery);

if (!$result) {
  die("Mini-task query failed: " . mysqli_error($database));
}

$row = mysqli_fetch_assoc($result);
$uncheckedCount = $row['unchecked_count'];

if ($uncheckedCount == 0) {
  // Update the event status to 1
  $updateQuery = "UPDATE events SET status = 1 WHERE id = $event_id";

  if (mysqli_query($database, $updateQuery)) {
    echo "Event status updated successfully.";
  } else {
    echo "Error updating event status: " . mysqli_error($database);
  }
} else {
  // If not all mini-tasks are checked, you can set the event status to 0 here if needed.
  // For example:
  $updateQuery = "UPDATE events SET status = 0 WHERE id = $event_id";
  mysqli_query($database, $updateQuery);
}
?>
<!-- Your HTML code here -->


<!DOCTYPE html>
<html>

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
              <i class="icon-paper menu-icon"></i>
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
            <a class="nav-link" href="task.php">
              <i class="icon-paper menu-icon"></i>
              <span class="menu-title">Calender</span>
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

      <div class="content-wrapper">
        <div class="row">
          <div class="col-md-6 grid-margin stretch-card">
            <div class="card">

              <div class="card-body">
                <p class="card-title mb-0">Task Description</p>
                <div class="table-responsive">
                  <table class="table table table-borderless">
                    <!-- <tr>
                      <th>Trainee Name</th>
                      <td><?php echo $event_row['name']; ?></td>
                    </tr> -->
                    <tr>
                      <th>Title</th>
                      <td><?php echo $event_row['title']; ?></td>
                    </tr>
                    <tr>
                      <th>Description</th>
                      <td><?php echo $event_row['description']; ?></td>
                    </tr>
                    <tr>
                      <th>Start Date</th>
                      <td><?php echo $event_row['start_date']; ?></td>
                    </tr>
                    <tr>
                      <th>End Date</th>
                      <td><?php echo $event_row['end_date']; ?></td>
                    </tr>
                    <tr>
                      <th>Status</th>
                      <td><?php echo $event_row['status'] == 1 ? 'Completed' : 'Pending'; ?></td>
                    </tr>
                  </table>
                </div>
              </div>

              <?php
              if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id"]) && isset($_POST["comment"])) {
                // Process form data
                $comment = $_POST["comment"];
                $id = $_POST["id"];
                $superid = $_POST["superid"];
                $timestamp = $_POST["timestamp"];

                // Check if the specified 'id' exists in the 'events' table
                $checkEventExists = $database->query("SELECT id FROM events WHERE id='$id'");

                if ($checkEventExists->num_rows > 0) {
                  // The event with the specified 'id' exists, proceed to insert the comment
                  $sql = "INSERT INTO comments (comment, timestamp, superid, id) VALUES ('$comment', '$timestamp', '$superid', '$id')";

                  if ($database->query($sql) === TRUE) {
                    echo "Comment added successfully";
                  } else {
                    echo "Error: " . $sql . "<br>" . $database->error;
                  }
                } else {
                  echo "Error: Event with id='$id' does not exist";
                }
              }

              ?>
              <div class="card-body">
                <p class="card-title mb-0">Comments Section</p>
                <div class="table-responsive">
                  <div class="card-body" style="height: 300px; overflow-y: auto;">
                    <table class="table table-striped custom-table">
                      <thead>
                        <tr>
                          <th>Comment</th>
                          <th>Timestamp</th>
                          <th>Supervisor</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        // Fetch comments for the current event
                        $commentsQuery = $database->query("SELECT c.*, s.name as supername FROM comments c INNER JOIN supervisor s ON c.superid = s.superid WHERE c.id = $event_id ORDER BY c.timestamp DESC");



                        if ($commentsQuery) {
                          while ($comment = $commentsQuery->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>{$comment['comment']}</td>";
                            echo "<td>{$comment['timestamp']}</td>";
                            echo "<td>{$comment['supername']}</td>";
                            echo "</tr>";
                          }
                        } else {
                          echo "<tr><td colspan='3'>Error fetching comments: " . $database->error . "</td></tr>";
                        }
                        ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>

            <?php
            // Query to retrieve related mini-tasks
            $minitask_query = "SELECT * FROM minitask WHERE id = $event_id";
            $minitask_result = mysqli_query($database, $minitask_query);
            ?>
            <div class="col-md-9 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <p class="card-title mb-0">Subtask</p>
                  <div class="card-body" style="height: 310px; overflow-y: auto;">
                    <div class="table-responsive">
                      <table class="table table table-border">
                        <?php if (mysqli_num_rows($minitask_result) > 0) : ?>


                          <tr>
                            <th>Subtask</th>
                            <th>Status</th>
                          </tr>
                          <?php while ($minitask_row = mysqli_fetch_assoc($minitask_result)) : ?>
                            <tr>
                              <td>
                                <div class="form-check form-check-primary">
                                  <label class="form-check-label">
                                    <input type="checkbox" id="minitask_<?php echo $minitask_row['minID']; ?>" data-minitaskid="<?php echo $minitask_row['minID']; ?>" class="minitask-checkbox form-check-input" <?php echo $minitask_row['status'] == 1 ? 'checked' : ''; ?>>
                                    <?php echo $minitask_row['subtask']; ?>
                                  </label>
                                </div>
                              </td>
                              <td><?php echo $minitask_row['status'] == 1 ? 'Completed' : 'Pending'; ?></td>
                            </tr>
                          <?php endwhile; ?>
                      </table>



                    <?php endif; ?>

                    <?php if (mysqli_num_rows($minitask_result) == 0) : ?>
                      <p>No Subtask found for this Main Task.</p>
                    <?php endif; ?>

                    <?php
                    // Close the database connection
                    mysqli_close($database);
                    ?>

                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>


</body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Include jQuery library -->

<script type="text/javascript">
  $(document).ready(function() {
    $('#updateMinitaskStatusButton').click(function() {
      $('.minitask-checkbox').each(function() {
        var minitaskId = $(this).data('minitaskid');
        var isChecked = $(this).is(":checked") ? 1 : 0;

        // Update the mini-task status via AJAX
        $.ajax({
          type: "POST",
          url: "update_minitask.php", // Create a PHP script to update the status
          data: {
            minitaskId: minitaskId,
            isChecked: isChecked
          },
          success: function() {
            console.log("Mini-Task status updated successfully.");
          },
          error: function(err) {
            console.error("Error updating mini-task status: " + err.responseText);

          }
        });
      });
    });
  });
</script>
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

</html>