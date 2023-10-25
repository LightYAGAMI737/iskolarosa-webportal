function editPost(createPostId) {
    console.log("editPost function called."); // Log a message when the editPost function is called.

    // Add an AJAX request to fetch the post data
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "./php/fetch_editpost.php?create_post_id=" + createPostId, true);

    xhr.onload = function() {
        console.log("AJAX request completed with status code:", xhr.status); // Log the status code after the AJAX request completes.

        if (xhr.status === 200) {
            var post = JSON.parse(xhr.responseText);
            console.log("Received post data:", post); // Log the received post data for debugging

            // Check if the DOM element with ID "postTitle" exists before setting its value
            var postTitleElement = document.getElementById("postTitle");
            if (postTitleElement) {
                postTitleElement.value = post.post_title || "";
            }

            // Check if the DOM element with ID "postDescription" exists before setting its value
            var postDescriptionElement = document.getElementById("postDescription");
            if (postDescriptionElement) {
                postDescriptionElement.value = post.post_description || "";
            }

            // Check if the DOM element with ID "tag" exists before setting its value
            var tagElement = document.getElementById("tag");
            if (tagElement) {
                tagElement.value = post.tag || "";
            }

            // Set the image path accordingly
            var imagePathElement = document.getElementById("imagePath");
            if (imagePathElement) {
                if (post.post_image_path) {
                    imagePathElement.innerHTML = `Current Image: <a href="${post.post_image_path}" target="_blank">View Image</a>`;
                } else {
                    imagePathElement.innerHTML = "No image available";
                }
            }

            // Add the createPostId to a hidden input field
            var editPostIdElement = document.getElementById("editPostId");
            if (editPostIdElement) {
                editPostIdElement.value = createPostId;
            }

            console.log("Form fields populated."); // Log a message indicating that the form fields are populated

            // Redirect to edit_post.php
            window.location.href = 'edit_post.php'; // Change the page's location to edit_post.php

        } else {
            console.log("AJAX request failed with status code:", xhr.status); // Log a message if the AJAX request fails.
        }
    };

    xhr.send();

    console.log("AJAX request sent."); // Log a message when the AJAX request is sent.
}
