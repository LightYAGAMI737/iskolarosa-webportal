  // JavaScript code to handle the popup
  const submitPostPopup = document.getElementById("submit-post-popup");
  const discardPostPopup = document.getElementById("discard-post-popup");

   // JavaScript functions to open and close the popups
   function openPopup() {
    document.getElementById("submit-post-popup").style.display = "block";
}

function closePopup() {
    document.getElementById("submit-post-popup").style.display = "none";
}

function opendiscard() {
    document.getElementById("discard-post-popup").style.display = "block";
}

function closediscard() {
    document.getElementById("discard-post-popup").style.display = "none";
}

function openSuccessPopup() {
    document.getElementById("success-popup").style.display = "block";
    
    // Automatically close the success popup after 3 seconds
    setTimeout(() => {
        closeSuccessPopup();
    }, 13000); // 3-second delay
}

function closeSuccessPopup() {
    document.getElementById("success-popup").style.display = "none";
    
    // Reload the page
    location.reload();
}


function submitPost() {
    // Disable the buttons after submission
    const cancelButton = document.querySelector(".cancel-button");
    const submitButton = document.querySelector(".confirm-button");
    
    cancelButton.classList.remove("disabled");
    submitButton.classList.remove("disabled");

    // Get the form data
    const formData = new FormData(document.getElementById("post-form"));

    // Send an AJAX request to submit the form
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "./php/create_post_insert.php", true);
    xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
    
    // Handle the response from the server
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            // Enable the buttons
            cancelButton.classList.add("disabled");
            submitButton.classList.add("disabled");

            const response = JSON.parse(xhr.responseText);
            if (response.success) {
                // Clear the form fields
                document.getElementById("title").value = "";
                document.getElementById("tag").value = "ceap";
                document.getElementById("description").value = "";
                document.getElementById("image").value = "";
                document.getElementById("scheduled_at").value = "";

                // Close the first popup with a 2-second delay
                setTimeout(() => {
                    closePopup();

                    // Open the success popup
                    openSuccessPopup();
                }, 1000); // 2-second delay
            } else {
                // Handle any error or display an error message
                alert("Failed to submit the form. Please try again.");
            }
        }
    };

    // Send the form data
    xhr.send(formData);
}


function discardPost() {
    // Get the form element
    const postForm = document.getElementById("post-form");
    
    // Reset the form, clearing all input fields
    postForm.reset();

    // Close the discard post popup
    closediscard();
    location.reload();
}
