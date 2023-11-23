<?php
include '../../admin-side/php/config_iskolarosa_db.php';

// Fetch the last inserted ID from the ceap_configuration table
$fetchReqAndQualiCEAP = "SELECT MAX(id) AS last_id FROM ceap_configuration";
$resultReqAndQualiCEAP = mysqli_query($conn, $fetchReqAndQualiCEAP);
$rowReqAndQualiCEAP = mysqli_fetch_assoc($resultReqAndQualiCEAP);
$lastIdCEAP = $rowReqAndQualiCEAP['last_id'];
// Fetch data based on the last ID
if (!empty($lastIdCEAP)) {
    $fetchReqAndQualiCEAP = "SELECT * FROM ceap_configuration WHERE id = ?";
    $stmtReqAndQuali = mysqli_prepare($conn, $fetchReqAndQualiCEAP);
    mysqli_stmt_bind_param($stmtReqAndQuali, "i", $lastIdCEAP);
    mysqli_stmt_execute($stmtReqAndQuali);
    $resultReqAndQualiCEAP = mysqli_stmt_get_result($stmtReqAndQuali);

    if (mysqli_num_rows($resultReqAndQualiCEAP) > 0) {
        $rowReqAndQualiCEAP = mysqli_fetch_assoc($resultReqAndQualiCEAP);
        $qualificationsCEAP = trim($rowReqAndQualiCEAP['qualifications']);
        $requirementsCEAP = trim($rowReqAndQualiCEAP['requirements']);
        $toggleValueCEAP =$rowReqAndQualiCEAP['toggle_value'];
    } else {
        echo "No results found CEAP.";
    }
}
// Fetch the last inserted ID from the lppp_configuration table
$fetchReqAndQualiLPPP = "SELECT MAX(id) AS last_id FROM lppp_configuration";
$resultReqAndQualiLPPP = mysqli_query($conn, $fetchReqAndQualiLPPP);
$rowReqAndQualiLPPP = mysqli_fetch_assoc($resultReqAndQualiLPPP);
$lastIdLPPP = $rowReqAndQualiLPPP['last_id'];
// Fetch data based on the last ID
if (!empty($lastIdLPPP)) {
    $fetchReqAndQualiLPPP = "SELECT * FROM lppp_configuration WHERE id = ?";
    $stmtReqAndQuali = mysqli_prepare($conn, $fetchReqAndQualiLPPP);
    mysqli_stmt_bind_param($stmtReqAndQuali, "i", $lastIdLPPP);
    mysqli_stmt_execute($stmtReqAndQuali);
    $resultReqAndQualiLPPP = mysqli_stmt_get_result($stmtReqAndQuali);

    if (mysqli_num_rows($resultReqAndQualiLPPP) > 0) {
        $rowReqAndQualiLPPP = mysqli_fetch_assoc($resultReqAndQualiLPPP);
        $qualificationsLPPP = trim($rowReqAndQualiLPPP['qualifications']);
        $requirementsLPPP = trim($rowReqAndQualiLPPP['requirements']);
        $toggleValueLPPP =$rowReqAndQualiLPPP['toggle_value'];
    } else {
        echo "No results found LPPP.";
    }
}
mysqli_close($conn);
?>

<!-- HomePageModalCEAP -->
<div class="ModalOut" id="HomePageModalCEAP">
    <div class="ModalIn">
    <div class="ModalInHeader"> 
    <button type="button" class="btn-close ceap-btn-close" aria-label="Close" onclick="closeHomePageModal()"></button>
    </div>
    <div class="modalBody">
        <div class="modalHeadingText">
            <h2>Serbisyong Makatao Lungsod na Makabago</h2>
            <h3>COLLEGE EDUCATIONAL ASSISTANCE PROGRAM (CEAP)</h3>
        </div>
        <div class="boxLeftRight">
        <div class="leftColumn">
            <h3>Qualification:</h3>
            <div class="scrollable-content">
                <p><?= $qualificationsCEAP ?></p>
            </div>
        </div>
        <div class="rightColumn">
            <h3>Requirement:</h3>
            <div class="scrollable-content">
                <p><?= $requirementsCEAP ?></p>
            </div>
    </div>
    </div>
    <div class="applBTN" style="padding: 3px; margin-top: 20px;">
            <!-- CEAP Apply Now Button -->
            <button type="button" class="applynow-button confirmBTN" id="ApplynowCEAP" name="submit" onclick="openCEAPApplyNowBtn()" disabled>
                <span>Apply Now</span>
            </button>
        </div>
    </div>
    </div>
</div>


<!-- HomePageModalLPPP -->
<div class="ModalOut" id="HomePageModalLPPP">
    <div class="ModalIn">
    <div class="ModalInHeader"> 
    <button type="button" class="btn-close lppp-btn-close" aria-label="Close" onclick="closeHomePageModal()"></button>
    </div>
    <div class="modalBody">
        <div class="modalHeadingText">
            <h2>Serbisyong Makatao Lungsod na Makabago</h2>
            <h3>LIBRENG PAGAARAL SA PRIBADONG PAARALAN (LPPP)</h3>
        </div>
        <div class="boxLeftRight">
        <div class="leftColumn">
            <h3>Qualification:</h3>
            <div class="scrollable-content">
                <p><?= $qualificationsLPPP ?></p>
            </div>
        </div>
        <div class="rightColumn">
            <h3>Requirement:</h3>
            <div class="scrollable-content">
                <p><?= $requirementsLPPP ?></p>
            </div>
    </div>
    </div>
        <div class="applBTN" style="padding: 3px; margin-top: 20px;">
            <!-- LPPP Apply Now Button -->
            <button type="button" class="applynow-button confirmBTN" id="ApplynowLPPP" name="submit" onclick="openLPPPApplyNowBtn()" disabled>
                <span>Apply Now</span>
            </button>
        </div>
    </div>
    </div>
