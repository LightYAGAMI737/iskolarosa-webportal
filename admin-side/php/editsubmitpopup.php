<div class="popupOut" id="edit-submit-post-popup">
        <div class="popupIn">
            <i class="ri-loop-right-fill" style="font-size: 6em; color: #0BA350;"></i>
            <strong>
                <h2>Update Post?</h2>
            </strong>
            <center>
                <p>Are you sure you want to update this post? Your content will be shared with others. Double-check your details before submitting.</p>
            </center>
            <div style="padding: 3px;">
            <button type="button" style="margin-right: 15px;" class="cancel-button" onclick="closePopup()">
                <i class="ri-close-fill"></i>
                <span>Cancel</span>
            </button>
                <button type="button" class="confirm-button" name="submit" onclick="submiteditPost(<?php echo $createPostId; ?>)">
                    <i class="ri-check-fill"></i>
                    <span>Update</span>
                </button>
            </div>
        </div>
    </div>

    <div class="popupOut" id="edit-discard-post-popup">
        <div class="popupIn">
            <i class="ri-delete-bin-2-fill" style="font-size: 6em; color: #BE0309;"></i>
            <strong>
                <h2>Discard Post?</h2>
            </strong>
            <center>
                <p>Are you sure you want to discard this post? Any unsaved changes will be lost.</p>
            </center>
            <div style="padding: 3px;">
            <button type="button" style="margin-right: 15px;" class="cancel-button" onclick="closediscard()">
                <i class="ri-close-fill"></i>
                <span>Cancel</span>
            </button>
                <button type="button" class="confirm-button" name="discard" onclick="discardPost()">
                    <i class="ri-check-fill"></i>
                    <span>Discard</span>
                </button>
            </div>
        </div>
    </div>


    <div class="popupOut" id="edit-success-popup">
  <div class="popupIn">
    <!-- Content for the success popup -->
    <i class="ri-checkbox-circle-line" style="font-size: 6em; color: #0BA350;"></i>
    <strong>
      <h2>Success!</h2>
    </strong>
    <center>
      <p>Your post has been successfully updated.</p>
    </center>
    <div style="padding: 3px;">
      <button type="button" id="okButton" class="confirm-button" onclick="closeSuccessPopup()">
        <i class="ri-check-fill"></i>
        <span>OK</span>
      </button>
    </div>
  </div>
</div>