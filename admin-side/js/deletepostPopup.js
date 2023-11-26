const deletepostpopup = document.getElementById('DeletepostPopUp');

    function opendeletepostpopup() {
        deletepostpopup.style.display = "block";
    }
    function closedeletepostpopup() {
        deletepostpopup.style.display = "none";
    }

    function deletePostSubmit() {
        var xhr = new XMLHttpRequest();
        var formData = new FormData(document.getElementById('post-form'));
    
        xhr.open('POST', './php/delete_post.php', true);
        xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    // Request was successful
                    var response = xhr.responseText;
                    console.log('Response:', response);
                    setTimeout(() => {
                    openDeleteConfirmationPopup();
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

    function openDeleteConfirmationPopup() {
        closedeletepostpopup();
      const confirmPopup = document.getElementById("ConfrimMsgDeletePopUp");
      confirmPopup.style.display = "block";
    
      const okButton = document.getElementById("okConfirm");
      okButton.addEventListener("click", function () {
        confirmPopup.style.display = "none";
        goBacks();
      });
      // Call the goBack function after a 5-second delay
      setTimeout(goBacks, 3000);
    }

    function goBacks() {
        location.reload();
    }
    
    