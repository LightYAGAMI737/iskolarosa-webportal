const employeeConfigPopup = document.getElementById('EmployeeConfigPopUp');

function openemployeeConfigPopup(){
    employeeConfigPopup.style.display ="block";
}

function closeemployeeConfigPopup(){
    employeeConfigPopup.style.display="none";
}


function submitEmployeeConfig() {
    var xhr = new XMLHttpRequest();
    var formData = new FormData(document.getElementById('employeeconfigform'));

    xhr.open('POST', 'employeeconfigurationinsert.php', true);
    xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                // Request was successful
                var response = xhr.responseText;
                console.log('Response:', response);
                setTimeout(() => {
                    openEmployeeConfirmationPopup();
            }, 1000);  
            } else {
                // Request failed
                console.error('Request failed with status ' + xhr.status);
            }
        }
    };
    xhr.onerror = function () {
        console.error('Network error');
    };
    xhr.send(formData);
}


const EmployeeConfigconfirmButtons = document.querySelectorAll('.confirm-button');
EmployeeConfigconfirmButtons.forEach(function(button) {
    button.addEventListener('click', function() {
        this.classList.add('disabled');
        const employeeConfigCancelButton = this.parentElement.querySelector('.cancel-button');
        employeeConfigCancelButton.classList.add('disabled');
    });
});


const employeeConfigCancelButtons = document.querySelectorAll(".cancel-button");
employeeConfigCancelButtons.forEach((employeeConfigCancelButton) => {
employeeConfigCancelButton.addEventListener("click", closeemployeeConfigPopup);
});

function openEmployeeConfirmationPopup() {
    closeemployeeConfigPopup();
  const confirmPopup = document.getElementById("ConfrimEmployeeConfigMsgPopUp");
  confirmPopup.style.display = "block";

  const okButton = document.getElementById("okConfirmCreate");
  okButton.addEventListener("click", function () {
    confirmPopup.style.display = "none";
    goBacks();
  });
  // Call the goBack function after a 5-second delay
  setTimeout(goBacks, 3000);
}
function goBacks() {
    window.location.href = "employee_list.php"; // Replace 'employee_list.php' with the actual page URL
}

