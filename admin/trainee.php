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
  <link rel="stylesheet" href="../vendors/css/d.bundle.base.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <link rel="stylesheet" href="../css/vertical-layout-light/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="../images/favicon.png" />
</head>
<style>
  /* Custom style for button labels to set text color to white */
  .custom-btn {
    color: white;
  }

  /* Custom CSS for the table */
  .custom-table {
    width: 100%;
    /* Set the table width to 100% */
  }

  /* Add overflow to create a scrollbar for the table if it exceeds its container's width */
  .custom-table {
    max-width: 100%;
    overflow-x: auto;
  }
</style>

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
          <!-- Search bar -->
          <form action="" method="GET">
            <div class="input-group mb-3">
              <button type="button" onclick="history.back()" class="btn btn-secondary">Back</button>
              <input type="text" name="search" required value="<?php if (isset($_GET['search'])) {
                                                                  echo $_GET['search'];
                                                                } ?>" class="form-control" placeholder="Search data">
              <button type="submit" class="btn btn-primary">Search</button>
            </div>
          </form>

          <!-- Status filter -->
          <form action="" method="GET">
            <div class="input-group mb-3">
              <select name="status_filter" class="form-control">
                <option value="">Filter by Status</option>
                <option value="1" <?php if (isset($_GET['status_filter']) && $_GET['status_filter'] == '1') {
                                    echo 'selected';
                                  } ?>>Active</option>
                <option value="2" <?php if (isset($_GET['status_filter']) && $_GET['status_filter'] == '2') {
                                    echo 'selected';
                                  } ?>>Non-Active</option>
                <option value="3" <?php if (isset($_GET['status_filter']) && $_GET['status_filter'] == '3') {
                                    echo 'selected';
                                  } ?>>Special Case</option>
              </select>
              <button type="submit" class="btn btn-primary">Apply Filter</button>
            </div>
          </form>
        </div>

        <div class="row">
          <div class="col-lg-12 stretch-card">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">Trainee Information</h4>
                <p class="card-description">
                  <!-- Add class <code>.table</code> -->
                </p>
                <div class="table-responsive pt-3">
                  <table class="table table-striped custom-table">
                    <thead>
                      <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Department</th>
                        <th>Status</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>



                      <?php
                      // Include your database connection code here
                      include("../connection.php");

                      // Initialize $tid with a default value or set it to null
                      $tid = null;

                      // Check if the 'id' parameter is set in the URL
                      if (isset($_GET["tid"])) {
                        $tid = $_GET["tid"];

                        // Check if the 'action' parameter is set to 'drop'
                        if ($_GET["action"] == 'drop') {
                          // Delete the trainee record from the database
                          $sql = "DELETE FROM trainee WHERE tid = $tid"; // Assuming 'tid' is the trainee ID field

                          // Perform the query
                          if (mysqli_query($database, $sql)) {
                            // Record deleted successfully
                            echo '<script>window.location.href = "trainee.php";</script>';
                            exit();
                          } else {
                            echo "Error deleting trainee record: " . mysqli_error($database);
                          }
                        }
                      }

                      // Default query to retrieve all data when no search filter is provided
                      // Default query to retrieve all data when no search term or status filter is provided
                      $query =
                        "SELECT t.tid, t.name AS trainee_name, t.temail, t.deptid, d.namedept, d.status as dept_status,
                        t.superid, s.name AS supervisor_name, t.username, t.tpassword,
                        t.phone_num, t.startdate, t.endate, t.courseofstudy, t.gender, t.status, t.uni, t.traineenumber
                        FROM trainee AS t
                        JOIN department AS d ON t.deptid = d.deptid
                        JOIN supervisor AS s ON t.superid = s.superid
                        ORDER BY t.traineenumber ASC
                 
               ";

                      // Check if a search term is provided in the URL
                      if (isset($_GET["search"])) {
                        $searchTerm = $_GET["search"];
                        // Add a WHERE clause to the query to filter by the search term
                        $query .= " WHERE t.name LIKE '%$searchTerm%' OR t.temail LIKE '%$searchTerm%' OR d.namedept LIKE '%$searchTerm%' OR s.name LIKE '%$searchTerm%' OR t.username LIKE '%$searchTerm%' OR t.phone_num LIKE '%$searchTerm%' OR t.courseofstudy LIKE '%$searchTerm%' OR t.uni LIKE '%$searchTerm%'";
                      }

                      // Check if a status filter is provided in the URL
                      if (isset($_GET["status_filter"]) && $_GET["status_filter"] !== "") {
                        $statusFilter = $_GET["status_filter"];
                        // Add a WHERE clause to filter by status
                        $query .= " AND t.status = $statusFilter";
                      }

                      $result = $database->query($query);

                      if ($result) {
                        if (mysqli_num_rows($result) > 0) {
                          while ($trainee = mysqli_fetch_assoc($result)) {
                            $status = $trainee['status'];
                            $status_text = "";

                            // Determine the status text based on the status value
                            switch ($status) {
                            }
                      ?>
                            <tr>
                              <td><?= $trainee['traineenumber']; ?></td>
                              <td><?= $trainee['trainee_name']; ?></td>
                              <td><?= $trainee['temail']; ?></td>
                              <td><?= $trainee['namedept'] . '(' . ($trainee['dept_status'] == 1 ? 'Active' : 'Inactive') . ')'; ?></td>
                              <td><?= ($trainee['status'] == 1) ? 'Active' : (($trainee['status'] == 2) ? 'Inactive' : 'Special case'); ?></td>

                              <td>
                                <div style="display:flex;justify-content: center;">


                                  <button class="btn btn-outline-info btn-fw btn-icon-text" onclick="viewTrainee(<?= $trainee['tid']; ?>)">
                                    <i></i> View
                                  </button>
                                  <script>
                                    function viewTrainee(tid) {
                                      // Define the URL where you want to navigate
                                      var url = 'view_trainee.php?tid=' + tid;

                                      // Use JavaScript to navigate to the URL
                                      window.location.href = url;
                                    }
                                  </script>




                                  <button class="btn btn-outline-success btn-fw btn-icon-text" onclick="updateTrainee(<?= $trainee['tid']; ?>)">
                                    <i></i> Update
                                  </button>

                                  <script>
                                    function updateTrainee(tid) {
                                      // Navigate to cuba3.php with the tid as a query parameter
                                      window.location.href = "update_form.php?tid=" + tid;
                                    }
                                  </script>

                                  <!-- <button class="btn btn-outline-success btn-fw btn-icon-text" onclick="updatepassword(<?= $trainee['tid']; ?>)">
                                    <i></i> password
                                  </button>
                                  <script>
                                    function updatepassword(tid) {
                                      // Navigate to cuba3.php with the tid as a query parameter
                                      window.location.href = "changepassword.php?tid=" + tid;
                                    } -->
                                  </script>



                                  <!-- <button type="button" class="btn btn-outline-danger btn-fw btn-icon-text" onclick="dropTrainee(<?= $trainee['tid']; ?>)">
                                    <i class="ti-trash"></i>
                                  </button> -->
                                  <script>
                                    function dropTrainee(tid) {
                                      // Perform the desired action when the button is clicked
                                      var action = 'drop'; // You can define other actions as well

                                      // Redirect to the appropriate URL based on the action and tid
                                      var url = '?action=' + action + '&tid=' + tid;

                                      // Use JavaScript to navigate to the URL
                                      window.location.href = url;
                                    }
                                  </script>



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
                    </tbody>

                    <!-- Add Trainee button -->
                    <button class="btn btn-inverse-info btn-fw" onclick="redirectToAddSupervisor()">
                      <i class="mdi mdi-account-plus"></i> Add Trainee
                    </button>


                    <!-- JavaScript function for redirection -->
                    <script>
                      function redirectToAddSupervisor() {
                        window.location.href = "add_trainee.php";
                      }
                    </script>


                    <form action="generate_pdf.php" method="post">
                      <button class="btn btn-inverse-danger btn-fw" type="submit">Generate PDF
                        <i class="ti-printer btn-icon-append"></i>
                      </button>
                    </form>

                    <form action="exportcvs.php" method="post">
                      <button class="btn btn-inverse-success btn-fw" type="submit">Export CSV
                        <i class="ti-upload btn-icon-append"></i>
                      </button>
                    </form>


                  </table>
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
      <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2023. <a href="https://www.bootstrapdash.com/" target="_blank">Bootstrap admin template</a> from BootstrapDash. All rights reserved.</span>
      <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Hand-crafted & made with <i class="ti-heart text-danger ml-1"></i></span>
    </div>
  </footer>
  <!-- partial -->
  </div>
  <!-- main-panel ends -->
  </div>
  <!-- page-body-wrapper ends -->
  </div>

  <!-- ... Your HTML code ... -->

  <script src="../../vendors/js/vendor.bundle.base.js"></script>
  <script src="../js/off-canvas.js"></script>
  <script src="../js/hoverable-collapse.js"></script>
  <script src="../js/template.js"></script>
  <script src="../js/settings.js"></script>
  <script src="../js/todolist.js"></script>

  <script>
    $(document).ready(function() {
      $('.status-select').change(function() {
        var form = $(this).closest('form'); // Get the parent form of the select element
        var tid = form.find('input[name="tid"]').val();
        var newStatus = $(this).val();

        console.log("Sending AJAX request with tid: " + tid + " and newStatus: " + newStatus); // Add this line for debugging

        if (confirm("Are you sure you want to update the status?")) {
          $.ajax({
            url: 'trainee.php',
            method: 'POST',
            data: {
              tid: tid,
              newStatus: newStatus
            }, // Include 'newStatus' in the data
            success: function(response) {
              if (response === 'Successful') {
                alert('Status updated successfully.');
              } else {
                alert('Error updating status.');
              }
            },
            error: function(xhr, status, error) {
              console.error('AJAX error:', error);
            }
          });
        }
      });

      // JavaScript for handling the delete operation
      function deleteTrainee(traineeId) {
        console.log('Deleting trainee with ID:', traineeId);
        if (confirm("Are you sure you want to delete this trainee?")) {
          $.ajax({
            url: 'trainee.php',
            method: 'POST',
            data: {
              traineeId: traineeId
            },
            success: function(response) {
              console.log('Delete response:', response);
              if (response === '') {
                location.reload();
              } else {
                alert('Error deleting trainee.');
              }
            },
            error: function(xhr, status, error) {
              console.error('AJAX error:', error);
            }
          });
        }
      }


    });
  </script>
</body>

</html>