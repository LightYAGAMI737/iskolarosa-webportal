<!-- CEAP Configuration popup -->
<div class="popupOut" id="CEAPconfigurationPopUp">
    <div class="popupIn">
        <i class="ri-error-warning-line" style="font-size: 6em; color: #F54021;"></i>
        <strong>
            <h2>Submit Now?</h2>
        </strong>
        <center>
            <p>Confirming this action will save your changes to the application peroid. <br>Are you sure you want to proceed?</p>
        </center>
        <div style="padding: 3px;">
            <button type="button" class="cancel-button" style="margin-right: 15px; background-color: #C0C0C0;">
                <i class="ri-close-fill"></i>
                <span>Cancel</span>
            </button>
            <button type="button" id="submitConfigurationBtn" class="confirm-button" onclick="CEAPSubmitConfig()">
                    <i class="ri-check-fill"></i>
                <span>Confirm</span>
            </button>
        </div>
    </div>
</div>

<!-- LPPP Configuration popup -->
<div class="popupOut" id="LPPPconfigurationPopUp">
    <div class="popupIn">
        <i class="ri-error-warning-line" style="font-size: 6em; color: #F54021;"></i>
        <strong>
            <h2>Submit Now?</h2>
        </strong>
        <center>
            <p>Confirming this action will save your changes to the application peroid. <br>Are you sure you want to proceed?</p>
        </center>
        <div style="padding: 3px;">
            <button type="button" class="cancel-button" style="margin-right: 15px; background-color: #C0C0C0;">
                <i class="ri-close-fill"></i>
                <span>Cancel</span>
            </button>
            <button type="button" id="submitConfigurationBtn" class="confirm-button" onclick="LPPPSubmitConfig()">
                    <i class="ri-check-fill"></i>
                <span>Confirm</span>
            </button>
        </div>
    </div>
</div>
 <!-- confirmation of updated status -->
 <div class="popupOut" id="ConfigConfrimMsgPopUp">
    <div class="popupIn">
      <i class="ri-checkbox-circle-fill" style="font-size: 6em; color: #0BA350;"></i>
      <strong>
        <h2>Submitted Successfully</h2>
      </strong>
      <center>
        <p>The application period has been submitted in the Application Qualifications and Requirements section.</p>
      </center>
      <div style="padding: 3px;">
        <button type="button" id="okConfigConfirm" >
          <span>OK</span>
        </button>
      </div>
    </div>
  </div>