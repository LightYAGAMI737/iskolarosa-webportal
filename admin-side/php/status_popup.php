 <!-- verified popup button -->
 <div class="verifiedPopUp" id="verifiedPopUp">
    <div class="open_verifiedPopUp" id="open_verifiedPopUp">
      <i class="ri-question-fill" style="font-size: 6em; color: #F54021;"></i>
      <strong>
        <h2>Verify this Applicant?</h2>
      </strong>
      <center>
        <p>Are you sure you want to proceed with this applicant's verification? Please note that by doing so, you are confirming the accuracy of the provided information.</p>
      </center>
      <div style="padding: 3px;">
        <button type="button" id="cancelConfirm" style="margin-right: 15px; background-color: #C0C0C0;">
          <i class="ri-close-fill"></i>
          <span>Cancel</span>
        </button>
        <button type="submit" name="submit" onclick="updateStatus('Verified', <?php echo $id; ?>)" id="submitConfirm">
          <i class="ri-check-fill"></i>
          <span>Confirm</span>
        </button>
      </div>
    </div>
  </div>