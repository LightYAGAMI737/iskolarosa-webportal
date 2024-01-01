<!-- Delete popup -->
<div class="popupOut" id="DeleteApplicantPopUpOLD">
    <div class="popupIn">
        <i class="ri-question-fill" style="font-size: 6em; color: #F54021;"></i>
        <strong>
            <h2>Delete this Applicant?</h2>
        </strong>
        <center>
            <p>Are you sure you want to proceed with the <strong>deletion</strong> of this applicant?<br>This is not irreversible.</p>
        </center>
        <div style="padding: 3px;">
            <button type="button" id="disqualified-cancel-button" class="cancel-button" style="margin-right: 15px; background-color: #C0C0C0;">
                <i class="ri-close-fill"></i>
                <span>Cancel</span>
            </button>
            <button type="submit" class="confirm-button" name="submit" onclick="deleteApplicant(<?php echo $id; ?>)">
    <i class="ri-check-fill"></i>
    <span>Delete</span>
</button>
        </div>
    </div>
    </div>
 <!-- confirmation of updated status -->
 <div class="popupOut" id="ConfrimMsgDeleteApplicantPopUp">
    <div class="popupIn">
      <i class="ri-checkbox-circle-fill" style="font-size: 6em; color: #0BA350;"></i>
      <strong>
        <h2>Applicant deleted Successfully</h2>
      </strong>
      <center>
        <p>The applicant's information can be viewed on the Archive Page.</p>
      </center>
      <div style="padding: 3px;">
        <button type="button" id="okConfirm" >
          <span>OK</span>
        </button>
      </div>
    </div>
  </div>