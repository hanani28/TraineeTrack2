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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Retrieve form data
  $subsidiaryId = $_POST["subsidiary"];
  $departmentId = $_POST["department"];
  $supervisorId = $_POST["supervisor"];

  // Check if a file is uploaded
  $imageData = isset($_FILES["image"]["tmp_name"]) ? file_get_contents($_FILES["image"]["tmp_name"]) : null;

  $name = $_POST["name"];
  $email = $_POST["email"];
  $username = $_POST["username"];
  $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
  $phoneNum = $_POST["phone_num"];
  $startDate = $_POST["startdate"];
  $endDate = $_POST["endate"];
  $courseOfStudy = $_POST["courseofstudy"];
  $gender = $_POST["gender"];
  $status = $_POST["status"];
  $uni = $_POST["uni"];

  // Process and insert data into the 'trainee' table
  // You need to perform proper validation and sanitization of data

  // Example SQL query
  $sql = "INSERT INTO trainee (name, deptid, superid, temail, username, tpassword, phone_num, startdate, endate, courseofstudy, gender, image_data, status, uni) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
  $stmt = $database->prepare($sql);

  if (!$stmt) {
    die('Error in SQL query: ' . $database->error);
  }

  $stmt->bind_param("siisssssssssss", $name, $departmentId, $supervisorId, $email, $username, $password, $phoneNum, $startDate, $endDate, $courseOfStudy, $gender, $imageData, $status, $uni);


  if ($stmt->execute()) {
    // Insertion into 'trainee' successful
    echo "Trainee registration successful.";

    // ...
  } else {
    // Insertion into 'trainee' failed
    echo "Error executing statement: " . $stmt->error;
  }
  // $stmt = $database->prepare($sql);
  // $stmt->bind_param("siisssssssssss", $name, $departmentId, $supervisorId, $email, $username, $password, $phoneNum, $startDate, $endDate, $courseOfStudy, $gender, $imageData, $status, $uni);

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
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Data Insert Form</title>
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>

<body>
  <h2>Insert Data</h2>
  <form action="add_trainee2.php" method="POST" enctype="multipart/form-data">

    <div class="form-group has-icon-left">
      <label for="image">Select an image:</label>
      <div class="position-relative">
        <input type="file" name="image" id="image" accept="image/*" required>
        <!-- 'accept' attribute restricts to image files only -->
        <div class="form-control-icon">
          <i class="fa fa-image"></i>8
        </div>
      </div>
    </div>

    <!-- Optional: Input for providing a description of the image -->
    <div class="form-group has-icon-left">
      <!-- <div class="col-md-12 col-12"> -->
      <label for="description">Image Description (optional):</label>
      <div class="position-relative">
        <input type="text" class="form-control" name="description" id="description" class="form-control" placeholder="Image Description">
        <div class="form-control-icon">
          <i class="fa fa-info-circle"></i>
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
    <input type="text" id="phone_num" name="phone_num"><br>

    <label for="startdate">Start Date:</label>
    <input type="text" id="startdate" name="startdate"><br>

    <label for="endate">End Date:</label>
    <input type="text" id="endate" name="endate"><br>

    <label for="courseofstudy">Course of Study:</label>
    <input type="text" id="courseofstudy" name="courseofstudy"><br>

    <label for="gender">Gender:</label>
    <input type="text" id="gender" name="gender"><br>

    <label for="status">Status:</label>
    <input type="text" id="status" name="status"><br>

    <label for="uni">University:</label>
    <input type="text" id="uni" name="uni"><br>

    <!-- Subsidiary Dropdown -->
    <label for="subsidiary">Subsidiary:</label>
    <select id="subsidiary" name="subsidiary" onchange="getDepartments()">
      <?php foreach ($subsidaries as $subsidiary) : ?>
        <option value="<?php echo $subsidiary['subid']; ?>"><?php echo $subsidiary['subname']; ?></option>
      <?php endforeach; ?>
    </select><br>

    <!-- Department Dropdown -->
    <label for="department">Department:</label>
    <select id="department" name="department" onchange="getSupervisors()">
      <!-- Options will be populated using JavaScript -->
    </select><br>

    <!-- Supervisor Dropdown -->
    <label for="supervisor">Supervisor:</label>
    <select id="supervisor" name="supervisor">
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