<?php
require_once '../../admin-side/TCPDF-main/tcpdf.php';
require_once '../../admin-side/php/config_iskolarosa_db.php'; // Include your database connection script
require_once 'pagpapatunayPHP.php';

// Set the time zone to Asia/Manila
date_default_timezone_set('Asia/Manila');
// Extend TCPDF to create a custom footer
class MYPDF extends TCPDF {

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
        $headerText = 'COLLEGE EDUCATIONAL ASSISTANCE PROGRAM (CEAP)' ;

        $currentYear = date('Y'); // Get the current year dynamically
        $lastYear = $currentYear - 1; // Assuming academic year starts from the previous year
        $headerText2 = 'Academic Year ' . $lastYear . ' - ' . $currentYear;

        $this->SetFont('helvetica', 'B', 12);
        $this->Cell(0, 15, $headerText, 0, false, 'C', 0, '', 0, false, 'M', 'M');
        $this->Ln(6); // Add a larger line break to lower the position
        $this->SetFont('helvetica', 12);
        $this->Cell(0, 25, $headerText2, 0, false, 'C', 0, '', 0, false, 'M', 'M');
        
        // Add a black line after the footer
        $lineStartXHeader = $this->GetX() + 1; // Adjust the starting x-coordinate as needed
        $lineEndXHeader = $this->GetPageWidth() - $this->GetMargins()['right'] - 162; // Adjust the ending x-coordinate as needed

        $lineYHeader = $this->GetY() + 5; // Adjust the vertical position as needed

        $this->Line($lineStartXHeader, $lineYHeader, $lineEndXHeader, $lineYHeader);
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
}
    }

// Create a new PDF document with dataChoice
$pdf = new MYPDF('P', 'mm', 'A4', true, 'UTF-8', false);
$pdf->SetMargins(24, 24, 24, true); // 2.4cm margins on each side

// Add a page break before the table
$pdf->AddPage();

// Set the document information
$pdf->SetCreator('iSKOLAROSA');
$pdf->SetAuthor('Scholarship Office of Santa Rosa');
$pdf->SetTitle('Pagpapatunay');

// Set font// Set font
$pdf->SetFont('helvetica', '', 11);
$html = '
    <style>
   
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
        }

        .pdf-header {
            font-size: 16px;
            text-align: center;
            letter-spacing: 3px;
        }

        .pdf-footer {
            font-size: 11px;
            text-align: right;
        }
        .pdf-content {
            font-size: 11px;
            text-indent: 20px;
        }
        .pdf-signature {
            font-size: 11px;
            text-align: right;
        }
    
        .pdf-guardian {
            font-size: 11px;
            text-align: left;
            position: relative;
        }

    </style>
    
    <div class="pdf-header">
        <strong>PAGPAPATUNAY</strong>
    </div>
    <div class="pdf-footer">
        Control number: <strong>' . $control_number . '</strong>
    </div>
      Sa Kinauukulan,<br>
      <div class="pdf-content">
      Tinatanggap ko ang halagang Limang Libong Piso ( Php 5,000.00 ) na tulong pinansyal sa aking pag-aaral sa <strong>' . $uppercaseSchoolName . '</strong>.<br><br>
        Sa ilalim ng programang <strong>"SERBISYONG MAKATAO, LUNGSOD NA MAKABAGO - COLLEGE EDUCATIONAL ASSISTANCE PROGRAM (CEAP)"</strong> ng <span style="font-style: italic;">Pamahalaang Lungsod ng Santa Rosa, Laguna para sa Panuruang taon (' . $lastYear . '  -  ' . $currentYear . ').</span><br><br>
        Ang pagpapatunay na ito ay nangangahulugan na ako ay isa sa napagkalooban ng "CEAP" ni Kgg. Arlene B. Arcillas.<br><br>
        Nilagdaan ko ang pagpapatunay na ito ngayong ______________ ' . $currentYear . ',
    </div>
    <div class="pdf-signature">
        <p style="text-transform: uppercase;"><strong>' . $first_name . ', ' . $last_name . '</strong> </p>
        <p style="margin-bottom: 20px;">Pangalan ng Nabenepisyuhan</p><br><br>
        <p>Lagda ng Nabenepisyuhan</p> 
    </div>
    <div class="pdf-guardian">
        <p>Binigyan ng Pansin:</p><br><br>
        <p style="text-transform: uppercase;"><strong>' . $guardian_firstname . ', ' . $guardian_lastname . '</strong></p>
        <p>Pangalan at Lagda ng Magulang ng Nabenipisyuhan<br><br>
        <p style="margin-bottom: 20px;">Kinatawan ng tanggapan ng Scholarship</p>
    </div>
';
// Add lines before and after content
$lineYSignatureBefore = $pdf->GetY() + 140; // Adjust the vertical position as needed

// Change the width to 1.5 and adjust the x-coordinate to position it on the right side
$pdf->SetLineWidth(0.1);
$pdf->Line($pdf->GetPageWidth() - 80, $lineYSignatureBefore, $pdf->GetPageWidth() - 20, $lineYSignatureBefore);

// Add lines before and after content
$lineYSignatureAfter = $pdf->GetY() + 160; // Adjust the vertical position as needed

// Change the width to 1.5 and adjust the x-coordinate to position it on the right side
$pdf->SetLineWidth(0.1);
$pdf->Line($pdf->GetPageWidth() - 80, $lineYSignatureAfter, $pdf->GetPageWidth() - 20, $lineYSignatureAfter);


$lineYGuardianAfter = $pdf->GetY() + 218; // Adjust the vertical position as needed

// Change the width to 1.5 and adjust the x-coordinate to position it on the left side
$pdf->SetLineWidth(0.1);
$pdf->Line(24, $lineYGuardianAfter, 120, $lineYGuardianAfter);

$lineYGuardianBefore = $pdf->GetY() + 238; // Adjust the vertical position as needed

// Change the width to 1.5 and adjust the x-coordinate to position it on the left side
$pdf->SetLineWidth(0.1);
$pdf->Line(24, $lineYGuardianBefore, 120, $lineYGuardianBefore);


// $lineYGuardianAfter = $pdf->GetY() + 5; // Adjust the vertical position as needed
// $pdf->Line(20, $lineYGuardianAfter, $pdf->GetPageWidth() - 20, $lineYGuardianAfter);
// Set the y-coordinate for the line
$yLine = $pdf->GetY() + 27; // Adjust this value to set the vertical position

// Output the table to PDF
$pdf->SetY($yLine + 10); // Adjust the value as needed, adding some space after the line
// Output the HTML and CSS for debugging

$pdf->writeHTML($html, true, false, true, false, '');

// Output the HTML and CSS for debugging
$filename = 'Pagpapatunay_' . $control_number . '.pdf';


// Close and output the PDF
$pdf->Output($filename, 'I');

// Close the database connection
$conn->close();

?> 