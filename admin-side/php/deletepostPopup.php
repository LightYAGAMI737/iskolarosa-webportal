<!-- Delete Post popup -->
<div class="popupOut" id="DeletepostPopUp">
    <div class="popupIn">
        <i class="ri-delete-bin-fill" style="font-size: 6em; color: #A5040A;"></i>
        <strong>
            <h2>Delete Post?</h2>
        </strong>
        <center>
            <p>Deleting the selected post(s) is permanent<br> and irreversible. <br> Are you sure you want to proceed?</p>
        </center>
        <div style="padding: 3px;">
            <button type="button" class="cancel-button" style="margin-right: 15px; background-color: #C0C0C0;">
                <i class="ri-close-fill"></i>
                <span>Cancel</span>
            </button>
            <button type="button" id="submitpostBtn" class="confirm-button" onclick="deletePostSubmit()">
                    <i class="ri-check-fill"></i>
                <span>Confirm</span>
            </button>
        </div>
    </div>
</div>



 <!-- confirmation of updated status -->
 <div class="popupOut" id="ConfrimMsgDeletePopUp">
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