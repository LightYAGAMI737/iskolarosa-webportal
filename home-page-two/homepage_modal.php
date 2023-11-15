<?php
include '../admin-side/php/config_iskolarosa_db.php';

// Fetch the last inserted ID from the ceap_configuration table
$fetchReqAndQuali = "SELECT MAX(id) AS last_id FROM ceap_configuration";
$resultReqAndQuali = mysqli_query($conn, $fetchReqAndQuali);
$rowReqAndQuali = mysqli_fetch_assoc($resultReqAndQuali);
$lastId = $rowReqAndQuali['last_id'];
// Fetch data based on the last ID
if (!empty($lastId)) {
    $fetchReqAndQuali = "SELECT * FROM ceap_configuration WHERE id = ?";
    $stmtReqAndQuali = mysqli_prepare($conn, $fetchReqAndQuali);
    mysqli_stmt_bind_param($stmtReqAndQuali, "i", $lastId);
    mysqli_stmt_execute($stmtReqAndQuali);
    $resultReqAndQuali = mysqli_stmt_get_result($stmtReqAndQuali);

    if (mysqli_num_rows($resultReqAndQuali) > 0) {
        $rowReqAndQuali = mysqli_fetch_assoc($resultReqAndQuali);
        $qualifications = trim($rowReqAndQuali['qualifications']);
        $requirements = trim($rowReqAndQuali['requirements']);
    } else {
        echo "No results found.";
    }
}
mysqli_close($conn);
?>

<!-- HomePageModal -->
<div class="ModalOut" id="HomePageModal">
    <div class="ModalIn">

        <div class="closeButton">
            <button class="close-button" onclick="closeHomePageModal()">X</button>  
        </div>

        <div class="modal-header">
            <h5>Serbisyong Makatao Lungsod na Makabago COLLEGE EDUCATIONAL ASSISTANCE PROGRAM (CEAP)</h5>
        </div>

        <div class="modal-content">

            <!-- Left Column for Qualifications -->
            <div class="modal-column">
                <h3>Qualifications</h3>
                <p><?php echo $qualifications; ?></p>
            </div>

            <!-- Right Column for Requirements -->
            <div class="modal-column">
                <h3>Requirements</h3>
                <p><?php echo $requirements; ?></p>
            </div>

        </div>

        <div style="padding: 3px;">
            <button type="submit" class="applynow-button" name="submit" onclick="">
                <span>Apply Now</span>
            </button>
        </div>
    </div>
</div>
