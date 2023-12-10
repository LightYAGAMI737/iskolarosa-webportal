<?php
require_once '../TCPDF-main/tcpdf.php';
require_once 'config_iskolarosa_db.php'; // Include your database connection script

// Set the time zone to Asia/Manila
date_default_timezone_set('Asia/Manila');

// Extend TCPDF to create a custom footer
class MYPDF extends TCPDF {
    private $dataChoice;

    // Constructor to set dataChoice
    public function __construct($dataChoice) {
        parent::__construct();
        $this->dataChoice = $dataChoice;
    }

    public function Header() {
        // Set the header content here
        $image_file = K_PATH_IMAGES . '../system-images/iskolarosa-logo.jpg';

        // Calculate x-coordinate to center the image
        $pageWidth = $this->GetPageWidth();
        $imageWidth = 25; // Adjust the image width as needed
        $xCoordinate = ($pageWidth - $imageWidth) / 2;

        $this->Image($image_file, $xCoordinate, 10, 25, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        $this->Ln(30); // Add a line break to lower the position

        // Set font after the image
        $this->SetFont('helvetica', 'B', 12);
        $this->SetTextColor(165, 4, 10); // RGB values for #A5040A
        $this->Cell(0, 15, 'iSKOLAROSA', 0, false, 'C', 0, '', 0, false, 'M', 'M');
        $this->Ln(5); // Add a line break to lower the position
        $this->SetTextColor(0, 0, 0); // Reset text color to black (optional)
        // Dynamically set the header based on $dataChoice
        if ($this->dataChoice === 'ceap') {
            $headerText = 'COLLEGE EDUCATIONAL ASSISTANCE PROGRAM (CEAP)';
        } else if ($this->dataChoice === 'lppp') {
            $headerText = 'LIBRENG PAGAARAL SA PRIBADONG PAARALAN (LPPP)';
        } else {
            $headerText = 'Default Header';
        }

        $this->SetFont('helvetica', 'B', 12);
        $this->Cell(0, 15, $headerText, 0, false, 'C', 0, '', 0, false, 'M', 'M');
        $this->Ln(30); // Add a line break to lower the position
    }

 // Footer
 public function Footer() {
    
    // Republic of the Philippines
    $this->SetFont('helvetica', '', 8);
    $this->SetY(-25);
    $this->Cell(0, 1, 'Republic of the Philippines', 0, false, 'L');
    $this->Ln();
    
    // City of Santa Rosa, Province of Laguna
    $this->Cell(0, 1, 'City of Santa Rosa, Province of Laguna', 0, false, 'L');
    $this->Ln();

    // Office of the City Mayor
    $this->Cell(0, 1, 'Office of the City Mayor', 0, false, 'L');
    $this->Ln();

    // City Scholarship Office
    $this->Cell(0, 1, 'City Scholarship Office', 0, false, 'L');
    $this->Ln();

    // “SERBISYONG MAKATAO, LUNGSOD NA MAKABAGO”
    $this->Cell(0, 1, '“SERBISYONG MAKATAO, LUNGSOD NA MAKABAGO”', 0, false, 'L');
    $this->Ln();

    $this->SetY(-28);
    
    // Logo on the left side
    $image_file = K_PATH_IMAGES . '../system-images/santarosa-logo.jpg';
    $imageWidth = 20; // Adjust the image width as needed
    $this->Image($image_file, $this->GetPageWidth() - 60, $this->GetY(), $imageWidth, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);

    // Logo on the right side
    $image_filetwo = K_PATH_IMAGES . '../system-images/SMLM-logo.jpg';
    $imageWidthtwo = 20; // Adjust the image width as needed
    $this->Image($image_filetwo, $this->GetPageWidth() - 35, $this->GetY(), $imageWidthtwo, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
}
}

// Create a new PDF document with dataChoice
$pdf = new MYPDF($_POST['dataChoice'], 'P', 'mm', 'A4', true, 'UTF-8', false);
$pdf->SetMargins(24, 24, 24, true); // 2.4cm margins on each side

// Set the document information
$pdf->SetCreator('iSKOLAROSA');
$pdf->SetAuthor('Scholarship Office of Santa Rosa');
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
                margin: 0 auto;
            }
            th, td {
                border: 1px solid #8F8F8F;
                text-align: left;
                padding: 10px;
            }
            th {
                border: 1px solid #8F8F8F;
                background-color: red;
            }
            p{
                text-align: center;
                width: 10px;
            }
            .date-generated {
                text-align: right;
                font-style: italic;
                font-size: 11px;
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
            case 'totalCountBarangay':
                $sqlQueries[] = 'SELECT "BARANGAY" AS `Label`, UPPER(barangay) AS `Value`, COUNT(barangay) AS `Total` FROM ' . $tableName . ' GROUP BY barangay ORDER BY COUNT(barangay) ' . ($sortingOrder === 'DESC' ? 'DESC' : 'ASC') . ';';
                break;
            case 'totalCountStatus':
                $sqlQueries[] = 'SELECT "STATUS" AS `Label`, UPPER(status) AS `Value`, COUNT(status) AS `Total` FROM ' . $status . ' GROUP BY status ORDER BY COUNT(status) ' . ($sortingOrder === 'DESC' ? 'DESC' : 'ASC') . ';';
                break;
            case 'totalCountSchool':
                $columnName = ($dataChoice === 'ceap') ? 'school_name' : 'elementary_school';
                $sqlQueries[] = 'SELECT "SCHOOL" AS `Label`, UPPER(' . $columnName . ') AS `Value`, COUNT(' . $columnName . ') AS `Total` FROM ' . $tableName . ' GROUP BY ' . $columnName . ' ORDER BY COUNT(' . $columnName . ') ' . ($sortingOrder === 'DESC' ? 'DESC' : 'ASC') . ';';
                break;
            
    }
}
// Initialize an array to store totals for each label
$totals = [];

