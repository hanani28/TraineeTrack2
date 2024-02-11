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
    <!-- <link rel="stylesheet" href="../../css/vertical-layout-light/style.css"> -->
    <!-- endinject -->
    <link rel="shortcut icon" href="images/favicon.png" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<?php
// Start the session
session_start();

// Check if the user is logged in and has the correct user type
if (isset($_SESSION["user"])) {
    if ($_SESSION["user"] == "" || $_SESSION['usertype'] != 'a') {
        header("location: ../login.php");
    }
} else {
    header("location: ../login.php");
}

// Import database
include("../connection.php");

// Retrieve the username from the session
$username = $_SESSION["user"];
?>
<style>
    /* Style for the modal */
    .modal {
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


    /* Style for the modal content */
    .modal-content {
        background: #fff;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
    }



    /* Style for the popup container */
    .popup {
        background: #fff;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
    }

    /* Style for the close button */
    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }


    .inactive-row {
        color: #555;
        /* Set text color for inactive rows */
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
                    <li class="nav-item nav-search d-none d-lg-block">
                        <div class="input-group">
                            <div class="input-group-prepend hover-cursor" id="navbar-search-icon">
                                <span class="input-group-text" id="search">
                                    <!-- <i class="icon-search"></i> -->
                                </span>
                            </div>
                            <!-- <input type="text" class="form-control" id="navbar-search-input" placeholder="Search now" aria-label="search" aria-describedby="search"> -->
                        </div>
                    </li>
                </ul>
                <!-- Inside the navbar, after the search input -->
                <ul class="navbar-nav ml-auto">
                    <!-- Add a nav-item for the profile dropdown -->
                    
                </ul>


                </ul>

            </div>
        </nav>
        <!-- partial -->
        <div class="container-fluid page-body-wrapper">

            <div id="right-sidebar" class="settings-panel">
                <i class="settings-close ti-close"></i>
                <ul class="nav nav-tabs border-top" id="setting-panel" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="todo-tab" data-toggle="tab" href="#todo-section" role="tab" aria-controls="todo-section" aria-expanded="true">TO DO LIST</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="chats-tab" data-toggle="tab" href="#chats-section" role="tab" aria-controls="chats-section">CHATS</a>
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
                                        <label class="form-check-label">
                                            <input class="checkbox" type="checkbox">
                                            Team review meeting at 3.00 PM
                                        </label>
                                    </div>
                                    <i class="remove ti-close"></i>
                                </li>
                                <li>
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input class="checkbox" type="checkbox">
                                            Prepare for presentation
                                        </label>
                                    </div>
                                    <i class="remove ti-close"></i>
                                </li>
                                <li>
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input class="checkbox" type="checkbox">
                                            Resolve all the low priority tickets due today
                                        </label>
                                    </div>
                                    <i class="remove ti-close"></i>
                                </li>
                                <li class="completed">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input class="checkbox" type="checkbox" checked>
                                            Schedule meeting for next week
                                        </label>
                                    </div>
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
                        <div class="events pt-4 px-3">
                            <div class="wrapper d-flex mb-2">
                                <i class="ti-control-record text-primary mr-2"></i>
                                <span>Feb 11 2018</span>
                            </div>
                            <p class="mb-0 font-weight-thin text-gray">Creating component page build a js</p>
                            <p class="text-gray mb-0">The total number of sessions</p>
                        </div>
                        <div class="events pt-4 px-3">
                            <div class="wrapper d-flex mb-2">
                                <i class="ti-control-record text-primary mr-2"></i>
                                <span>Feb 7 2018</span>
                            </div>
                            <p class="mb-0 font-weight-thin text-gray">Meeting with Alisa</p>
                            <p class="text-gray mb-0 ">Call Sarah Graves</p>
                        </div>
                    </div>

                </div>
            </div>
            <!-- partial -->
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

                        <h2>Select Subsidiary and View Departments</h2>

                        <form action="" method="post">
                            <label for="subid">Select Subsidiary:</label>
                            <select name="subid" required>
                                <?php
                                // Include your database connection code here
                                include('../connection.php');

                                // Include an initial option with subid set to 1 and selected attribute
                                echo '<option value="1" selected>Select Subsidiary</option>';

                                // Fetch subsidiary data from the database  
                                $subsidiaryQuery = "SELECT subid, subname FROM subsidiary ORDER BY subid ASC";
                                $subsidiaryResult = mysqli_query($database, $subsidiaryQuery);

                                // Populate the dropdown list with subsidiary options
                                while ($row = mysqli_fetch_assoc($subsidiaryResult)) {
                                    $isSelected = ($row['subid'] == 1) ? 'selected' : ''; // Check if subid is 1
                                ?>
                                    <option value='<?php echo $row['subid']; ?>' <?php echo $isSelected; ?>><?php echo $row['subname']; ?></option>
                                <?php
                                }

                                // Close the database connection
                                ?>

                            </select><br>

                            <input type="submit" value="View Departments">
                        </form>

                        <?php
                        // Check if the form is submitted
                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            // Retrieve selected subsidiary ID and name
                            $selectedSubid = $_POST['subid'];

                            // Include your database connection code here
                            include('../connection.php');

                            // Fetch department data based on the selected subsidiary
                            $departmentQuery = "SELECT * FROM department WHERE subid = '$selectedSubid' ORDER BY status ASC";
                            $departmentResult = mysqli_query($database, $departmentQuery);

                            // Get the selected subsidiary name
                            $subsidiaryNameQuery = "SELECT subname FROM subsidiary WHERE subid = '$selectedSubid'";
                            $subsidiaryNameResult = mysqli_query($database, $subsidiaryNameQuery);
                            $row = mysqli_fetch_assoc($subsidiaryNameResult);
                            $selectedSubName = $row['subname'];
                        ?>

                            <div class="row">
                                <div class="col-lg-12 stretch-card">
                                    <div class="card">
                                        <div class="card-body">

                                            <h3>Departments for Subsidiary: <?php echo $selectedSubName; ?></h3>
                                            <div class="table-responsive pt-3">
                                                <table class="table table-bordered custom-table">
                                                    <thead>
                                                        <!-- <th>Department ID</th> -->
                                                        <th>Department ID</th>
                                                        <th>Department Name</th>
                                                        <th>Action</th>
                                                        <!-- <th>Subsidiary ID</th> -->
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        while ($row = mysqli_fetch_assoc($departmentResult)) {
                                                            // Check if the status is 2
                                                            $rowClass = ($row['status'] == 2) ? 'inactive-row' : '';

                                                            // Set background color based on status
                                                            $rowStyle = ($row['status'] == 2) ? 'background-color: #f2f2f2;' : '';
                                                        ?>
                                                            <tr class="<?php echo $rowClass; ?>" style="<?php echo $rowStyle; ?>">
                                                                <td><?php echo $row['deptnumber']; ?></td>
                                                                <td><?php echo $row['namedept']; ?></td>
                                                                <td>
                                                                    <!-- Add an Edit button with a data attribute to store the department ID -->
                                                                    <!-- Add a class to the link to identify it -->
                                                                    <a class="edit-btn" data-deptid="<?php echo $row['deptid']; ?>" href="editdepartment.php?deptid=<?php echo $row['deptid']; ?>">Edit</a>
                                                                </td>
                                                            </tr>
                                                        <?php
                                                        }
                                                        ?>
                                                    </tbody>

                                                </table>
                                            <?php
                                            // Close the database connection
                                            mysqli_close($database);
                                        }
                                            ?>


                                            <a href="#" id="openModalBtn"><button>Go to Manage Form</button></a>


                                            <!-- The Modal -->
                                            <div id="myModal" class="modal">

                                                <!-- Modal content -->
                                                <div class="overlay" id="popupOverlay">
                                                    <!-- Popup container -->
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title">Add Department</h4>
                                                                <span class="close" onclick="closeModal()">&times;</span>
                                                            </div>

                                                            <form action="manageform.php" method="post">
                                                                <label for="subid">Subsidiary:</label>
                                                                <select name="subid" required>
                                                                    <?php
                                                                    // Include your database connection code here
                                                                    include('../connection.php');

                                                                    // Fetch subsidiary data from the database
                                                                    $subsidiaryQuery = "SELECT subid, subname FROM subsidiary";
                                                                    $subsidiaryResult = mysqli_query($database, $subsidiaryQuery);

                                                                    // Populate the dropdown list with subsidiary options
                                                                    while ($row = mysqli_fetch_assoc($subsidiaryResult)) {
                                                                        echo "<option value='" . $row['subid'] . "'>" . $row['subname'] . "</option>";
                                                                    }

                                                                    // Close the database connection
                                                                    mysqli_close($database);
                                                                    ?>
                                                                </select><br>

                                                                <label for="deptnumber">Department ID:</label>
                                                                <input type="text" name="deptnumber" required><br>

                                                                <label for="namedept">Department Name:</label>
                                                                <input type="text" name="namedept" required><br>



                                                                <input type="submit" value="Add Department">
                                                            </form>

                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            </div>
                                            <!-- <script>
                                                // Function to open the modal
                                                function openModal() {
                                                    document.getElementById('myModal').style.display = 'block';
                                                }

                                                // Function to close the modal
                                                function closeModal() {
                                                    document.getElementById('myModal').style.display = 'none';
                                                }

                                                // Attach the click event to the "Go to Manage Form" button
                                                document.getElementById('openModalBtn').addEventListener('click', function(e) {
                                                    e.preventDefault();
                                                    openModal();
                                                });
                                            </script> -->

                                            <!-- Modal container -->
                                            <div id="editModal" class="modal">
                                                <div class="modal-content">
                                                    <span class="close" onclick="closeEditModal()">&times;</span>
                                                    <!-- Form content -->
                                                    <form id="editForm" action="update_department.php" method="post">
                                                        <!-- Form fields go here -->
                                                        <label for="deptnumber">Department ID:</label>
                                                        <input type="text" name="deptnumber" id="editDeptID" required>

                                                        <label for="namedept">Department Name:</label>
                                                        <input type="text" name="namedept" id="editNamedept" required>

                                                        <label for="subid">Subsidiary ID:</label>
                                                        <input type="text" name="subid" id="editSubid" required>

                                                        <input type="submit" value="Update Department">
                                                    </form>
                                                </div>
                                            </div>

                                            <!-- <script>
                                                function openModal() {
                                                    const deptid = button.getAttribute('data-deptid');
                                                    const deptnumber = '<?php echo $row['deptnumber']; ?>';
                                                    const namedept = '<?php echo $row['namedept']; ?>';
                                                    const subid = '<?php echo $row['subid']; ?>';

                                                    document.getElementById('editDeptID').value = deptnumber;
                                                    document.getElementById('editNamedept').value = namedept;
                                                    document.getElementById('editSubid').value = subid;
                                                    document.getElementById('editModal').style.display = 'block';
                                                }

                                                function closeModal() {
                                                    document.getElementById('editModal').style.display = 'none';
                                                }

                                                document.querySelectorAll('.edit-btn').forEach(function(button) {
                                                    button.addEventListener('click', function(event) {
                                                        event.preventDefault();
                                                        openModal(this,edit);
                                                    });
                                                });
                                            </script> -->




                                            <script>
                                                // Function to open the first modal
                                                function openModal() {
                                                    document.getElementById('myModal').style.display = 'block';
                                                }

                                                // Function to close the first modal
                                                function closeModal() {
                                                    document.getElementById('myModal').style.display = 'none';
                                                }

                                                // Attach the click event to the "Go to Manage Form" button
                                                document.getElementById('openModalBtn').addEventListener('click', function(e) {
                                                    e.preventDefault();
                                                    openModal();
                                                });
                                            </script>
                                            <!-- Script for the second modal (editModal) -->
                                            <!-- Add this JavaScript at the end of your HTML file -->
                                            <script>
                                                function openEditModal(link) {
                                                    // Get the department ID from the data attribute
                                                    var deptid = link.getAttribute('data-deptid');

                                                    // Assuming you have a modal with an id "editModal"
                                                    var modal = document.getElementById('editModal');

                                                    // Populate the modal with data using AJAX
                                                    var xhttp = new XMLHttpRequest();
                                                    xhttp.onreadystatechange = function() {
                                                        if (this.readyState == 4 && this.status == 200) {
                                                            // Insert the fetched HTML into the modal
                                                            modal.innerHTML = this.responseText;

                                                            // Display the modal
                                                            modal.style.display = 'block';
                                                        }
                                                    };
                                                    xhttp.open('GET', 'update_department.php?deptid=' + deptid, true);
                                                    xhttp.send();
                                                }

                                                function closeEditModal() {
                                                    // Assuming you have a modal with an id "editModal"
                                                    var modal = document.getElementById('editModal');
                                                    modal.style.display = 'none';
                                                }

                                                function updateDepartment() {
                                                    // Get form data
                                                    var deptid = document.getElementById('editDeptID').value;
                                                    var newDeptNumber = document.getElementById('editNewDeptNumber').value;
                                                    var newNameDept = document.getElementById('editNewNameDept').value;

                                                    // Send data through AJAX
                                                    var xhttp = new XMLHttpRequest();
                                                    xhttp.onreadystatechange = function() {
                                                        if (this.readyState == 4 && this.status == 200) {
                                                            // Handle the response
                                                            var response = JSON.parse(this.responseText);
                                                            if (response.success) {
                                                                // Update successful, close the modal or do other actions
                                                                closeEditModal();
                                                            } else {
                                                                // Handle the error (display an error message, log, etc.)
                                                                alert('Error updating department: ' + response.error);
                                                            }
                                                        }
                                                    };
                                                    xhttp.open('POST', 'update_department.php', true);
                                                    xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                                                    xhttp.send('deptid=' + deptid + '&newDeptNumber=' + newDeptNumber + '&newNameDept=' + newNameDept);
                                                }
                                            </script>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</body>

</html>