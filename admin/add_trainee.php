<?php
// Include your database connection code here
include("../connection.php");
// Fetch subsidiary data
$subsidary_query = $database->query("SELECT * FROM subsidiary");
$subsidaries = $subsidary_query->fetch_all(MYSQLI_ASSOC);

// Function to generate the next trainee number based on the pattern
function generateTraineeNumber()
{
  global $database;

  $result = $database->query("SELECT MAX(traineenumber) AS max_number FROM trainee");
  $row = $result->fetch_assoc();
  $maxNumber = $row['max_number'];

  if ($maxNumber) {
    $prefix = substr($maxNumber, 0, 1);
    $suffix = (int)substr($maxNumber, 1);

    if ($suffix < 9999) {
      $suffix++;
    } else {
      $prefix++;
      $suffix = 1;
    }
  } else {
    // Initial value if no existing trainee numbers
    $prefix = 'A';
    $suffix = 1;
  }

  $newTraineeNumber = $prefix . sprintf("%04d", $suffix);
  return $newTraineeNumber;
}

// echo generateTraineeNumber();


// Fetch department data (assuming a function getDepartments($subsidaryId) exists)
function getDepartments($subsidaryId)
{
  global $database;
  $department_query = $database->query("SELECT * FROM department WHERE subid = $subsidaryId");
  return $department_query->fetch_all(MYSQLI_ASSOC);
}

