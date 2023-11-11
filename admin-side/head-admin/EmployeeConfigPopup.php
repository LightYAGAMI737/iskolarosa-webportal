<!-- EmployeeConfig popup -->
<div class="popupOut" id="EmployeeConfigPopUp">
    <div class="popupIn">
        <i class="ri-question-fill" style="font-size: 6em; color: #F54021;"></i>
        <strong>
            <h2>Create Employee Account?</h2>
        </strong>
        <center>
            <p>Are you sure you want to proceed with the creation of Employee Account?  Please note that by doing so, you are confirming the accuracy of the provided information.</p>
        </center>
        <div style="padding: 3px;">
            <button type="button" class="cancel-button" style="margin-right: 15px; background-color: #C0C0C0;">
                <i class="ri-close-fill"></i>
                <span>Cancel</span>
            </button>
            <button type="submit" class="confirm-button" name="submit" onclick="submitEmployeeConfig()">
                <i class="ri-check-fill"></i>
                <span>Create</span>
            </button>
        </div>
    </div>
</div>

 <!-- confirmation of updated status -->
 <div class="popupOut" id="ConfrimEmployeeConfigMsgPopUp">
    <div class="popupIn">
      <i class="ri-checkbox-circle-fill" style="font-size: 6em; color: #0BA350;"></i>
      <strong>
        <h2>Success!</h2>
      </strong>
      <center>
        <p>Employee Account created successfully.</p>
      </center>
      <div style="padding: 3px;">
        <button type="button" id="okConfirm" >
          <span>OK</span>
        </button>
      </div>
    </div>
  </div>