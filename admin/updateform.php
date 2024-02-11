<?php
include("../connection.php");

$tid = $_GET['tid'];

$sql = "SELECT * FROM trainee WHERE tid = $tid";
$result = $database->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $tid = $row['tid'];
    $traineenumber = $row["traineenumber"];
    $name = $row["name"];
    $deptid = $row["deptid"];
    $temail = $row["temail"];
    $username = $row["username"];
    $tpassword = $row["tpassword"];
    $superid = $row["superid"];
    $phone_num = $row["phone_num"];
    $startdate = $row["startdate"];
    $endate = $row["endate"];
    $courseofstudy = $row["courseofstudy"];
    $gender = $row["gender"];
    $status = $row["status"];
    $uni = $row["uni"];
    $scase = $row["scase"];

    // Fetch subsidiary data
    $sql_sub = "SELECT * FROM subsidiary";
    $result_sub = $database->query($sql_sub);

    // Fetch department data based on the selected subsidiary
    $sql_dept = "SELECT * FROM department WHERE subid = $deptid";
    $result_dept = $database->query($sql_dept);

    // Fetch supervisor data based on the selected department
    $sql_super = "SELECT * FROM supervisor WHERE deptid = $deptid";
    $result_super = $database->query($sql_super);

    // Initialize variables to prevent undefined index warnings
    $subid = $deptid = $superid = '';

    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Update $tid to use POST data for consistency
        $tid = $_POST["tid"];

        $traineenumber = $_POST["traineenumber"];
        $name = $_POST["name"];
        $deptid = $_POST["deptid"];
        $temail = $_POST["temail"];
        $username = $_POST["username"];
        $tpassword = password_hash($_POST["tpassword"], PASSWORD_DEFAULT);
        $superid = $_POST["superid"];
        $phone_num = $_POST["phone_num"];
        $startdate = $_POST["startdate"];
        $endate = $_POST["endate"];
        $courseofstudy = $_POST["courseofstudy"];
        $gender = $_POST["gender"];
        $status = $_POST["status"];
        $uni = $_POST["uni"];
        $scase = isset($_POST["scase"]) ? $_POST["scase"] : ''; // Ensure $scase is defined

        // Update subsidiary, department, and supervisor based on the form data
        $subid = isset($_POST["subid"]) ? $_POST["subid"] : '';
        $deptid = isset($_POST["deptid"]) ? $_POST["deptid"] : '';
        $superid = isset($_POST["superid"]) ? $_POST["superid"] : '';

        // Update the trainee record in the database
        $sql_update = "UPDATE trainee SET 
                        traineenumber='$traineenumber',
                        name='$name',
                        deptid='$deptid',
                        temail='$temail',
                        username='$username',
                        tpassword='$tpassword',
                        superid='$superid',
                        phone_num='$phone_num',
                        startdate='$startdate',
                        endate='$endate',
                        courseofstudy='$courseofstudy',
                        gender='$gender',
                        status='$status',
                        uni='$uni',
                        scase='$scase'
                        WHERE tid=$tid";

        if ($database->query($sql_update) === TRUE) {
            echo '<script>
            alert("Record updated successfully");
            window.location.href = "trainee.php";
          </script>';
            exit;
        } else {
            echo '<script>
            alert("Error updating record: ' . $updateStmt->error . '");
            
          </script>';
        }

        // Update email in the webuser table if it has changed
        if ($temail != $row['temail']) {
            $updateWebUserStmt = $database->prepare("UPDATE webuser SET email = ? WHERE email = ?");
            $updateWebUserStmt->bind_param("ss", $temail, $row['temail']);
            $updateWebUserStmt->execute();
            $updateWebUserStmt->close();
        }
    }
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    // Close the database connection
    $database->close();
} else {
    // Handle the case when no trainee is found with the given tid
    echo "Trainee not found.";
    // You might want to redirect the user to the previous page or show an error message
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Trainee Information</title>
</head>

<body>

    <h2>Update Trainee Information</h2>

    <form action="updateform.php?tid=<?php echo $tid; ?>" method="post" onsubmit="updateScase()">
        <input type="hidden" name="tid" value="<?php echo $tid; ?>">


        <label for="traineenumber">Trainee Number:</label>
        <input type="text" id="traineenumber" name="traineenumber" value="<?php echo $traineenumber; ?>" required><br>

        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?php echo $name; ?>" required><br>




        <label for="temail">Email:</label>
        <input type="text" id="temail" name="temail" value="<?php echo $temail; ?>" required><br>

        <label for="username">Username:</label>
        <input type="text" id="username" name="username" value="<?php echo $username; ?>" required><br>

        <label for="tpassword">Password:</label>
        <input type="password" id="tpassword" name="tpassword" value="<?php echo $tpassword; ?>" required>

        <!-- Add a reset button -->
        <button type="button" onclick="resetPassword()">Reset Password</button><br>



        <!-- Add these fields to your form -->
        <label for="subid">Subsidiary:</label>
        <select name="subid" class="form-control" id="subid">
            <?php
            // Fetch subsidiary data
            $sql_sub = "SELECT * FROM subsidiary";
            $result_sub = $database->query($sql_sub);

            // Check if there is subsidiary data
            if ($result_sub->num_rows > 0) {
                while ($sub_row = $result_sub->fetch_assoc()) {
                    $sub_option_value = $sub_row['subid'];
                    $sub_option_text = $sub_row['name']; // Change this to the actual column name in your table

                    // Check if the current subsidiary is the selected one (based on $deptid)
                    $selected = ($subid == $sub_option_value) ? 'selected' : '';

                    // Output the option element
                    echo "<option value=\"$sub_option_value\" $selected>$sub_option_text</option>";
                }
            } else {
                echo '<option value="" disabled>No subsidiaries found</option>';
            }
            ?>
        </select><br>

        <label for="deptid">Department ID:</label>
        <select name="deptid" class="form-control" id="deptid">
            <!-- Populate this dropdown with options based on the selected subsidiary -->
            <!-- Example: <option value="1" <?= ($deptid == 1) ? 'selected' : ''; ?>>Department 1</option> -->
        </select><br>

        <label for="superid">Supervisor:</label>
        <select name="superid" class="form-control" id="superid">
            <!-- Populate this dropdown with options based on the selected department -->
            <!-- Example: <option value="1" <?= ($superid == 1) ? 'selected' : ''; ?>>Supervisor 1</option> -->
        </select><br>






        <label for="phone_num">Phone Number:</label>
        <input type="text" id="phone_num" name="phone_num" value="<?php echo $phone_num; ?>"><br>

        <label for="startdate">Start Date:</label>
        <input type="text" id="startdate" name="startdate" value="<?php echo $startdate; ?>"><br>

        <label for="endate">End Date:</label>
        <input type="text" id="endate" name="endate" value="<?php echo $endate; ?>"><br>

        <label for="courseofstudy">Course of Study:</label>
        <input type="text" id="courseofstudy" name="courseofstudy" value="<?php echo $courseofstudy; ?>"><br>

        <label for="gender">Gender:</label>
        <input type="text" id="gender" name="gender" value="<?php echo $gender; ?>"><br>


        <label for="uni">University:</label>
        <input type="text" id="uni" name="uni" value="<?php echo $uni; ?>"><br>

        <label for="status">Status:</label>
        <select name="status" class="form-control" id="status" onchange="toggleScase()">
            <option value="1" <?= ($status == 1) ? 'selected' : ''; ?>>Active</option>
            <option value="2" <?= ($status == 2) ? 'selected' : ''; ?>>Inactive</option>
            <option value="3" <?= ($status == 3) ? 'selected' : ''; ?>>Special Case</option>
        </select><br>

        <!-- Special Case -->
        <label for="scase">Special Case:</label>
        <select name="scase" class="form-control" id="scase" <?php echo ($status == 3) ? '' : 'disabled'; ?>>
            <option value="Not Applicable" <?= ($scase == 'Not Applicable') ? 'selected' : '' ?>>Not Applicable</option>
            <option value="Health Issues" <?= ($scase == 'Health Issues') ? 'selected' : '' ?>>Health Issues</option>
            <option value="Personal Reasons" <?= ($scase == 'Personal Reasons') ? 'selected' : '' ?>>Personal Reasons</option>
            <option value="Family Emergency" <?= ($scase == 'Family Emergency') ? 'selected' : '' ?>>Family Emergency</option>
            <option value="Death in the Family" <?= ($scase == 'Death in the Family') ? 'selected' : '' ?>>Death in the Family</option>
            <option value="Death of Trainee" <?= ($scase == 'Death of Trainee') ? 'selected' : '' ?>>Death of Trainee</option>
            <option value="Job Opportunity" <?= ($scase == 'Job Opportunity') ? 'selected' : '' ?>>Job Opportunity</option>
            <option value="Financial Constraints" <?= ($scase == 'Financial Constraints') ? 'selected' : '' ?>>Financial Constraints</option>
            <option value="Change of Career Path" <?= ($scase == 'Change of Career Path') ? 'selected' : '' ?>>Change of Career Path</option>
            <option value="Graduation" <?= ($scase == 'Graduation') ? 'selected' : '' ?>>Graduation</option>
            <option value="Military Service" <?= ($scase == 'Military Service') ? 'selected' : '' ?>>Military Service</option>
            <option value="Relocation" <?= ($scase == 'Relocation') ? 'selected' : '' ?>>Relocation</option>
            <option value="Personal Development" <?= ($scase == 'Personal Development') ? 'selected' : '' ?>>Personal Development</option>
            <!-- Add more options as needed -->
            <option value="Other" <?= ($scase == 'Other') ? 'selected' : '' ?>>Other</option>
        </select><br>

        <button type="submit">Update Information</button>
    </form>

    <script>
        function toggleScase() {
            var statusSelect = document.getElementById('status');
            var scaseSelect = document.getElementById('scase');

            if (statusSelect.value == '3') {
                scaseSelect.disabled = false;
            } else {
                scaseSelect.disabled = true;
                // Set the value to "Not Applicable" when status is 1 or 2
                scaseSelect.value = 'Not Applicable';
            }
        }

        // Initial call to set the initial state based on the current value of 'status'
        toggleScase();
    </script>

    <script>
        function resetPassword() {
            // Create a new text input field
            var newPasswordInput = document.createElement("input");
            newPasswordInput.type = "text";
            newPasswordInput.value = '1224';

            // Replace the existing password input with the new one
            var existingPasswordInput = document.getElementById('tpassword');
            existingPasswordInput.parentNode.replaceChild(newPasswordInput, existingPasswordInput);

            // Wait for 1 second and then replace the text input with a password input
            setTimeout(function() {
                var newPasswordInput = document.createElement("input");
                newPasswordInput.type = "password";
                newPasswordInput.value = '1224';

                existingPasswordInput.parentNode.replaceChild(newPasswordInput, existingPasswordInput);
            }, 1000);
        }
    </script>
    <!-- ... (Your HTML head and body tags) ... -->

    <script>
        // Function to load departments based on the selected subsidiary
        function loadDepartments() {
            var subid = document.getElementById('subid').value;
            var initialDeptid = document.getElementById('initialDeptid').value;
            var deptidSelect = document.getElementById('deptid');
            var superidSelect = document.getElementById('superid');

            // Reset department and supervisor dropdowns
            deptidSelect.innerHTML = '<option value="">Select Department</option>';
            superidSelect.innerHTML = '<option value="">Select Supervisor</option>';

            // If a subsidiary is selected, fetch departments associated with it
            if (subid !== '') {
                fetch(`get_depart.php?subid=${subid}`)
                    .then(response => response.json())
                    .then(data => {
                        // Populate the department dropdown with fetched data
                        data.forEach(department => {
                            var option = document.createElement('option');
                            option.value = department.deptid;
                            option.text = department.namedept;
                            // Set the selected option based on the initial department value
                            option.selected = (department.deptid == initialDeptid) ? true : false;
                            deptidSelect.add(option);
                        });

                        // Enable the department dropdown
                        deptidSelect.disabled = false;

                        // Trigger the function to load supervisors based on the selected department
                        loadSupervisors();
                    })
                    .catch(error => console.error('Error:', error));
            } else {
                // If no subsidiary is selected, disable the department and supervisor dropdowns
                deptidSelect.disabled = true;
                superidSelect.disabled = true;
            }
        }

        // Function to load supervisors based on the selected department
        function loadSupervisors() {
            var deptid = document.getElementById('deptid').value;
            var superidSelect = document.getElementById('superid');

            // Reset supervisor dropdown
            superidSelect.innerHTML = '<option value="">Select Supervisor</option>';

            // If a department is selected, fetch supervisors associated with it
            if (deptid !== '') {
                fetch(`get_super.php?deptid=${deptid}`)
                    .then(response => response.json())
                    .then(data => {
                        // Populate the supervisor dropdown with fetched data
                        data.forEach(supervisor => {
                            var option = document.createElement('option');
                            option.value = supervisor.superid;
                            option.text = supervisor.name;
                            superidSelect.add(option);
                        });

                        // Enable the supervisor dropdown
                        superidSelect.disabled = false;
                    })
                    .catch(error => console.error('Error:', error));
            } else {
                // If no department is selected, disable the supervisor dropdown
                superidSelect.disabled = true;
            }
        }

        // Initial call to set the initial state based on the current value of 'subid'
        loadDepartments();
    </script>

    <!-- ... (Your HTML closing tags) ... -->




</body>

</html>