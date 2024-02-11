<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Supervisor</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="../vendors/feather/feather.css">
  <link rel="stylesheet" href="../vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="../vendors/css/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- Plugin css for this page -->
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="../css/vertical-layout-light/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="../images/favicon.png" />
</head>

<?php

//learn from w3schools.com

session_start();

if (isset($_SESSION["user"])) {
  if (($_SESSION["user"]) == "" or $_SESSION['usertype'] != 'a') {
    header("location: ../login.php");
  }
} else {
  header("location: ../login.php");
}


//import database
include("../connection.php");


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

      <!-- partial -->
      <div class="main-panel">

        <div class="content-wrapper">
          <div class="row">
            <form action="" method="GET">
              <div class="input-group mb-3">
                <button type="button" onclick="history.back()" class="btn btn-secondary">Back</button>
                <input type="text" name="search" required value="<?php if (isset($_GET['search'])) {
                                                                    echo $_GET['search'];
                                                                  } ?>" class="form-control" placeholder="Search for a supervisor...">
                <button type="submit" class="btn btn-primary">Search</button>
              </div>
            </form>


          </div>
        </div>

        <div class="content-wrapper">
          <div class="row">
            <div class="col-lg-12 stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">List Of Supervisor</h4>
                  <p class="card-description">
                    <!-- Add class <code>.table</code> -->
                  </p>
                  <div class="table-responsive pt-3">
                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <th>Name</th>
                          <th>Email</th>
                          <th>Department Name</th>
                          <th>Username</th>
                          <th>Status</th>
                          <th>Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        include("../connection.php");

                        function deleteSupervisor($database, $superid)
                        {
                          $query = "DELETE FROM supervisor WHERE superid = '$superid'";
                          $query_run = mysqli_query($database, $query);

                          if ($query_run) {
                            return true; // Deletion was successful
                          } else {
                            return false; // Deletion failed
                          }
                        }

                        // Check if the 'action' parameter is set and it's equal to 'drop'
                        if (isset($_GET['action']) && $_GET['action'] == 'drop') {
                          if (isset($_GET['superid'])) {
                            $superid = $_GET['superid'];

                            // Call the deleteSupervisor function to delete the supervisor
                            $deleted = deleteSupervisor($database, $superid);

                            if ($deleted) {
                              echo "Supervisor deleted successfully.";
                              // Optionally, you can redirect to a specific page after successful deletion.
                              // header("Location: some_page.php");
                              // exit();
                            } else {
                              echo "Failed to delete supervisor.";
                            }
                          }
                        }
                        ?>


                        <?php
                        include("../connection.php");

                        // Default query to retrieve all data when no search filter is provided
                        $query = "SELECT s.superid, s.name, s.semail, d.namedept, s.username, s.status, s.spassword, d.status as dept_status
                        FROM supervisor AS s
                        JOIN department AS d ON s.deptid = d.deptid
                        ORDER BY s.status ASC, s.name";

                        // Check if a search filter is provided
                        if (isset($_GET['search'])) {
                          $filtervalues = $_GET['search'];
                          // Append the WHERE clause to filter the data
                          $query .= " WHERE CONCAT(s.name, s.semail, d.namedept, s.username, s.spassword) LIKE '%$filtervalues%'";
                        }

                        $query_run = mysqli_query($database, $query);

                        if ($query_run) {
                          if (mysqli_num_rows($query_run) > 0) {
                            while ($items = mysqli_fetch_assoc($query_run))
                            // Fetch each row as an associative array
                            {
                        ?>

                              <tr>
                                <td><?= $items['name']; ?></td>
                                <td><?= $items['semail']; ?></td>
                                <td><?= $items['namedept'] . '(' . ($items['dept_status'] == 1 ? 'Active' : 'Inactive') . ')'; ?></td>
                                <td><?= $items['username']; ?></td>
                                <td><?= $items['status'] == 1 ? 'Active' : 'Inactive'; ?></td>


                                <td>

                                  <div style="display:flex;justify-content: center;">
                                    <button class="btn btn-outline-info btn-fw btn-icon-text">
                                      <a href="password.php?reset=1&superid=<?= $items['superid']; ?>">
                                        <i class="ti-reload btn-icon-prepend"></i> Reset Password
                                      </a>
                                    </button>



                                    <div style="display:flex;justify-content: center;">
                                      <button class="btn btn-outline-info btn-fw btn-icon-text">
                                        <a href="list_trainee.php?superid=<?= $items['superid']; ?>">
                                          <i class="ti-eye btn-icon-prepend"></i> View
                                        </a>
                                      </button>


                                      <button type="button" class="btn btn-outline-warning btn-fw btn-icon-text" onclick="openStatusModal(<?= $items['superid']; ?>)">
                                        <i class="ti-alert btn-icon-prepend"></i> Status
                                      </button>

                                      <div id="statusModal<?= $items['superid']; ?>" class="modal fade" role="dialog">
                                        <div class="modal-dialog">
                                          <div class="modal-content">
                                            <div class="modal-header">
                                              <h4 class="modal-title">Update Status</h4>
                                              <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>
                                            <div class="modal-body">

                                              <p>Status:
                                              <h4 class="card-title text-primary">
                                                <?php
                                                // Display "Active" if status is 1, and "Inactive" if status is 2
                                                $status = $items['status'];
                                                echo $status == 1 ? 'Active' : 'Inactive';

                                                ?>
                                              </h4>
                                              </p>
                                              <!-- Add your status update form or content here -->
                                              <form id="statusForm<?= $items['superid']; ?>">
                                                <label for="status">New Status:</label>
                                                <div class="form-check form-check-inline">
                                                  <input type="radio" id="active<?= $items['superid']; ?>" name="status" value="1" class="form-check-input" required>
                                                  <label class="form-check-label" for="active<?= $items['superid']; ?>">Active</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                  <input type="radio" id="inactive<?= $items['superid']; ?>" name="status" value="2" class="form-check-input" required>
                                                  <label class="form-check-label" for="inactive<?= $items['superid']; ?>">Inactive</label>
                                                </div>

                                                <br>
                                                <button type="button" class="btn btn-primary" onclick="updateStatus(<?= $items['superid']; ?>)">Update</button>
                                              </form>
                                            </div>
                                          </div>
                                        </div>
                                      </div>

                                      <script>
                                        function openStatusModal(superid) {
                                          // You can customize this function to load existing status data if needed
                                          $('#statusModal' + superid).modal('show');
                                        }

                                        function updateStatus(superid) {
                                          var newStatus = $('input[name="status"]:checked').val();

                                          // Send an AJAX request to update the status
                                          $.ajax({
                                            type: 'POST',
                                            url: 'supervisorstatus.php',
                                            data: {
                                              superid: superid,
                                              newStatus: newStatus
                                            },
                                            success: function(response) {
                                              // Handle the response from the server, e.g., show a success message
                                              alert(response);
                                              $('#statusModal' + superid).modal('hide');
                                              // You may want to refresh the page or update the status in the table dynamically
                                              location.reload();
                                            },
                                            error: function(error) {
                                              // Handle errors, e.g., show an error message
                                              alert('Error updating status');
                                            }
                                          });
                                        }
                                      </script>





                                      <button type="button" class="btn btn-outline-danger btn-fw btn-icon-text" onclick="confirmDelete(<?= $items['superid']; ?>)">
                                        <i class="ti-alert btn-icon-prepend"></i> Delete
                                      </button>

                                    </div>
                                </td>
                              </tr>
                            <?php
                            }
                          } else {
                            ?>
                            <tr>
                              <td colspan="5">No Record Found</td>
                            </tr>
                        <?php
                          }
                        } else {
                          echo "Query execution failed: " . mysqli_error($database);
                        }
                        ?>

                        <span>
                          <button onclick="redirectToAddSupervisor()" type="button" class="btn btn-inverse-warning btn-fw">
                            <i class="mdi mdi-account-plus"></i> Add Supervisor
                          </button>
                        </span>

                        <script>
                          function redirectToAddSupervisor() {
                            // Redirect to the add_supervisor page
                            window.location.href = 'add_supervisor.php';
                          }
                        </script>

                      </tbody>


                    </table>

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
      <script src="../../vendors/js/vendor.bundle.base.js"></script>
      <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>


      <script>
        function confirmDelete(superid) {
          // Send an AJAX request to check for related trainees
          $.ajax({
            type: 'POST',
            url: 'check_trainee.php', // Replace with the actual file handling related trainees check
            data: {
              superid: superid
            },
            success: function(response) {
              // Parse the JSON response
              var data = JSON.parse(response);

              // Check if there are related trainees
              if (data.hasRelatedTrainees) {
                // Display a pop-up with related trainee information
                var confirmMessage = 'There are related trainees:\n' + data.traineeList.join('\n') + '\nDo you still want to delete this supervisor?';
                if (confirm(confirmMessage)) {
                  // If the user confirms, proceed with deletion
                  proceedWithDeletion(superid);
                } else {
                  // If the user cancels, show a message
                  alert('Deletion canceled.');
                }
              } else {
                // No related trainees, proceed with deletion
                proceedWithDeletion(superid);
              }
            },
            error: function(error) {
              // Handle errors, e.g., show an error message
              alert('Error checking related trainees');
            }
          });
        }

        function proceedWithDeletion(superid) {
          // Send an AJAX request to delete the supervisor
          $.ajax({
            type: 'POST',
            url: 'delete_supervisor.php', // Replace with the actual file handling deletion
            data: {
              superid: superid
            },
            success: function(response) {
              // Handle the response from the server, e.g., show a success message
              alert(response);
              // You may want to refresh the page or update the table dynamically
              location.reload();
            },
            error: function(error) {
              // Handle errors, e.g., show an error message
              alert('Error deleting supervisor');
            }
          });
        }
      </script>


      <!-- plugins:js -->
      <script src="../vendors/js/vendor.bundle.base.js"></script>
      <!-- endinject -->
      <!-- Plugin js for this page -->
      <script src="../js/chartist.js"></script>
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
      <!-- endinject -->
      <!-- Custom js for this page-->
      <!-- End custom js for this page-->
</body>

</html>