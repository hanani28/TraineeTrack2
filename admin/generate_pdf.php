<?php
include_once("../connection.php");
include_once('../fpdf/fpdf.php');

// Define a class that extends FPDF
class PDF extends FPDF {
    // Page header
    function Header() {
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(190, 10, 'Trainee List', 0, 1, 'C');
        $this->Ln(10); // Add some vertical spacing
    }

    // Page footer
    function Footer() {
        // Position at 1.5 cm from the bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
    }

    // Create the trainee list
    function CreateTraineeList($trainees) {
        // Add table header
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(70, 10, 'Name', 1);
        $this->Cell(60, 10, 'Email', 1);
        $this->Cell(47, 10, 'Department', 1);
        $this->Cell(15, 10, 'Status', 1);
        $this->Ln(); // Move to the next line

        // Add data rows
        $this->SetFont('Arial', '', 7);
        foreach ($trainees as $trainee) {
            $this->Cell(70, 10, $trainee['trainee_name'], 1);
            $this->Cell(60, 10, $trainee['temail'], 1);
            $this->Cell(47, 10, $trainee['namedept'], 1);
            $this->Cell(15, 10, $trainee['status'], 1);
            $this->Ln(); // Move to the next line
        }
    }
}

   // Define the database query based on filter criteria
  // Define the database query based on filter criteria
  $query = "SELECT t.tid, t.name AS trainee_name, t.temail, t.deptid, d.namedept, 
  t.superid, s.name AS supervisor_name, t.username, t.tpassword,
  t.phone_num, t.startdate, t.endate, t.courseofstudy, t.gender, t.status, t.uni
  FROM trainee AS t
  JOIN department AS d ON t.deptid = d.deptid
  JOIN supervisor AS s ON t.superid = s.superid";
  
  // Modify the query to include filter conditions based on query parameters
  if (isset($_GET['search']) && !empty($_GET['search'])) {
      $searchTerm = mysqli_real_escape_string($database, $_GET['search']);
      $query .= " WHERE t.name LIKE '%$searchTerm%'";
  }
  
  if (isset($_GET['status_filter']) && !empty($_GET['status_filter'])) {
      $statusFilter = mysqli_real_escape_string($database, $_GET['status_filter']);
      $query .= " AND t.status = '$statusFilter'";
  }
  
  $result = mysqli_query($database, $query);
  
  if (!$result) {
      die("Database query failed: " . mysqli_error($database));
  }
  
  $trainees = array();
  
  while ($row = mysqli_fetch_assoc($result)) {
      $trainees[] = $row;
  }
  
  // Generate the trainee list in the PDF
  $pdf = new PDF();
  $pdf->AddPage();
  $pdf->CreateTraineeList($trainees);
  
  // Output the PDF (force download)
  $pdf->Output('Trainee List.pdf', 'D');

