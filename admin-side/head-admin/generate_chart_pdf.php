<?php
require_once '../tcpdf-main/tcpdf.php';
require_once '../php/config_iskolarosa_db.php'; // Include your database connection script

// Define the query to retrieve data from the ceap_reg_form table
$queryCeapRegForm = "SELECT barangay, 
    SUM(CASE WHEN gender = 'Male' THEN 1 ELSE 0 END) as MaleCount, 
    SUM(CASE WHEN gender = 'Female' THEN 1 ELSE 0 END) as FemaleCount
    FROM ceap_reg_form
    GROUP BY barangay";

// Define the query to retrieve data from the ceap_personal_account table
$queryPersonalAccount = "SELECT barangay, 
    SUM(CASE WHEN gender = 'Male' THEN 1 ELSE 0 END) as MaleCount, 
    SUM(CASE WHEN gender = 'Female' THEN 1 ELSE 0 END) as FemaleCount
    FROM ceap_personal_account
    GROUP BY barangay";

// Execute both queries
$resultCeapRegForm = mysqli_query($conn, $queryCeapRegForm);
$resultPersonalAccount = mysqli_query($conn, $queryPersonalAccount);

// Initialize an associative array to store the combined totals for each barangay
$combinedTotals = array();

// Loop through the query results for ceap_reg_form
while ($row = mysqli_fetch_assoc($resultCeapRegForm)) {
    $barangay = $row['barangay'];
    $maleCount = $row['MaleCount'];
    $femaleCount = $row['FemaleCount'];

    // Add or update the combined totals for the barangay
    if (!isset($combinedTotals[$barangay])) {
        $combinedTotals[$barangay] = array('MaleCount' => 0, 'FemaleCount' => 0);
    }

    // Update the combined totals
    $combinedTotals[$barangay]['MaleCount'] += $maleCount;
    $combinedTotals[$barangay]['FemaleCount'] += $femaleCount;
}

// Loop through the query results for ceap_personal_account
while ($row = mysqli_fetch_assoc($resultPersonalAccount)) {
    $barangay = $row['barangay'];
    $maleCount = $row['MaleCount'];
    $femaleCount = $row['FemaleCount'];

    // Add or update the combined totals for the barangay
    if (!isset($combinedTotals[$barangay])) {
        $combinedTotals[$barangay] = array('MaleCount' => 0, 'FemaleCount' => 0);
    }

    // Update the combined totals
    $combinedTotals[$barangay]['MaleCount'] += $maleCount;
    $combinedTotals[$barangay]['FemaleCount'] += $femaleCount;
}

// Initialize variables to store the table content
$tableContent = '<table border="1" style="width: 100%;"><tr><th style="padding: 5px;">Barangay</th><th style="padding: 5px;">Male</th><th style="padding: 5px;">Female</th></tr>';

// Loop through the combined totals
foreach ($combinedTotals as $barangay => $totals) {
    $maleCount = $totals['MaleCount'];
    $femaleCount = $totals['FemaleCount'];

    // Add a row to the table
    $tableContent .= '<tr><td style="padding: 5px;">' . $barangay . '</td><td style="padding: 5px;">' . $maleCount . '</td><td style="padding: 5px;">' . $femaleCount . '</td></tr>';
}

// Close the table
$tableContent .= '</table>';

// Create a new TCPDF instance
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Set document information
$pdf->SetCreator('Scholarship Office in Santa Rosa Laguna');
$pdf->SetAuthor('Scholarship Office in Santa Rosa Laguna');
$pdf->SetTitle('Gender Report');
$pdf->SetSubject('Gender Report');
$pdf->SetKeywords('Gender, Report, PDF');

// Add a page to the PDF
$pdf->AddPage();

// Set font
$pdf->SetFont('helvetica', '', 12);

// Define the header content with image on the left
$header = '<div>';
$header .= '<img src="../system-images/iskolarosa-logo.jpg" alt="Logo" style="width: 50px; height: auto; float: left; margin-right: 10px;" />';
$header .= '<h1 style="text-align: center;">CEAP Gender Report</h1>';
$header .= '</div>';

// Output the header content to the PDF
$pdf->writeHTML($header, true, false, true, false, '');

// Output the table content to the PDF
$pdf->writeHTML($tableContent, true, false, true, false, '');

// Output the PDF to the browser or save it to a file
$pdf->Output('gender_report.pdf', 'I'); // 'I' sends the PDF to the browser; 'F' saves it to a file

?>
