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
  <title>Data Insert Form</title>
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>
<?php
// Include your database connection code here
include("../connection.php");
// Fetch subsidiary data
$subsidary_query = $database->query("SELECT * FROM subsidiary");
$subsidaries = $subsidary_query->fetch_all(MYSQLI_ASSOC);

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
  $sql = "INSERT INTO trainee (name, deptid, superid, temail, username, tpassword, phone_num, startdate, endate, courseofstudy, gender, status, uni, image_data) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
  $stmt = $database->prepare($sql);
  $stmt->bind_param("siisssssssssss", $name, $departmentId, $supervisorId, $email, $username, $password, $phoneNum, $startDate, $endDate, $courseOfStudy, $gender, $status, $uni, $imageData);


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
                        <form id="insertForm" action="add_trainee2.php" method="post" enctype="multipart/form-data">
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

                          <label for="name">Name:</label>
                          <input type="text" id="name" name="name" required><br>

                          <label for="email">Email:</label>
                          <input type="email" id="email" name="email" required><br>

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


                          <!-- <label for="status">Status:</label>
                          <input type="text" id="status" name="status"><br> -->



                          <!-- Subsidiary Dropdown -->
                          <label for="subsidiary">Subsidiary:</label>
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
                          <select id="supervisor" name="supervisor"required>
                            <!-- Options will be populated using JavaScript -->
                          </select><br>

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

</body>

</html>