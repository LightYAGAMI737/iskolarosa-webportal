<!-- Delete img popup -->
<div class="popupOut" id="deletephotoPopUp">
    <div class="popupIn">
        <i class="ri-delete-bin-5-fill" style="font-size: 6em; color: #F54021;"></i>
        <strong>
            <h2>Delete Photo?</h2>
        </strong>
        <center>
            <p>This action is not irreversible</p>
        </center>
        <div style="padding: 3px;">
            <button type="button" class="cancel-button" style="margin-right: 15px; background-color: #C0C0C0;">
                <i class="ri-close-fill"></i>
                <span>Cancel</span>
            </button>
            <button type="submit" class="confirm-button" name="submit" id="deletephotoconfirm" onclick="deletephotoconfirm(<?php echo $createPostId; ?>)">
                <i class="ri-check-fill"></i>
                <span>Delete</span>
            </button>

        </div>
    </div>
</div>

 <!-- confirmation of updated status -->
 <div class="popupOut" id="ConfrimdeleteMsgPopUp">
    <div class="popupIn">
      <i class="ri-checkbox-circle-fill" style="font-size: 6em; color: #0BA350;"></i>
      <strong>
        <h2>Photo Deleted Successfully</h2>
      </strong>
          <div style="padding: 3px;">
        <button type="button" id="okConfirm" >
          <span>OK</span>
        </button>
      </div>
    </div>
  </div>
