function openforgotemailconfirm() {
    const emailconfirmPopup = document.getElementById("EmailConfrimMsgPopUp");
    emailconfirmPopup.style.display = "block";
  
    const emailokButton = document.getElementById("okConfirm");
    emailokButton.addEventListener("click", function () {
      emailconfirmPopup.style.display = "none";
    });
    // Call the goBack function after a 5-second delay
    setTimeout(function () {
      window.location.href = "../admin_index.php";
    }, 5000);
  }
  