// Fetch supervisor data (assuming a function getSupervisors($departmentId) exists)
function getSupervisors($departmentId)
{
  global $database;
  $supervisor_query = $database->query("SELECT * FROM supervisor WHERE deptid = $departmentId AND status = 1");
  return $supervisor_query->fetch_all(MYSQLI_ASSOC);
}
// ...

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Retrieve form data
  $subsidiaryId = $_POST["subsidiary"];
  $departmentId = $_POST["department"];
  $supervisorId = $_POST["supervisor"];
  $traineenumber = $_POST["traineenumber"];
  $name = $_POST["name"];
  $email = $_POST["email"];
  $username = $_POST["username"];
  $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
  $phoneNum = $_POST["phone_num"];
  $startDate = $_POST["startdate"];
  $endDate = $_POST["endate"];
  $courseOfStudy = $_POST["courseofstudy"];
  $gender = $_POST["gender"];
  $status = 1;
  $uni = $_POST["uni"];

  // Check if a file is uploaded
  $imageData = isset($_FILES["image_data"]["tmp_name"]) ? file_get_contents($_FILES["image_data"]["tmp_name"]) : null;

  // Process and insert data into the 'trainee' table
  // You need to perform proper validation and sanitization of data

  // Example SQL query
  // Example SQL query
  $sql = "INSERT INTO trainee (traineenumber, name, deptid, superid, temail, username, tpassword, phone_num, startdate, endate, courseofstudy, gender, status, uni, image_data) VALUES (?,?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
  $stmt = $database->prepare($sql);
  $stmt->bind_param("ssiisssssssssss", $traineenumber, $name, $departmentId, $supervisorId, $email, $username, $password, $phoneNum, $startDate, $endDate, $courseOfStudy, $gender, $status, $uni, $imageData);


  if ($stmt->execute()) {
    // Insertion into 'trainee' successful
    echo "Trainee registration successful.";

    // Insert data into the 'webuser' table
    $webuser_email = $email;
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
?>


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
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

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
                      <h4 class="card-title">Add Trainee</h4>
                      <form id="insertForm" action="add_trainee.php" method="post" enctype="multipart/form-data">
                        <!-- ... existing form fields ... -->

                        <!-- File input field for the image -->
                        <div class="form-group has-icon-left">
                          <label for="image">Select an image:</label>
                          <div class="position-relative">
                            <input type="file" name="image_data" id="image_data" accept="image/*" required>
                            <div class="form-control-icon">
                              <i class="fa fa-image"></i>
                            </div>
                          </div>
                        </div>
                        <!-- Auto-generated trainee number using ajax refer the script section -->

                        <!-- Add this section to your HTML form -->
                        <!-- Add this section to your HTML form -->
                        <label for="traineenumber">Trainee Number:</label>
                        <input type="text" id="traineenumber" name="traineenumber" readonly required><br>

                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name" required><br>


                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" onblur="checkEmail()" required>
                        <span id="emailError" style="color: red;"></span><br>

                        <label for="username">Username:</label>
                        <input type="text" class="form-control" placeholder="Username" name="username" oninput="generatePassword()" required>


                        <label for="password">Password:</label>
                        <input type="password" id="password" name="password" required><br>

                        <label for="phone_num">Phone Number:</label>
                        <input type="text" id="phone_num" name="phone_num" required><br>

                        <label for="startdate">Start Date:</label>
                        <input type="date" id="startdate" name="startdate" required><br>

                        <label for="endate">End Date:</label>
                        <input type="date" id="endate" name="endate" required><br>

                        <label for="uni">Institute:</label>
                        <input type="text" id="uni" name="uni" required><br>

                        <label for="courseofstudy">Program:</label>
                        <input type="text" id="courseofstudy" name="courseofstudy" required><br>

                        <label for="gender">Gender:</label>
                        <select id="gender" name="gender" required>
                          <option value="male">Male</option>
                          <option value="female">Female</option>
                        </select><br>



                        <!-- Subsidiary Dropdown -->
                        <label for="subsidiary">Company:</label>
                        <select id="subsidiary" name="subsidiary" onchange="getDepartments()">
                          <?php foreach ($subsidaries as $subsidiary) : ?>
                            <option value="<?php echo $subsidiary['subid']; ?>"><?php echo $subsidiary['subname']; ?></option>
                          <?php endforeach; ?>
                        </select><br>

                        <!-- Department Dropdown -->
                        <label for="department">Division:</label>
                        <select id="department" name="department" onchange="getSupervisors()">
                          <!-- Options will be populated using JavaScript -->
                        </select><br>

                        <!-- Supervisor Dropdown -->
                        <label for="supervisor">Supervisor:</label>
                        <select id="supervisor" name="supervisor" required>
                          <!-- Options will be populated using JavaScript -->
                        </select><br>

                        <!-- <label for="status">Status:</label>
                          <input type="text" id="status" name="status"><br> -->

                        <input type="submit" value="Submit">
                      </form>

                      <!-- Add this script tag to include Font Awesome for the icons -->
                      <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>

                      <script>
                        function generatePassword() {
                          // Get the username from the input field
                          var username = document.querySelector('input[name="username"]').value;

                          // Generate the default password by adding "123" to the username
                          var defaultPassword = username + "123";

                          // Set the generated password in the password input field
                          document.querySelector('input[name="password"]').value = defaultPassword;
                        }
                      </script>


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

                        function getSupervisors() {
                          var departmentId = $('#department').val();

                          // Perform an AJAX request to get supervisors for the selected department
                          $.ajax({
                            url: 'get_supervisor.php?supervisors', // Update the URL to include 'supervisors'
                            method: 'GET',
                            dataType: 'json',
                            data: {
                              departmentId: departmentId
                            },
                            success: function(data) {
                              // Populate the 'supervisor' dropdown with the retrieved data
                              var supervisorDropdown = $('#supervisor');
                              supervisorDropdown.empty(); // Clear existing options

                              // Append new options based on the retrieved data
                              $.each(data, function(index, item) {
                                supervisorDropdown.append($('<option>', {
                                  value: item.superid,
                                  text: item.name
                                }));
                              });
                            },
                            error: function(error) {
                              console.error('Error fetching supervisor data:', error);
                            }
                          });
                        }
                      </script>

                      <script>
                        document.addEventListener("DOMContentLoaded", function() {
                          generateTraineeNumber();
                        });

                        function generateTraineeNumber() {
                          // Make an AJAX call to get the next trainee number
                          var xhttp = new XMLHttpRequest();
                          xhttp.onreadystatechange = function() {
                            if (this.readyState == 4 && this.status == 200) {
                              document.getElementById("traineenumber").value = this.responseText;
                            }
                          };
                          xhttp.open("GET", "generate_traineenumber.php", true);
                          xhttp.send();
                        }
                      </script>
                      <script>
                        function checkEmail() {
                          var email = $('#email').val();

                          $.ajax({
                            url: 'check_email.php',
                            method: 'POST',
                            data: {
                              email: email
                            },
                            success: function(response) {
                              if (response === 'exists') {
                                $('#emailError').html('Email already exists.');
                              } else {
                                $('#emailError').html('');
                              }
                            },
                            error: function(error) {
                              console.error('Error checking email:', error);
                            }
                          });
                        }
                      </script>

                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
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
</body>

</html>