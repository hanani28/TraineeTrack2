<?php
include("../connection.php");
session_start();



// Check if the 'tid' parameter is set in the URL
$tid = isset($_GET['tid']) ? $_GET['tid'] : null;

if ($tid === null) {
    // Handle the case where 'tid' is not set
    echo "Trainee data not found: 'tid' parameter is missing.";
    exit();
}
// Fetch trainee data based on the provided tid
$sql_trainee = "SELECT * FROM trainee WHERE tid = ?";
$stmt_trainee = $database->prepare($sql_trainee);
$stmt_trainee->bind_param("i", $tid);
$stmt_trainee->execute();
$result_trainee = $stmt_trainee->get_result();

// Check if trainee data exists
if ($result_trainee->num_rows > 0) {
    $row_trainee = $result_trainee->fetch_assoc();
} else {
    // Handle the case where trainee data is not found
    echo "Trainee data not found for tid: " . $tid;
    exit();
}

$stmt_trainee->close();




if (isset($_SESSION["user"]) && $_SESSION["user"] != "" && $_SESSION['usertype'] == 'a') {

    // $image_data = isset($_POST["image_data"]) ? $_POST["image_data"] : $row_trainee['image_data'];
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST["name"];
        $deptid = $_POST["deptid"];
        $superid = $_POST["superid"];
        $temail = $_POST["temail"];
        $tpassword = $_POST["tpassword"];
        $phone_number = $_POST["phone_number"];
        $startdate = $_POST["startdate"];
        $endate = $_POST["endate"];
        $courseofstudy = $_POST["courseofstudy"];
        $gender = $_POST["gender"];
        $status = $_POST["status"];
        $uni = $_POST["uni"];

        $image_data = isset($_POST["image_data"]) ? $_POST["image_data"] : $row_trainee['image_data'];
        $scase = isset($_POST["scase"]) ? $_POST["scase"] : (isset($row_trainee['scase']) ? $row_trainee['scase'] : null);
        // $image_data = $_POST["image_data"];
        // $created_at = $_POST["created_at"];
        // $scase = $_POST["scase"];
        // Check if 'image_data' is set in $_FILES and if it's not empty
        if (isset($_FILES['image_data'])) {
            // Debugging: Print the content of $_FILES
            echo '<pre>';
            print_r($_FILES);
            echo '</pre>';

            // Check if 'image_data' is not empty
            if (!empty($_FILES['image_data']['name'])) {
                // Check if the file upload is successful
                if ($_FILES['image_data']['error'] === UPLOAD_ERR_OK) {
                    $image_data = file_get_contents($_FILES['image_data']['tmp_name']);
                } else {
                    // Handle file upload error if needed
                    $image_data = null;
                    echo 'File upload error: ' . $_FILES['image_data']['error'];
                }
            } else {
                // If 'image_data' is empty, use the existing image data
                $image_data = $row_trainee['image_data'];
            }
        } else {
            // If 'image_data' is not set, use the existing image data
            $image_data = $row_trainee['image_data'];
        }



        // Update trainee information in the database
        $stmt = $database->prepare("UPDATE trainee 
            SET name = ?, 
                deptid = ?, 
                superid = ?, 
                temail = ?, 
                tpassword = ?, 
                phone_num = ?, 
                startdate = ?, 
                endate = ?,
                courseofstudy = ?, 
                gender = ?, 
                status = ?, 
                uni = ?, 
                image_data = ?, 
                created_at = ?, 
                scase = ? 
            WHERE tid = ?");

        $stmt->bind_param(
            "siississsssssssi", // Adjust types and count as per your columns
            $name,
            $deptid,
            $superid,
            $temail,
            $tpassword,
            $phone_number, // Fixed variable name
            $startdate,
            $endate,
            $courseofstudy,
            $gender,
            $status,
            $uni,
            $image_data,
            $created_at,
            $scase,
            $tid
        );

        // Execute the query
        if ($stmt->execute()) {
            $updateSuccess = true;

            // If email is updated, update the corresponding webuser record
            if ($temail != $row_trainee['temail']) {
                $updateWebUserStmt = $database->prepare("UPDATE webuser SET email = ? WHERE email = ?");
                $updateWebUserStmt->bind_param("ss", $temail, $row_trainee['temail']);
                $updateWebUserStmt->execute();
                $updateWebUserStmt->close();
            }
        }

        // Close the statement after execution
        $stmt->close();
    }
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
    </head>
</head>

<body>



    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Add Supervisor</h4>

                <form id="updateForm" action="cuba4.php?tid=<?= $tid ?>" method="post" enctype="multipart/form-data">



                    <div class="form-group has-icon-left">
                        <label for="name">Name:</label>
                        <input type="text" class="form-control" name="name" id="name" value="<?= $row_trainee['name']; ?>"><br>

                        <div class="form-control-icon" style="position: absolute;">
                        </div>
                        <!-- Department Selection -->
                        <div class="form-group has-icon-left">
                            <label for="deptid">Department:</label>
                            <div class="position-relative">
                                <select name="deptid" class="form-control" id="deptid">
                                    <!-- Fetch departments from the database -->
                                    <?php
                                    $sql = "SELECT deptid, namedept FROM department";
                                    $result = $database->query($sql);

                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            $selected = ($row_trainee['deptid'] == $row['deptid']) ? 'selected' : '';
                                            echo "<option value='" . $row['deptid'] . "' $selected>" . $row['namedept'] . "</option>";
                                        }
                                    }
                                    ?>
                                    <div class="form-control-icon" style="position: absolute;">
                                </select><br>
                            </div>

                            <!-- Supervisor Selection -->
                            <div class="form-group has-icon-left">
                                <label for="superid">Supervisor</label>
                                <div class="position-relative">
                                    <select name="superid" class="form-control" id="superid">
                                        <?php
                                        $sqlSupervisor = "SELECT superid, name FROM supervisor WHERE deptid = ?";
                                        $stmtSupervisor = $database->prepare($sqlSupervisor);
                                        $stmtSupervisor->bind_param("i", $row_trainee['deptid']);
                                        $stmtSupervisor->execute();
                                        $resultSupervisor = $stmtSupervisor->get_result();

                                        if ($resultSupervisor->num_rows > 0) {
                                            while ($rowSupervisor = $resultSupervisor->fetch_assoc()) {
                                                $selectedSuper = ($row_trainee['superid'] == $rowSupervisor['superid']) ? 'selected' : '';
                                                echo "<option value='" . $rowSupervisor['superid'] . "' $selectedSuper>" . $rowSupervisor['name'] . "</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                    <div class="form-control-icon" style="position: absolute;">
                                        <i class=""></i>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group has-icon-left">
                                <label for="name">Email:</label>
                                <input type="text" class=form-control name="temail" id="temail" value="<?= $row_trainee['temail']; ?>"><br>
                                <div class="form-control-icon" style="position: absolute;">
                                </div>


                                <label for="tpassword">Password:</label>
                                <input type="text" name="tpassword" id="tpassword" value="<?= $row_trainee['tpassword']; ?>"><br>

                                <!-- Phone Number -->
                                <label for="phone_number">Phone Number:</label>
                                <input type="text" name="phone_number" id="phone_numb" value="<?= $row_trainee['phone_num']; ?>"><br>

                                <!-- Start Date -->
                                <!-- Status Selection -->


                                <label for="endate">Start Date:</label>
                                <input type="date" name="startdate" id="startdate" value="<?= $row_trainee['startdate']; ?>"><br>

                                <label for="endate">End Date:</label>
                                <input type="date" name="endate" id="endate" value="<?= $row_trainee['endate']; ?>"><br>
                                <!-- Course of Study -->
                                <label for="courseofstudy">Course of Study:</label>
                                <input type="text" name="courseofstudy" id="courseofstudy" value="<?= $row_trainee['courseofstudy']; ?>"><br>

                                <!-- Gender -->
                                <label for="gender">Gender:</label>
                                <select name="gender" id="gender">
                                    <option value="Male" <?= ($row_trainee['gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                                    <option value="Female" <?= ($row_trainee['gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                                    <option value="Other" <?= ($row_trainee['gender'] == 'Other') ? 'selected' : ''; ?>>Other</option>
                                </select><br>


                                <!-- University -->
                                <label for="uni">University:</label>
                                <input type="text" name="uni" id="uni" value="<?= $row_trainee['uni']; ?>"><br>




                                <!-- Allow the user to update the image if needed -->
                                <label for="image">Update Image:</label>
                                <input type="file" name="image_data" id="image_data" accept="image/*"><br>

                                <!-- Hidden field to store the existing image data -->



                                <!-- Created At -->
                                <label for="created_at">Created At:</label>
                                <input type="datetime-local" name="created_at" id="created_at" value="<?= $row_trainee['created_at']; ?>" readonly><br>


                                <label for="status">Status:</label>
                                <select name="status" id="status" onchange="toggleScase()">
                                    <option value="1" <?= ($row_trainee['status'] == 1) ? 'selected' : ''; ?>>Active</option>
                                    <option value="2" <?= ($row_trainee['status'] == 2) ? 'selected' : ''; ?>>Inactive</option>
                                    <option value="3" <?= ($row_trainee['status'] == 3) ? 'selected' : ''; ?>>Special Case</option>
                                </select><br>
                                <!-- Special Case -->
                                <label for="scase">Special Case:</label>
                                <select name="scase" id="scase" disabled>
                                    <option value="Not Applicable" <?= ($row_trainee['scase'] == 'Not Applicable') ? 'selected' : '' ?>>Not Applicable</option>
                                    <option value="Health Issues" <?= ($row_trainee['scase'] == 'Health Issues') ? 'selected' : '' ?>>Health Issues</option>
                                    <option value="Personal Reasons" <?= ($row_trainee['scase'] == 'Personal Reasons') ? 'selected' : '' ?>>Personal Reasons</option>
                                    <option value="Family Emergency" <?= ($row_trainee['scase'] == 'Family Emergency') ? 'selected' : '' ?>>Family Emergency</option>
                                    <option value="Death in the Family" <?= ($row_trainee['scase'] == 'Death in the Family') ? 'selected' : '' ?>>Death in the Family</option>
                                    <option value="Death of Trainee" <?= ($row_trainee['scase'] == 'Death of Trainee') ? 'selected' : '' ?>>Death of Trainee</option>
                                    <option value="Job Opportunity" <?= ($row_trainee['scase'] == 'Job Opportunity') ? 'selected' : '' ?>>Job Opportunity</option>
                                    <option value="Financial Constraints" <?= ($row_trainee['scase'] == 'Financial Constraints') ? 'selected' : '' ?>>Financial Constraints</option>
                                    <option value="Change of Career Path" <?= ($row_trainee['scase'] == 'Change of Career Path') ? 'selected' : '' ?>>Change of Career Path</option>
                                    <option value="Graduation" <?= ($row_trainee['scase'] == 'Graduation') ? 'selected' : '' ?>>Graduation</option>
                                    <option value="Military Service" <?= ($row_trainee['scase'] == 'Military Service') ? 'selected' : '' ?>>Military Service</option>
                                    <option value="Relocation" <?= ($row_trainee['scase'] == 'Relocation') ? 'selected' : '' ?>>Relocation</option>
                                    <option value="Personal Development" <?= ($row_trainee['scase'] == 'Personal Development') ? 'selected' : '' ?>>Personal Development</option>
                                    <!-- Add more options as needed -->
                                    <option value="Other" <?= ($row_trainee['scase'] == 'Other') ? 'selected' : '' ?>>Other</option>
                                </select><br>
                                <!-- <button onclick="closeScaseModal(event)">Close</button> -->




                                <input type="submit" value="Update Information">
                </form>
            </div>
        </div>
    </div>


    <script>
        // JavaScript to load supervisors based on department selection
        document.getElementById("deptid").addEventListener("change", function() {
            var deptid = this.value;
            var supervisorSelect = document.getElementById("superid");

            // Clear the current supervisor options
            supervisorSelect.innerHTML = "";

            // Fetch supervisors based on the selected department using AJAX
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "get_supervisor.php?deptid=" + deptid, true);

            xhr.onload = function() {
                if (xhr.status === 200) {
                    var supervisors = JSON.parse(xhr.responseText);
                    supervisors.forEach(function(supervisor) {
                        var option = document.createElement("option");
                        option.value = supervisor.superid;
                        option.textContent = supervisor.name;
                        supervisorSelect.appendChild(option);
                    });
                }
            };

            xhr.send();
        });

        // Trigger the change event to load supervisors for the initial department
        document.getElementById("deptid").dispatchEvent(new Event('change'));
    </script>
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

</body>

</html>

<!-- https://www.google.com/url?sa=t&rct=j&q=&esrc=s&source=web&cd=&cad=rja&uact=8&ved=2ahUKEwiX3uqL2OOCAxVlxzgGHUj0CzYQwqsBegQICRAG&url=https%3A%2F%2Fwww.youtube.com%2Fwatch%3Fv%3D9RfU6KGNkfE&usg=AOvVaw1qZ2G0AoqeiIlxw7sEBLUD&opi=89978449 
https://www.google.com/url?sa=t&rct=j&q=&esrc=s&source=web&cd=&ved=2ahUKEwiX3uqL2OOCAxVlxzgGHUj0CzYQwqsBegQIDBAG&url=https%3A%2F%2Fwww.youtube.com%2Fwatch%3Fv%3Db50hB7cfsfg&usg=AOvVaw1XNjMsjBVTb_pmKlPEgRff&opi=89978449-->