// Generate the HTML for each SQL query
foreach ($sqlQueries as $sqlQuery) {
    $result = mysqli_query($conn, $sqlQuery);

    if ($result) {
        // Retrieve the label for the current SQL query
        $label = '';  // Initialize $label variable
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

        // Generate the HTML table header for the current label
        $html .= '<p>This is to confirm the Santa Rosa City Government Scholars for the<br><strong>';

foreach ($filterOption as $option) {
    switch ($option) {
        case 'totalCountGender':
            $label = 'GENDER';
            break;
        case 'totalCountBarangay':
            $label = 'BARANGAY';
            break;
        case 'totalCountStatus':
            $label = 'STATUS';
            break;
        case 'totalCountSchool':
            $label = ($dataChoice === 'ceap') ? 'SCHOOL' : 'ELEMENTARY SCHOOL';
            break;
        default:
            $label = 'UNKNOWN';
            break;
    }

    // Add the label to the HTML
    $html .= 'TOTAL COUNT OF ' . $label . '';
}

$html .= '</strong> as follows:</p>';
   // Add the date generated
   $html .= '<p class="date-generated">Generated on: <strong>' . date('F j, Y') . '</strong></p>'; 
        $html .= '
                    <table>
                    <tr>
                        <th style="background-color: #D9D9D9; height: 20px; width: 70%; padding: 20px 20px; text-align: center;" >' . $label . '</th>
                        <th style="background-color: #D9D9D9; height: 20px; width: 30%; padding: 20px 20px; text-align: center;">TOTAL</th>
                    </tr>';

        // Generate the HTML table rows with calculated totals
        foreach ($totals[$label] as $data) {
            $html .= '<tr>';
            $html .= '<td>' . $data['Value'] . '</td>';
            $html .= '<td>' . $data['Total'] . '</td>';
            $html .= '</tr>';
        }

        $html .= '</table>';
    }
}

// Add a black line after the header
$pdf->SetLineWidth(0.2); // Set the line width as needed
$pdf->SetDrawColor(93, 93, 93); // Set the color to black

// Calculate x-coordinates for the line
$xStart = $pdf->GetX();
$xEnd = $pdf->GetPageWidth() - $pdf->GetMargins()['right'];

// Set the y-coordinate for the line
$yLine = $pdf->GetY() + 27; // Adjust this value to set the vertical position

$pdf->Line($xStart, $yLine, $xEnd, $yLine); // Draw a horizontal line

// Output the table to PDF
$pdf->SetY($yLine + 10); // Adjust the value as needed, adding some space after the line
$pdf->writeHTML($html, true, false, true, false, '');

// Output the HTML and CSS for debugging
$filename = 'iskolarosa_GENERATE_REPORT.pdf';

// Close and output the PDF
$pdf->Output($filename, 'I');

// Close the database connection
$conn->close();
?>