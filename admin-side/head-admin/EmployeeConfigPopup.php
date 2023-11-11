<!-- create EmployeeConfig popup -->
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

 <!-- confirmation of created account -->
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
        <button type="button" class="okConfirms" id="okConfirmCreate" >
          <span>OK</span>
        </button>
      </div>
    </div>
  </div>

  <!-- edit EmployeeConfig popup -->
<div class="popupOut" id="EditEmployeeInfoPopUp">
    <div class="popupIn">
        <i class="ri-question-fill" style="font-size: 6em; color: #F54021;"></i>
        <strong>
            <h2>Update Employee Information?</h2>
        </strong>
        <center>
            <p>Are you sure you want to <strong>Update</strong> this Employee's information?  Please note that by doing so, you are confirming the accuracy of the provided information.</p>
        </center>
        <div style="padding: 3px;">
            <button type="button" class="cancel-button" style="margin-right: 15px; background-color: #C0C0C0;">
                <i class="ri-close-fill"></i>
                <span>Cancel</span>
            </button>
            <button type="submit" class="confirm-button" name="submit" onclick="submitEditEmployeeInfo()">
                <i class="ri-check-fill"></i>
                <span>Update</span>
            </button>
        </div>
    </div>
</div>

 <!-- confirmation of updated info applicant -->
 <div class="popupOut" id="EditConfrimEmployeeConfigMsgPopUp">
    <div class="popupIn">
      <i class="ri-checkbox-circle-fill" style="font-size: 6em; color: #0BA350;"></i>
      <strong>
        <h2>Success!</h2>
      </strong>
      <center>
        <p>Employee Account <strong>Updated</strong> Successfully.</p>
      </center>
      <div style="padding: 3px;">
        <button type="button" class="okConfirm" id="okConfirmUpdate" >
          <span>OK</span>
        </button>
      </div>
    </div>
  </div>


  
  <!-- delete EmployeeConfig popup -->
<div class="popupOut" id="DeleteEmployeeInfoPopUp">
    <div class="popupIn">
        <i class="ri-question-fill" style="font-size: 6em; color: #F54021;"></i>
        <strong>
            <h2>Delete This Employee?</h2>
        </strong>
        <center>
            <p>Are you sure you want to <strong>Delete</strong> this Employee?  This action is irreversible</p>
        </center>
        <div style="padding: 3px;">
            <button type="button" class="cancel-button" style="margin-right: 15px; background-color: #C0C0C0;">
                <i class="ri-close-fill"></i>
                <span>Cancel</span>
            </button>
            <button type="submit" class="confirm-button" name="submit" onclick="submitDeleteEmployeeInfo()">
                <i class="ri-check-fill"></i>
                <span>Delete</span>
            </button>
        </div>
    </div>
</div>

 <!-- confirmation of deleted applicant -->
 <div class="popupOut" id="DeleteConfrimEmployeeConfigMsgPopUp">
    <div class="popupIn">
      <i class="ri-checkbox-circle-fill" style="font-size: 6em; color: #0BA350;"></i>
      <strong>
        <h2>Success!</h2>
      </strong>
      <center>
        <p>Employee Account <strong>Deleted</strong> Successfully.</p>
      </center>
      <div style="padding: 3px;">
        <button type="button" class="okConfirms" id="okConfirmDelete" >
          <span>OK</span>
        </button>
      </div>
    </div>
  </div>