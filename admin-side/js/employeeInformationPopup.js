function goBacks() {
    window.location.href = "employee_list.php"; // Replace 'employee_list.php' with the actual page URL
}

const editemployeeConfigPopup = document.getElementById('EditEmployeeInfoPopUp');
const deleteEmployee = document.getElementById('DeleteEmployeeInfoPopUp');

function openEditEmployeeConfigPopup(){
    editemployeeConfigPopup.style.display ="block";
}
function closeemployeeInfoPopup(){
    if(editemployeeConfigPopup)
    {editemployeeConfigPopup.style.display="none";}
    if(deleteEmployee)
    {deleteEmployee.style.display="none";}
}
function toggleEditing() {
    var form = document.getElementById('editForm');
    var inputs = form.getElementsByTagName('input');
    var accountStatus = document.getElementById('accountStatus');
    var departmentDropdown = document.getElementById('department');

    if (document.querySelector('.editBtn').textContent === 'Edit') {
        // Switch to "Cancel" mode
        document.querySelector('.editBtn').textContent = 'Cancel';
        for (var i = 0; i < inputs.length; i++) {
            inputs[i].removeAttribute('disabled');
        }
        accountStatus.removeAttribute('disabled');
        departmentDropdown.removeAttribute('disabled');
    } else {
        // Switch to "Edit" mode
        document.querySelector('.editBtn').textContent = 'Edit';
        for (var i = 0; i < inputs.length; i++) {
            inputs[i].setAttribute('disabled', 'disabled');
        }
        accountStatus.setAttribute('disabled', 'disabled');
        departmentDropdown.setAttribute('disabled', 'disabled');
    }
}


function submitEditEmployeeInfo() {
    var xhr = new XMLHttpRequest();
    var form = new FormData(document.getElementById('editForm'));

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            // Handle the response, e.g., display a success message
            console.log(xhr.responseText.trim());
    
            setTimeout(() => {
            openEditEmployeeConfirmPopup();
            }, 1000);
        }
    };

    xhr.open('POST', './php/updateEmployeeInformation.php', true);
    xhr.send(form);
}

function openEditEmployeeConfirmPopup() {
    closeemployeeInfoPopup();
  const EditInfoconfirmPopup = document.getElementById("EditConfrimEmployeeConfigMsgPopUp");
  EditInfoconfirmPopup.style.display = "block";

  const EditOkButton = document.getElementById("okConfirmUpdate");
  EditOkButton.addEventListener("click", function () {
    EditInfoconfirmPopup.style.display = "none";
    goBacks();
  });
  // Call the goBack function after a 5-second delay
  setTimeout(goBacks, 113000);
}

function openDeleteEmployee(){
    deleteEmployee.style.display="block";
}
function closeDeleteEmployee(){
    deleteEmployee.style.display="none";
}

function submitDeleteEmployeeInfo() {
    var xhr = new XMLHttpRequest();
    var form = new FormData(document.getElementById('editForm'));

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            // Handle the response, e.g., display a success message
            console.log(xhr.responseText.trim());
            setTimeout(() => {
                openDeleteEmployeeConfirmPopup();
            }, 1000);
        }
    };

    xhr.open('POST', './php/deleteEmployeeInformation.php', true);
    xhr.send(form);
}


function openDeleteEmployeeConfirmPopup() {
    closeemployeeInfoPopup();
  const DeleteInfoconfirmPopup = document.getElementById("DeleteConfrimEmployeeConfigMsgPopUp");
  DeleteInfoconfirmPopup.style.display = "block";

  const DeleteOkButton = document.getElementById("okConfirmDelete");
  DeleteOkButton.addEventListener("click", function () {
    DeleteInfoconfirmPopup.style.display = "none";
    goBacks();
  });
  // Call the goBack function after a 5-second delay
  setTimeout(goBacks, 3000);
}


const EmployeeInfoConfirm = document.querySelectorAll('.confirm-button');
EmployeeInfoConfirm.forEach(function(button) {
    button.addEventListener('click', function() {
        this.classList.add('disabled');
        const EmployeeInfoCancel = this.parentElement.querySelector('.cancel-button');
        EmployeeInfoCancel.classList.add('disabled');
    });
});


const EmployeeInfoCancels = document.querySelectorAll(".cancel-button");
EmployeeInfoCancels.forEach((EmployeeInfoCancel) => {
EmployeeInfoCancel.addEventListener("click", closeemployeeInfoPopup);
});


function toggleSidebar() {
  var sidebar = document.getElementById("sidebar-wrapper");
  var tableContainer = document.querySelector(".table-container");

  if (sidebar.classList.contains("open")) {
    // Sidebar is currently open
    sidebar.classList.remove("open");
    tableContainer.style.width = "100%"; // Reset the width to 100%
  } else {
    // Sidebar is currently closed
    sidebar.classList.add("open");
    var sidebarWidth = sidebar.offsetWidth;
    var availableWidth = window.innerWidth - sidebarWidth;
    tableContainer.style.width = availableWidth + "px";
  }
}

function expandImage(img) {
  img.parentNode.querySelector(".expanded-image").style.display = "block";
}

function collapseImage(expandedImg) {
  expandedImg.style.display = "none";
}
