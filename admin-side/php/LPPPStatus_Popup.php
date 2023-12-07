 <!-- confirmation of updated status -->
 <div class="popupOut" id="ConfrimMsgPopUp">
    <div class="popupIn">
      <i class="ri-checkbox-circle-fill" style="font-size: 6em; color: #0BA350;"></i>
      <strong>
        <h2>Status Updated Successfully</h2>
      </strong>
      <center>
        <p>Applicant's status was updated successfully.</p>
      </center>
      <div style="padding: 3px;">
        <button type="button" id="okConfirm" >
          <span>OK</span>
        </button>
      </div>
    </div>
  </div>

<!-- LPPPVerified popup -->
<div class="popupOut" id="LPPPverifiedPopUp">
    <div class="popupIn">
        <i class="ri-question-fill" style="font-size: 6em; color: #F54021;"></i>
        <strong>
            <h2>Update Applicant Status?</h2>
        </strong>
        <center>
            <p>Are you sure you want to proceed with the updating of applicant's status to <strong>Verified</strong>?  Please note that by doing so, you are confirming the accuracy of the provided information.</p>
        </center>
        <div style="padding: 3px;">
            <button type="button" class="cancel-button" style="margin-right: 15px; background-color: #C0C0C0;">
                <i class="ri-close-fill"></i>
                <span>Cancel</span>
            </button>
            <button type="submit" class="confirm-button" name="submit" onclick="updateStatusLPPP('Verified', <?php echo $id; ?>)">
                <i class="ri-check-fill"></i>
                <span>Confirm</span>
            </button>
        </div>
    </div>
</div>

<!-- Disqualified popup  -->
<div class="popupOut" id="DisqualifiedPopUp">
    <div class="popupIn">
        <i class="ri-question-fill" style="font-size: 6em; color: #F54021;"></i>
        <strong>
            <h2>Update Applicant Status?</h2>
        </strong>
        <center>
            <p>Are you sure you want to proceed with the updating of applicant's status to <strong>Disqualified</strong>?  Please note that by doing so, you are confirming the accuracy of the provided information.</p>
        </center>
        <div style="padding: 3px;">
            <button type="button" id="disqualified-cancel-button" class="cancel-button" style="margin-right: 15px; background-color: #C0C0C0;">
                <i class="ri-close-fill"></i>
                <span>Cancel</span>
            </button>
            <button type="submit" class="confirm-button" name="submit" id="confirmButton">
    <i class="ri-check-fill"></i>
    <span>Confirm</span>
</button>

        </div>
    </div>
</div>

<!-- examLPPP popup  -->
<div class="popupOut" id="examLPPPPopUp">
    <div class="popupIn">
        <i class="ri-question-fill" style="font-size: 6em; color: #F54021;"></i>
        <strong>
            <h2>Update Applicant Status?</h2>
        </strong>
        <center>
            <p>Are you sure you want to proceed with the updating of applicant's status to <strong>Exam</strong>?  Please note that by doing so, you are confirming the accuracy of the provided information.</p>
        </center>
        <div style="padding: 3px;">
            <button type="button" class="cancel-button" style="margin-right: 15px; background-color: #C0C0C0;">
                <i class="ri-close-fill"></i>
                <span>Cancel</span>
            </button>
            <button type="button" class="confirm-button saveBtn" id="confirmSetexamLPPP">
                <i class="ri-check-fill"></i>
                <span>Confirm</span>
            </button>
        </div>
    </div>
</div>

<!-- Interview popup  -->
<div class="popupOut" id="interviewPopUp">
    <div class="popupIn">
        <i class="ri-question-fill" style="font-size: 6em; color: #F54021;"></i>
        <strong>
            <h2>Update Applicant Status?</h2>
        </strong>
        <center>
            <p>Are you sure you want to proceed with the updating of applicant's status to <strong>Interview</strong>?  Please note that by doing so, you are confirming the accuracy of the provided information.</p>
        </center>
        <div style="padding: 3px;">
            <button type="button" class="cancel-button" style="margin-right: 15px; background-color: #C0C0C0;">
                <i class="ri-close-fill"></i>
                <span>Cancel</span>
            </button>
            <button type="button" class="confirm-button saveBtn" id="confirmSetInterview">
                <i class="ri-check-fill"></i>
                <span>Confirm</span>
            </button>
        </div>
    </div>
</div>

<!-- Grantee popup  -->
<div class="popupOut" id="GranteePopUp">
    <div class="popupIn">
        <i class="ri-question-fill" style="font-size: 6em; color: #F54021;"></i>
        <strong>
            <h2>Update Applicant Status?</h2>
        </strong>
        <center>
            <p>Are you sure you want to proceed with the updating of applicant's status to <strong>Grantee</strong>? Please note that by doing so, you are confirming the accuracy of the provided information.</p>
        </center>
        <div style="padding: 3px;">
            <button type="button" class="cancel-button" style="margin-right: 15px; background-color: #C0C0C0;">
                <i class="ri-close-fill"></i>
                <span>Cancel</span>
            </button>
            <button type="submit" class="confirm-button" name="submit" onclick="updateStatus('Grantee', <?php echo $id; ?>)">
                <i class="ri-check-fill"></i>
                <span>Confirm</span>
            </button>
        </div>
    </div>
</div>


