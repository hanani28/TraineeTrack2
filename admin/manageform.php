<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Department Form</title>
</head>

<body>
    <?php
    // Include your database connection code here
    include('../connection.php');

    // Check if the form is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Retrieve form data
        $namedept = $_POST['namedept'];
        $deptnumber = $_POST['deptnumber'];
        $subid = $_POST['subid'];

        // Insert data into the "department" table
        $sql = "INSERT INTO department (namedept, subid, deptnumber) VALUES ('$namedept', '$subid','$deptnumber')";

        if (mysqli_query($database, $sql)) {
            // Success message
            $successMessage = "Department added successfully!";
        } else {
            // Error message
            $errorMessage = "Error: " . $sql . "<br>" . mysqli_error($database);
        }

        // Close the database connection
        mysqli_close($database);
    }
    ?>

    <!-- Display success message in a popup -->
    <?php if (isset($successMessage)) : ?>
        <script>
            alert("<?php echo $successMessage; ?>");
            // Redirect to manageform.php after 0.5 seconds
            setTimeout(function () {
                window.location.href = "formdisplay.php";
            }, 500);
        </script>
    <?php endif; ?>

    <!-- Display error message in a popup -->
    <?php if (isset($errorMessage)) : ?>
        <script>
            alert("<?php echo $errorMessage; ?>");
        </script>
    <?php endif; ?>

</body>

</html>
