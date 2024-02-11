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
      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-12 grid-margin">
              <div class="row">
                <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                  <h3 class="font-weight-bold">Welcome <?php echo $username; ?></h3>
                  <h6 class="font-weight-normal mb-0">All systems are running smoothly! You have <span class="text-primary">3 unread alerts!</span></h6>
                </div>

              </div>
            </div>
          </div>


          <div class="row">
            <?php
            // Set the timezone to Malaysia
            date_default_timezone_set('Asia/Kuala_Lumpur');

            // Get the current time in Malaysia
            $current_time = date('H:i'); // Format it as HH:mm

            // Replace 'YOUR_API_KEY' with your actual OpenWeatherMap API key
            $api_key = 'd56341dd7d83f104ae364931491f57b3';

            // Function to fetch weather data from the API
            function getWeatherData($url)
            {
              $response = file_get_contents($url);
              return json_decode($response, true);
            }

            // Get weather data for your location (replace 'Your_Location' with your actual location)
            $location = 'Bintulu,Sarawak'; // e.g., 'Bintulu,MY'
            $weather_url = "http://api.openweathermap.org/data/2.5/weather?q=$location&units=metric&appid=$api_key";
            $weather_data = getWeatherData($weather_url);

            // Extract the current temperature
            $temperature = $weather_data['main']['temp'];
            $temperature = round($temperature); // Round to the nearest whole number



            ?>

            <div class="col-md-6 grid-margin stretch-card">
              <div class="card tale-bg">
                <div class="card-people mt-auto">
                  <img src="../images/dashboard/people.svg" alt="people">
                  <div class="weather-info">
                    <div class="d-flex">
                      <div>
                        <h2 class="mb-0 font-weight-normal"><i class="icon-sun mr-2"></i><?php echo $current_time; ?></h2>
                      </div>
                      <div class="ml-2">
                        <h4 class="location font-weight-normal"><?php echo $location; ?></h4>
                        <h6 class="font-weight-normal">Bintulu</h6>
                        <p class="font-weight-normal"><?php echo $temperature; ?><sup>°C</sup></p> <!-- Display the current temperature -->
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <?php
            // Include your database connection code here
            include("../connection.php");

            // Query to count the number of trainees
            $queryTrainees = "SELECT COUNT(*) AS traineeCount FROM trainee";
            $resultTrainees = mysqli_query($database, $queryTrainees);
            $rowTrainees = mysqli_fetch_assoc($resultTrainees);
            $traineeCount = $rowTrainees['traineeCount'];

            // Query to count the number of supervisors
            $querySupervisors = "SELECT COUNT(*) AS supervisorCount FROM supervisor";
            $resultSupervisors = mysqli_query($database, $querySupervisors);
            $rowSupervisors = mysqli_fetch_assoc($resultSupervisors);
            $supervisorCount = $rowSupervisors['supervisorCount'];

            // Default query to retrieve all data when no search filter is provided
            $query = "SELECT t.tid, t.status
                            FROM trainee AS t";

            $query_run = mysqli_query($database, $query);

            if ($query_run) {
              $activeCount = 0;
              $notActiveCount = 0;

              while ($items = mysqli_fetch_assoc($query_run)) {
                $status = $items['status'];

                if ($status == 1) {
                  // Active status
                  $activeCount++;
                } elseif ($status == 2) {
                  // Not Active status
                  $notActiveCount++;
                }
              }
            }
            ?>



            <div class="col-md-6 grid-margin transparent">
              <div class="row">
                <div class="col-md-6 mb-4 stretch-card transparent">
                  <div class="card card-tale">
                    <div class="card-body">
                      <p class="mb-4">Trainee </p>
                      <p class="fs-30 mb-2"><?php echo $traineeCount; ?></p>
                      <!-- <p>10.00% (30 days)</p> -->
                    </div>
                  </div>
                </div>
                <div class="col-md-6 mb-4 stretch-card transparent">
                  <div class="card card-dark-blue">
                    <div class="card-body">
                      <p class="mb-4">Supervisor</p>
                      <p class="fs-30 mb-2"><?php echo $supervisorCount; ?></p>

                    </div>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6 mb-4 mb-lg-0 stretch-card transparent">
                  <div class="card card-light-blue">
                    <div class="card-body">
                      <p class="mb-4">Active Trainees</p>
                      <p class="fs-30 mb-2"><?php echo $activeCount; ?></p>
                    </div>
                  </div>
                </div>
                <div class="col-md-6 stretch-card transparent">
                  <div class="card card-light-danger">
                    <div class="card-body">
                      <p class="mb-4">Not Active Trainees</p>
                      <p class="fs-30 mb-2"><?php echo $notActiveCount; ?></p>
                    </div>
                  </div>
                </div>
              </div>

            </div>


            <div class="col-lg-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Bar chart</h4>
                  <div id="chart_div">
                    <?php
                    // Connect to your MySQL database
                    include("../connection.php");

                    // Check the connection
                    if ($database->connect_error) {
                      die("Connection failed: " . $database->connect_error);
                    }

                    // Execute a SQL query to fetch data
                    $query = "
                            SELECT DATE_FORMAT(created_at, '%Y-%m') AS month, COUNT(*) AS trainee_count
                            FROM trainee
                            GROUP BY month
                            ORDER BY month
                        ";

                    $result = $database->query($query);

                    // Create an associative array to store the data, initialized with all months
                    $data = array();
                    $currentMonth = date('Y-m');
                    for ($i = 0; $i < 12; $i++) {
                      $data[$currentMonth] = 0;
                      $currentMonth = date('Y-m', strtotime("$currentMonth +1 month"));
                    }

                    while ($row = $result->fetch_assoc()) {
                      $data[$row['month']] = (int)$row['trainee_count'];
                    }

                    // Close the database connection
                    $database->close();
                    ?>



                    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
                    <script type="text/javascript">
                      google.charts.load('current', {
                        'packages': ['bar']
                      });
                      google.charts.setOnLoadCallback(drawChart);

                      function drawChart() {
                        var data = new google.visualization.DataTable();
                        data.addColumn('string', 'Month');
                        data.addColumn('number', 'Trainee Count');

                        <?php
                        foreach ($data as $month => $count) {
                          echo "data.addRow(['$month', $count]);";
                        }
                        ?>

                        var options = {
                          chart: {
                            title: 'Number of Trainees per Month',
                            subtitle: 'Based on the data from your entered data',
                          },
                          bars: 'vertical',
                          width: 900,
                          height: 300
                        };

                        var chart = new google.charts.Bar(document.getElementById('chart_div'));

                        chart.draw(data, google.charts.Bar.convertOptions(options));
                      }
                    </script>
                  </div>
                </div>
              </div>
            </div>


          </div>


        </div>
        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->
        <footer class="footer">
          <div class="d-sm-flex justify-content-center justify-content-sm-between">
            <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2023. Hanani </a> All rights reserved.</span>

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
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

  <!-- Chart.js library -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <!-- End custom js for this page-->
</body>

</html>