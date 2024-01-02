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
        $this->SetTextColor(165, 4, 10); // #A5040A
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
        // Add a black line after the footer
$lineStartXHeader = $this->GetX() + 1; // Adjust the starting x-coordinate as needed
$lineEndXHeader = $this->GetPageWidth() - $this->GetMargins()['right'] - 162; // Adjust the ending x-coordinate as needed

$lineYHeader = $this->GetY() + 5; // Adjust the vertical position as needed

$this->Line($lineStartXHeader, $lineYHeader, $lineEndXHeader, $lineYHeader);
    }
      
    public function AddPage($orientation = '', $format = '', $keepmargins = false, $tocpage = false) {
        // Check if it's the second page or subsequent pages
        if ($this->PageNo() >= 1) {
            // Set the margins for the second page and subsequent pages
            parent::SetMargins(24, 55, 24, true);
        } 
    
        // Set the auto page break distance from the bottom (in millimeters)
        parent::SetAutoPageBreak(true, 33);
    
        // Call the parent AddPage method
        parent::AddPage($orientation, $format, $keepmargins, $tocpage);
    }

// Footer
public function Footer() {
    // Set line width for the footer
    $this->SetLineWidth(0.2);

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

// Add a black line after the footer
$lineStartX = $this->GetX() - 10; // Adjust the starting x-coordinate as needed
$lineEndX = $this->GetPageWidth() - $this->GetMargins()['right'] - 160; // Adjust the ending x-coordinate as needed

$lineY = $this->GetY() - 2; // Adjust the vertical position as needed

$this->Line($lineStartX, $lineY, $lineEndX, $lineY);
}
    }

// Create a new PDF document with dataChoice
$pdf = new MYPDF($_POST['dataChoice'], 'P', 'mm', 'A4', true, 'UTF-8', false);
$pdf->SetMargins(24, 24, 24, true); // 2.4cm margins on each side

// Add a page break before the table
$pdf->AddPage();

// Set the document information
$pdf->SetCreator('iSKOLAROSA');
$pdf->SetAuthor('Scholarship Office of Santa Rosa');
$pdf->SetTitle('Reports');

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

// Execute SQL query to count total number of applicants
$countQuery = "SELECT COUNT(*) AS TotalRows FROM $tableName";
$countResult = mysqli_query($conn, $countQuery);
$totalNumberOfApplicants = 0;

if ($countResult) {
    $countRow = mysqli_fetch_assoc($countResult);
    $totalNumberOfApplicants = $countRow['TotalRows'];
}

foreach ($filterOption as $option) {
    switch ($option) {
        case 'listOfApplicants':
            $sqlQueries[] = 'SELECT "NAME" AS `Label`, CONCAT(UPPER(last_name), ", ", UPPER(first_name)) AS `Value`, control_number FROM ' . $tableName . ' ' . ($sortingOrder === 'DESC' ? 'ORDER BY last_name DESC' : 'ORDER BY last_name ASC') . ';';
            break;
        case 'totalCountGender':
            $sqlQueries[] = 'SELECT "SEX" AS `Label`, UPPER(gender) AS `Value`, COUNT(gender) AS `Total` FROM ' . $tableName . ' GROUP BY gender ORDER BY gender ' . ($sortingOrder === 'DESC' ? 'DESC' : 'ASC') . ';';
            break;
            case 'totalCountBarangay':
                $sqlQueries[] = 'SELECT "BARANGAY" AS `Label`, UPPER(barangay) AS `Value`, COUNT(barangay) AS `Total` FROM ' . $tableName . ' GROUP BY barangay ORDER BY COUNT(barangay) ' . ($sortingOrder === 'DESC' ? 'DESC' : 'ASC') . ';';
                break;
            case 'totalCountStatus':
                $sqlQueries[] = 'SELECT "STATUS" AS `Label`, UPPER(status) AS `Value`, COUNT(status) AS `Total` FROM ' . $status . ' GROUP BY status ORDER BY COUNT(status) ' . ($sortingOrder === 'DESC' ? 'DESC' : 'ASC') . ';';
                break;
            
    }
}

