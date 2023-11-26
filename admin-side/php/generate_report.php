<?php
require_once '../TCPDF-main/tcpdf.php';
require_once 'config_iskolarosa_db.php'; // Include your database connection script

// Create a new PDF document
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8');

// Set header content
$headerTitle = 'Your PDF Header Title';

// Set the document information
$pdf->SetCreator('iSKOLAROSA');
$pdf->SetAuthor('Lance');
$pdf->SetTitle('Reports');

// Add a page
$pdf->AddPage();

// Set font
$pdf->SetFont('helvetica', '', 11);

// Styling for the table
$html = '<style>
            table {
                border-collapse: collapse;
                width: 100%;
            }
            th, td {
                border: 1px solid #dddddd;
                text-align: left;
                padding: 10px;
            }
            th {
                background-color: #f2f2f2;
            }
          </style>';

// Fetch the radio selected and option selected from the POST data
$dataChoice = $_POST['dataChoice'];
$sortingOrder = isset($_POST['sortingOrder']) ? $_POST['sortingOrder'] : 'ASC'; // Default to ascending if not selected
$filterOption = $_POST['filterOption'];

// Create a dynamic SQL query based on the selected options
$sqlQueries = [];

if ($dataChoice === 'ceap') {
    $tableName = 'ceap_reg_form';
    $status = 'temporary_account';
} else if ($dataChoice === 'lppp') {
    $tableName = 'lppp_reg_form';
    $status = 'lppp_temporary_account';
}

foreach ($filterOption as $option) {
    switch ($option) {
        case 'totalCountGender':
            $sqlQueries[] = 'SELECT "GENDER" AS `Label`, UPPER(gender) AS `Value`, COUNT(gender) AS `Total` FROM ' . $tableName . ' GROUP BY gender ORDER BY gender ' . ($sortingOrder === 'DESC' ? 'DESC' : 'ASC') . ';';
            break;

            case 'totalCountStatus':
                $sqlQueries[] = 'SELECT "STATUS" AS `Label`, UPPER(status) AS `Value`, COUNT(status) AS `Total` FROM ' . $status . ' GROUP BY status ORDER BY COUNT(status) ' . ($sortingOrder === 'DESC' ? 'DESC' : 'ASC') . ';';
                break;

        case 'totalCountBarangay':
            $sqlQueries[] = 'SELECT "BARANGAY" AS `Label`, UPPER(barangay) AS `Value`, COUNT(barangay) AS `Total` FROM ' . $tableName . ' GROUP BY barangay ORDER BY barangay ' . ($sortingOrder === 'DESC' ? 'DESC' : 'ASC') . ';';
            break;

            case 'totalCountSchool':
                $columnName = ($dataChoice === 'ceap') ? 'school_name' : 'elementary_school';
                $sqlQueries[] = 'SELECT "SCHOOL" AS `Label`, UPPER(' . $columnName . ') AS `Value`, COUNT(' . $columnName . ') AS `Total` FROM ' . $tableName . ' GROUP BY ' . $columnName . ' ORDER BY COUNT(' . $columnName . ') ' . ($sortingOrder === 'DESC' ? 'DESC' : 'ASC') . ';';
                break;
            
    }
}

// Initialize an array to store totals for each label
$totals = [];

// Execute the SQL queries and calculate totals
if (!empty($sqlQueries)) {
    foreach ($sqlQueries as $sqlQuery) {
        $result = mysqli_query($conn, $sqlQuery);

        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $label = $row['Label'];
                $value = $row['Value'];
                $total = $row['Total'];

                // Initialize total for this label if not set
                if (!isset($totals[$label])) {
                    $totals[$label] = [];
                }

                // Store each value separately with its total
                $totals[$label][] = ['Value' => $value, 'Total' => $total];
            }
        }
    }
}

// Generate the HTML table header
$html .= '<table>
            <tr>
                <th>' . $label . '</th>
                <th>TOTAL</th>
            </tr>';


// Generate the HTML table rows with calculated totals
foreach ($totals as $label => $values) {
    // Generate the HTML table rows for each value
    foreach ($values as $data) {
        $html .= '<tr>';
        $html .= '<td>' . $data['Value'] . '</td>';
        $html .= '<td>' . $data['Total'] . '</td>';
        $html .= '</tr>';
    }
}

$html .= '</table>';

// Output the table to PDF
$pdf->writeHTML($html, true, false, true, false, '');

// Set the Content-Type header for PDF
$filename = 'iskolarosa_GENERATE_REPORT.pdf';

// Close and output the PDF
$pdf->Output($filename, 'I');

// Close the database connection
$conn->close();
?>