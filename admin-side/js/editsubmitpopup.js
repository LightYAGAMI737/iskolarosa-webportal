const editsubmitpopup = document.getElementById('edit-submit-post-popup');
const editdiscardpopup = document.getElementById('edit-discard-post-popup');

function openeditsubmitPopup() {
    editsubmitpopup.style.display="block";
}

function editsubmitclosepopup() {
    editsubmitpopup.style.display="none";
}

function openeditdiscardPopup() {
    editdiscardpopup.style.display="block";
}

function editdiscardclosepopup() {
    editdiscardpopup.style.display="none";
}

const editsubmitconfirmButtons = document.querySelectorAll('.confirm-button');
 editsubmitconfirmButtons.forEach(function(button) {
    button.addEventListener('click', function() {
        this.classList.add('disabled');
        const editsubmitcelButton = this.parentElement.querySelector('.cancel-button');
        editsubmitcelButton.classList.add('disabled');
    });
});


const editsubmitcelButtons = document.querySelectorAll(".cancel-button");
editsubmitcelButtons.forEach((editsubmitcelButton) => {
editsubmitcelButton.addEventListener("click", editsubmitclosepopup);
});
function submiteditPost(createPostId) {
    // Check if createPostId is valid
    if (createPostId) {
        console.log("createPostId:", createPostId);

        // Get the values of form elements and assign them to variables
        var postTitle = document.getElementById("title").value;
        var tag = document.getElementById("tag").value;
        var postDescription = document.getElementById("description").value;
        var image = document.getElementById("image").files[0];
    
        // Debugging: Log the values to the console
        console.log("postTitle: " + postTitle);
        console.log("tag: " + tag);
        console.log("postDescription: " + postDescription);
        console.log("image: " + image);

        // Create a FormData object to send the data, including the file
        var formData = new FormData();
        formData.append('create_post_id', createPostId);
        formData.append('post_title', postTitle);
        formData.append('tag', tag);
        formData.append('post_description', postDescription);
        formData.append('post_image', image); // Append the file to FormData

        // Perform an AJAX request to update the post in the database
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "php/submiteditedpost.php");
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    // Successfully updated post
                    setTimeout(() => {
                        openeditConfirmationPopup();
                    }, 500); 
                    console.log("Success: updating post successfully.");
                } else {
                    // Handle errors or display an error message
                    console.error("Error updating post. Server returned status code: " + xhr.status);
                }
            }
        };

        // Send the FormData object
        xhr.send(formData);
    }
}


function openeditConfirmationPopup() {
    editsubmitclosepopup();
  const confirmeditPopup = document.getElementById("edit-success-popup");
  confirmeditPopup.style.display = "block";

  const okeditButton = document.getElementById("okConfirm");
  okeditButton.addEventListener("click", function () {
    confirmeditPopup.style.display = "none";
    gobackreloadedit();
  });
  // Call the goBack function after a 5-second delay
  setTimeout(gobackreloadedit, 3000);
}

function gobackreloadedit() {
   window.location.href="manage_post.php";
}