// Mapping array for Barangay values
$barangayMapping = [
    'PULONGSANTACRUZ' => 'PULONG SANTA CRUZ',
    'SANTODOMINGO' => 'SANTO DOMINGO',
    'MARKETAREA' => 'MARKET AREA',
    'DONJOSE' => 'DON JOSE',
    // Add more mappings as needed
];

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
            $total = isset($row['Total']) ? $row['Total'] : $row['control_number']; // Use control_number if Total is not set
           
            if ($label === 'BARANGAY') {
                // Replace fetched value with the mapped display value
                $value = isset($barangayMapping[$value]) ? $barangayMapping[$value] : $value;
            }

            // Initialize total for this label if not set
            if (!isset($totals[$label])) {
                $totals[$label] = [];
            }

           // Store each value separately with its total
        $totals[$label][] = ['Value' => $value, 'Total' => isset($row['Total']) ? $row['Total'] : (isset($row['control_number']) ? $row['control_number'] : 0)];

        }


        // Generate the HTML table header for the current label
        $html .= '<p>This is to confirm the Santa Rosa City Government Scholars for the<br><strong>';

        foreach ($filterOption as $option) {
            switch ($option) {
                case 'listOfApplicants':
                    $label = 'NAME';
                    $label2 = "CONTROL NUMBER";
                    break;
                case 'totalCountGender':
                    $label = 'SEX';
                    $label2 = "TOTAL";
                    break;
                case 'totalCountBarangay':
                    $label = 'BARANGAY';
                    $label2 = "TOTAL";
                    break;
                case 'totalCountStatus':
                    $label = 'STATUS';
                    $label2 = "TOTAL";
                    break;
                default:
                    $label = 'UNKNOWN';
                    break;
            }

            // Add the label to the HTML
            $html .= $option === 'listOfApplicants' ? 'LIST OF APPLICANTS' : 'TOTAL COUNT OF ' . $label;
        }

$html .= '</strong> as follows:</p>';
  // Add the date generated
$html .= '<p class="date-generated">Generated on: <strong>' . date('F j, Y') . '</strong></p>';
// Start the table
$html .= '<table>';

if ($option == "listOfApplicants") {
    // Add table row for "listOfapplicant" option
    $html .= '<tr>
                <th style="background-color: #D9D9D9; height: 20px; width: 30%; padding: 20px 20px; text-align: center;">' . $label2 . '</th>
                <th style="background-color: #D9D9D9; height: 20px; width: 70%; padding: 20px 20px; text-align: center;" >' . $label . '</th>
              </tr>';
    // Generate the HTML table rows with calculated totals
    foreach ($totals[$label] as $data) {
        $html .= '<tr>';
        // Check if the 'Total' key exists before accessing it
        $totalValue = isset($data['Total']) ? $data['Total'] : 0;
        $html .= '<td>' . $totalValue . '</td>';
        $html .= '<td>' . $data['Value'] . '</td>';
        $html .= '</tr>';
    }
    $html .= '</table>';

    // display the total count of all data in the table
    $html .= '<p><strong>Total number of Applicants: ' . $totalNumberOfApplicants . '</strong></p>';
} else {
    // Add table row for other options
    $html .= '<tr>
                <th style="background-color: #D9D9D9; height: 20px; width: 70%; padding: 20px 20px; text-align: center;" >' . $label . '</th>
                <th style="background-color: #D9D9D9; height: 20px; width: 30%; padding: 20px 20px; text-align: center;">' . $label2 . '</th>
              </tr>';


        // Generate the HTML table rows with calculated totals
        foreach ($totals[$label] as $data) {
            $html .= '<tr>';
            $html .= '<td>' . $data['Value'] . '</td>';

            // Check if the 'Total' key exists before accessing it
            $totalValue = isset($data['Total']) ? $data['Total'] : 0;

            $html .= '<td>' . $totalValue . '</td>';
            $html .= '</tr>';
        }
        $html .= '</table>';
    }
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

// Output the table to PDF
$pdf->SetY($yLine + 10); // Adjust the value as needed, adding some space after the line
// Output the HTML and CSS for debugging

$pdf->writeHTML($html, true, false, true, false, '');

// Output the HTML and CSS for debugging
$filename = 'iskolarosa_GENERATE_REPORT.pdf';

// Close and output the PDF
$pdf->Output($filename, 'I');

// Close the database connection
$conn->close();
?>