</div>
<!-- CEAP Apply Now Button -->
<script>
    var toggleValueCEAP = <?php echo $toggleValueCEAP; ?>;
    var applyNowButtonCEAP = document.getElementById('ApplynowCEAP');
    
    if (toggleValueCEAP === 1) {
        applyNowButtonCEAP.removeAttribute('disabled');
    }
</script>

<!-- LPPP Apply Now Button -->
<script>
    var toggleValueLPPP = <?php echo $toggleValueLPPP; ?>;
    var applyNowButtonLPPP = document.getElementById('ApplynowLPPP');
    
    if (toggleValueLPPP === 1) {
        applyNowButtonLPPP.removeAttribute('disabled');
    }

</script>


<!-- applynow CEAP next button popup -->
<div class="RemindersOut" id="applynowNextButton">
    <div class="RemindersIn">
        <i class="ri-notification-2-fill" style="font-size: 6em; color: #F54021;"></i>
        <strong>
            <h2>Reminders</h2>
        </strong>
            <p style="margin-bottom: 2px;">1. Make sure to answer this form correctly and completely.<br>
                2. Fields with asterisk (*) must be filled up.<br>
                3. Make sure you've prepared all the documents needed for uploading: <br></p>
        <div class="reminders">
            <div class="column">
            <!-- <p>Applicant:</p> -->
                <div class="document">
                    <i class="ri-check-fill"></i>
                    <p>Applicant's Voters Certificate.</p>
                </div>
                <div class="document">
                    <i class="ri-check-fill"></i>
                    <p>2x2 Picture.</p>
                </div>
                <div class="document">
                    <i class="ri-check-fill"></i>
                    <p>Current semester's Grade.</p>
                </div>
                <div class="document">
                    <i class="ri-check-fill"></i>
                    <p>Certificate of Registration.</p>
                </div>
            </div>
            <div class="column">
            <!-- <p>Guardian:</p> -->
                <div class="document">
                    <i class="ri-check-fill"></i>
                    <p>Guardian's Voters Certificate.</p>
                </div>
                <div class="document">
                    <i class="ri-check-fill"></i>
                    <p>Guardian's Income Tax Return.</p>
                </div>
                <div class="document">
                    <i class="ri-check-fill"></i>
                    <p>Residency.</p>
                </div>
            </div>
        </div>
        <div style="padding: 3px;  margin-top: 10px;">
            <button type="button" class="cancel-button" style="margin-right: 15px; background-color: #C0C0C0;">
                <i class="ri-close-fill"></i>
                <span>Cancel</span>
            </button>
            <button type="submit" class="confirm-button confirmBTN" id="confirm-button-CEAP" name="submit" onclick="" disabled>
                <i class="ri-check-fill"></i>
                <span>OK</span>
            </button>
        </div>
    </div>
</div>



<!-- applynow LPPP next button popup -->
<div class="RemindersOut" id="applynowLPPPNextButton">
    <div class="RemindersIn">
        <i class="ri-notification-2-fill" style="font-size: 6em; color: #F54021;"></i>
        <strong>
            <h2>Reminders</h2>
        </strong>
            <p style="margin-bottom: 2px;">1. Make sure to answer this form correctly and completely.<br>
                2. Fields with asterisk (*) must be filled up.<br>
                3. Make sure you've prepared all the documents needed for uploading: <br></p>
        <div class="reminders">
            <div class="column">
            <!-- <p>Applicant:</p> -->
                <div class="document">
                    <i class="ri-check-fill"></i>
                    <p>2x2 Picture.</p>
                </div>
                <div class="document">
                    <i class="ri-check-fill"></i>
                    <p>Current semester's Grade.</p>
                </div>
            </div>
            <div class="column">
            <!-- <p>Guardian:</p> -->
                <div class="document">
                    <i class="ri-check-fill"></i>
                    <p>Guardian's Voters Certificate.</p>
                </div>
                <div class="document">
                    <i class="ri-check-fill"></i>
                    <p>Guardian's Income Tax Return.</p>
                </div>
                <div class="document">
                    <i class="ri-check-fill"></i>
                    <p>Residency.</p>
                </div>
            </div>
        </div>
        <div style="padding: 3px;  margin-top: 10px;">
            <button type="button" class="cancel-button" style="margin-right: 15px; background-color: #C0C0C0;">
                <i class="ri-close-fill"></i>
                <span>Cancel</span>
            </button>
            <button type="submit" class="confirm-button confirmBTN" id="confirm-button-LPPP" name="submit" onclick="" disabled>
                <i class="ri-check-fill"></i>
                <span>OK</span>
            </button>
        </div>
    </div>
</div>