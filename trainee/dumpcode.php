<?php
//learn from w3schools.com

session_start();

if (isset($_SESSION["user"])) {
  if (($_SESSION["user"]) == "" or $_SESSION['usertype'] != 't') {
    header("location: ../login.php");
  } else {
    $useremail = $_SESSION["user"];
  }
} else {
  header("location: ../login.php");
}


//import database
include("../connection.php");
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

$event_query = "SELECT events.*, trainee.name AS name FROM events
INNER JOIN trainee ON events.tid = trainee.tid
WHERE events.id = $event_id";

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
    echo "";
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
        <ul class="navbar-nav mr-lg-2">
          <li class="nav-item nav-search d-none d-lg-block">
            <div class="input-group">
              <div class="input-group-prepend hover-cursor" id="navbar-search-icon">
                <span class="input-group-text" id="search">
                  <i class="icon-search"></i>
                </span>
              </div>
              <input type="text" name="search" required value="<?php if (isset($_GET['search'])) {
                                                                  echo $_GET['search'];
                                                                } ?>" class="form-control" placeholder="Search data">
          </li>
        </ul>
        <ul class="navbar-nav navbar-nav-right">
          <li class="nav-item dropdown">
            <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#" data-toggle="dropdown">
              <i class="icon-bell mx-0"></i>
              <span class="count"></span>
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="notificationDropdown">

              <a class="dropdown-item preview-item">
                <div class="preview-thumbnail">
                  <div class="preview-icon bg-success">
                    <i class="ti-info-alt mx-0"></i>
                  </div>
                </div>
                <div class="preview-item-content">
                  <h6 class="preview-subject font-weight-normal">Application Error</h6>
                  <p class="font-weight-light small-text mb-0 text-muted">
                    Just now
                  </p>
                </div>
              </a>
              <a class="dropdown-item preview-item">

              </a>
              <a class="dropdown-item preview-item">
                <div class="preview-thumbnail">
                  <div class="preview-icon bg-info">
                    <i class="ti-user mx-0"></i>
                  </div>
                </div>
                <div class="preview-item-content">

                </div>
              </a>
            </div>
          </li>
          <li class="nav-item nav-profile dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
              <img src="../../images/faces/face28.jpg" alt="profile" />
            </a>

          </li>
          <li class="nav-item nav-settings d-none d-lg-flex">

          </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
          <span class="icon-menu"></span>
        </button>
      </div>
    </nav>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:../../partials/_settings-panel.html -->
      <div class="theme-setting-wrapper">
        <div id="settings-trigger"><i class="ti-settings"></i></div>
        <div id="theme-settings" class="settings-panel">


        </div>
      </div>
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

            </div>

            <h4 class="px-3 text-muted mt-5 font-weight-light mb-0">Events</h4>


          </div>
          <!-- To do section tab ends -->
          <div class="tab-pane fade" id="chats-section" role="tabpanel" aria-labelledby="chats-section">


          </div>
          <!-- chat tab ends -->
        </div>
      </div>
      <!-- partial -->
      <!-- partial:../../partials/_sidebar.html -->
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
            <a class="nav-link" href="task.php">
              <i class="icon-paper menu-icon"></i>
              <span class="menu-title">Task</span>
            </a>
          </li>


          <li class="nav-item">
            <a class="nav-link" href="cal/index.php">
              <i class="icon-user menu-icon"></i>
              <span class="menu-title">Calender</span>
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
                    <tr>
                      <th>Trainee Name</th>
                      <td><?php echo $event_row['name']; ?></td>
                    </tr>
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

                          </tr>
                          <?php while ($minitask_row = mysqli_fetch_assoc($minitask_result)) : ?>
                            <tr>
                              <td>
                                <div class="custom-checkbox">
                                  <input type="checkbox" id="minitask_<?php echo $minitask_row['minID']; ?>" data-minitaskid="<?php echo $minitask_row['minID']; ?>" class="minitask-checkbox visually-hidden" <?php echo $minitask_row['status'] == 1 ? 'checked' : ''; ?>>
                                  <label for="minitask_<?php echo $minitask_row['minID']; ?>" class="custom-checkbox-label"></label>
                                  <?php echo $minitask_row['subtask']; ?>
                                </div>
                              </td>
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script type="text/javascript">
  $(document).ready(function() {
    $('.minitask-checkbox').change(function() {
      var minitaskId = $(this).data('minitaskid');
      var isChecked = $(this).is(":checked") ? 1 : 0;

      // Update the mini-task status via AJAX
      $.ajax({
        type: "POST",
        url: "update_minitask_status.php", // Create a PHP script to update the status
        data: {
          minitaskId: minitaskId,
          isChecked: isChecked
        },
        success: function() {
          console.log("Mini-Task status updated successfully.");

          // If all checkboxes are checked, update the event status to 1
          if ($('.minitask-checkbox:not(:checked)').length === 0) {
            $.ajax({
              type: "POST",
              url: "update_event_status.php", // Create a PHP script to update the event status
              data: {
                event_id: <?php echo $event_id; ?>,
                status: 1
              },
              success: function() {
                console.log("Event status updated to 1.");
              },
              error: function(err) {
                console.error("Error updating event status: " + err.responseText);
              }
            });
          } else {
            // If not all checkboxes are checked, update the event status to 0
            $.ajax({
              type: "POST",
              url: "update_event_status.php", // Create a PHP script to update the event status
              data: {
                event_id: <?php echo $event_id; ?>,
                status: 0
              },
              success: function() {
                console.log("Event status updated to 0.");
              },
              error: function(err) {
                console.error("Error updating event status: " + err.responseText);
              }
            });
          }
        },
        error: function(err) {
          console.error("Error updating mini-task status: " + err.responseText);
        }
      });
    });
  });
</script>

