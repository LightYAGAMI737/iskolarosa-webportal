<!-- LPPPConfirm popup -->
<div class="popupOut" id="lpppConfirmPopUp">
    <div class="popupIn">
        <i class="ri-error-warning-line" style="font-size: 6em; color: #F54021;"></i>
        <strong>
            <h2>Submit Application?</h2>
        </strong>
        <center>
            <p>You are about to submit your Application Form. Submitting this application will hereby confirm that you have reviewed and provided true and correct information.</p>
        </center>
        <div style="padding: 3px;">
            <button type="button" class="cancel-button" style="margin-right: 15px; background-color: #C0C0C0;">
                <i class="ri-close-fill"></i>
                <span>Cancel</span>
            </button>
            <button type="submit" class="confirm-button" name="submit" onclick="submitFormLPPP()">
                <i class="ri-check-fill"></i>
                <span>Confirm</span>
            </button>
        </div>
    </div>
</div>

 <!-- confirmation msg application -->
 <div class="popupOut" id="LPPPConfrimMsgPopUp">
    <div class="popupIn">
      <i class="ri-checkbox-circle-line" style="font-size: 6em; color: #0BA350;"></i>
      <strong>
        <h2>Application Submitted</h2>
      </strong>
      <center>
        <p>Thank you for submitting your application! An email with a temporary account has been sent to your email address. You can now login to check your application status.</p>
      </center>
      <div style="padding: 3px;">
        <button type="button" id="okConfirm" >
          <span>OK</span>
        </button>
      </div>
    </div>
  </div>