<?php
require_once '../../admin-side/TCPDF-main/tcpdf.php';
require_once 'pagpapatunayPHP.php';

class PDF extends TCPDF {
    public function Header() {
        // Add your header content here, if needed
    }

    public function Footer() {
        // Add your footer content here, if needed
    }
}

// Create PDF instance
$pdf = new PDF();

// Set margins
$margin = 24; // in millimeters
$pdf->SetMargins($margin, $margin, $margin);

$pdf->SetAutoPageBreak(true, $margin);

// Add a page
$pdf->AddPage();

// Set font
$pdf->SetFont('helvetica', '', 11);

// Add content to the PDF
$html = '
    <div style="text-align: center; font-size: 16px; margin-bottom: 20px;">
        <strong>PAGPAPATUNAY</strong>
    </div>
    <div style=" text-align: right;">
        <strong>Control number: </strong>' . $control_number . '
    </div>
    <div>
        <p>Sa Kinauukulan,</p>
        <p>Tinatanggap ko ang halagang Limang Libong Piso ( Php 5,000.00 ) na tulong pinansyal sa aking pag-aaral sa ' . $uppercaseSchoolName . '.</p>
        <p>Sa ilalim ng programang <strong>"SERBISYONG MAKATAO, LUNGSOD NA MAKABAGO - COLLEGE EDUCATIONAL ASSISTANCE PROGRAM (CEAP)"</strong> ng Pamahalaang Lungsod ng Santa Rosa, Laguna para sa Panuruang taon (' . $lastYear . '  -  ' . $currentYear . ').</p>
        <p>Ang pagpapatunay na ito ay nangangahulugan na ako ay isa sa napagkalooban ng "CEAP" ni Kgg. Arlene B. Arcillas.</p>
        <p>Nilagdaan ko ang pagpapatunay na ito ngayong ______________ ' . $currentYear . ', </p>
    </div>
    <div style="margin-top: 20px;">
        <p style="text-align: right; text-decoration: underline; text-transform: uppercase;">' . $first_name . ', ' . $last_name . ' </p>
        <p style="text-align: right;">pangalan ng nabenepisyuhan</p>
        <p style="text-align: right;">_______________________</p>
        <p style="text-align: right;">lagda ng nabenepisyuhan</p>
    </div>
    <div style="margin-top: 10px;">
        <p style="text-align: left;">Binigyang Pansin:</p><br><br><br>
        <p style="text-align: left; text-decoration: underline; text-transform: uppercase;">' . $guardian_firstname . ', ' . $guardian_lastname . '</p>
        <p style="text-align: left;">Pangalan at Lagda ng Magulang ng Nabenipisyuhan</p>
        <p style="text-align: left;">_______________________</p>
        <p style="text-align: left;">Kinatawan ng tanggapan ng Scholarship</p>
    </div>
';

$pdf->writeHTML($html, true, false, true, false, '');
// Output the PDF as a download
$pdf->Output('pagpapatunay.pdf', 'I');
?>