<!-- container-scroller -->
<!-- plugins:js -->
<script src="../../vendors/js/vendor.bundle.base.js"></script>
<!-- endinject -->
<!-- Plugin js for this page -->
<script src="../../vendors/typeahead.js/typeahead.bundle.min.js"></script>
<script src="../../vendors/select2/select2.min.js"></script>
<!-- End plugin js for this page -->
<!-- inject:js -->
<script src="../../js/off-canvas.js"></script>
<script src="../../js/hoverable-collapse.js"></script>
<script src="../../js/template.js"></script>
<script src="../../js/settings.js"></script>
<script src="../../js/todolist.js"></script>
<!-- endinject -->
<!-- Custom js for this page-->
<script src="../../js/file-upload.js"></script>
<script src="../../js/typeahead.js"></script>
<script src="../../js/select2.js"></script>

</html>











<!-- New Part of code -->
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
        <ul class="navbar-nav mr-lg-2">
          <li class="nav-item nav-search d-none d-lg-block">
            <div class="input-group">
              <div class="input-group-prepend hover-cursor" id="navbar-search-icon">
                <span class="input-group-text" id="search">
                  <i class="icon-search"></i>
                </span>
              </div>
              <input type="text" name="search" required value="<?php if (isset($_GET['search'])) {
                                                                  echo $_GET['search'];
                                                                } ?>" class="form-control" placeholder="Search data">
          </li>
        </ul>
        <ul class="navbar-nav navbar-nav-right">
          <li class="nav-item dropdown">
            <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#" data-toggle="dropdown">
              <i class="icon-bell mx-0"></i>
              <span class="count"></span>
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="notificationDropdown">

              <a class="dropdown-item preview-item">
                <div class="preview-thumbnail">
                  <div class="preview-icon bg-success">
                    <i class="ti-info-alt mx-0"></i>
                  </div>
                </div>
                <div class="preview-item-content">
                  <h6 class="preview-subject font-weight-normal">Application Error</h6>
                  <p class="font-weight-light small-text mb-0 text-muted">
                    Just now
                  </p>
                </div>
              </a>
              <a class="dropdown-item preview-item">

              </a>
              <a class="dropdown-item preview-item">
                <div class="preview-thumbnail">
                  <div class="preview-icon bg-info">
                    <i class="ti-user mx-0"></i>
                  </div>
                </div>
                <div class="preview-item-content">

                </div>
              </a>
            </div>
          </li>
          <li class="nav-item nav-profile dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
              <img src="../../images/faces/face28.jpg" alt="profile" />
            </a>

          </li>
          <li class="nav-item nav-settings d-none d-lg-flex">

          </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
          <span class="icon-menu"></span>
        </button>
      </div>
    </nav>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:../../partials/_settings-panel.html -->
      <div class="theme-setting-wrapper">
        <div id="settings-trigger"><i class="ti-settings"></i></div>
        <div id="theme-settings" class="settings-panel">


        </div>
      </div>
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

            </div>

            <h4 class="px-3 text-muted mt-5 font-weight-light mb-0">Events</h4>


          </div>
          <!-- To do section tab ends -->
          <div class="tab-pane fade" id="chats-section" role="tabpanel" aria-labelledby="chats-section">


          </div>
          <!-- chat tab ends -->
        </div>
      </div>
      <!-- partial -->
      <!-- partial:../../partials/_sidebar.html -->
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
            <a class="nav-link" href="task.php">
              <i class="icon-paper menu-icon"></i>
              <span class="menu-title">Task</span>
            </a>
          </li>


          <li class="nav-item">
            <a class="nav-link" href="cal/index.php">
              <i class="icon-user menu-icon"></i>
              <span class="menu-title">Calender</span>
            </a>
          </li>





        </ul>
      </nav>

      <div class="content-wrapper">
        <div class="row">
          <div class="col-lg-10 stretch-card">
            <div class="card">
              <div class="card-body">

                <h2><?php echo $event_row['title']; ?></h2>
               
                <h3><strong class="card-title text-primary">
                  Description:
                </strong> <?php echo $event_row['description']; ?>
                                                              </h3>
                <h3><strong class=" grid-margin card-title text-primary">Start Date:</strong> <?php echo $event_row['start_date']; ?></h3>
                <h3><strong class="card-title text-primary">End Date:</strong> <?php echo $event_row['end_date']; ?></h3>
                <p><strong class="card-title text-primary">Status :</strong> <?php echo $event_row['status'] == 1 ? 'Completed' : 'Pending'; ?></p>


                <?php
                // Query to retrieve related mini-tasks
                $minitask_query = "SELECT * FROM minitask WHERE id = $event_id";
                $minitask_result = mysqli_query($database, $minitask_query);
                ?>

                <?php if (mysqli_num_rows($minitask_result) > 0) : ?>
                  <h2>Mini Task</h2>
                  <ul class="list-star">
                    <?php while ($minitask_row = mysqli_fetch_assoc($minitask_result)) : ?>
                      <li>
                        <div class="form-group">
                          <div class="form-check form-check-primary">
                            <label class="form-check-label">
                              <input type="checkbox" id="minitask_<?php echo $minitask_row['minID']; ?>" data-minitaskid="<?php echo $minitask_row['minID']; ?>" class="minitask-checkbox form-check-input" <?php echo $minitask_row['status'] == 1 ? 'checked' : ''; ?>>
                             <?php echo $minitask_row['subtask']; ?>
                            </label>
                          </div>
                        </div>
                      </li>





                        
                    <?php endwhile; ?>
                  </ul>

                  <button class="btn btn-outline-danger btn-fw btn-icon-text" id="updateMinitaskStatusButton">UPDATE</button>


                <?php endif; ?>
                <p>No Subtask found for this Main Task.</p>
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