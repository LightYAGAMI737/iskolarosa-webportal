 <!-- confirmation of updated status -->
 <div class="popupOut" id="RescheduleMsgPopUp">
    <div class="popupIn">
      <i class="ri-checkbox-circle-fill" style="font-size: 6em; color: #0BA350;"></i>
      <strong>
        <h2>Interview Reschedule Successfully</h2>
      </strong>
      <center>
        <p>Applicant's interview date was rescheduled successfully.</p>
      </center>
      <div style="padding: 3px;">
        <button type="button" id="okConfirm" >
          <span>OK</span>
        </button>
      </div>
    </div>
  </div>

<!-- Reschedule Aplaya popup  -->
<div class="popupOut" id="RescheduleAplayaPopUp">
    <div class="popupIn">
        <i class="ri-question-fill" style="font-size: 6em; color: #F54021;"></i>
        <strong>
            <h2>Reschedule Applicant?</h2>
        </strong>
        <center>
            <p>Are you sure you want to proceed with the rescheduling of applicant(s)?</p>
        </center>
        <div style="padding: 3px;">
            <button type="button" class="cancel-button" style="margin-right: 15px; background-color: #C0C0C0;">
                <i class="ri-close-fill"></i>
                <span>Cancel</span>
            </button>
            <button type="button" class="confirm-button saveBtn" onclick="RescheduleInterview()">
                <i class="ri-check-fill"></i>
                <span>Confirm</span>
            </button>
        </div>
    </div>
</div>
