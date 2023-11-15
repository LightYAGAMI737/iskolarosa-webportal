    document.addEventListener('DOMContentLoaded', function() {
        const HomePageModal = document.getElementById("HomePageModal");

        function openHomePageModal() {
            HomePageModal.style.display = "block";
        }

        function closeHomePageModal() {
                HomePageModal.style.display = "none";
        }

        const StatusapplynowButtons = document.querySelectorAll('.applynow-button');
        StatusapplynowButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                this.classList.add('disabled');
                const cancelButton = this.parentElement.querySelector('.cancel-button');
                cancelButton.classList.add('disabled');
            });
        });

        // function openapplynowationPopup() {
        //     closeHomePageModal();
        //     const applynowPopup = document.getElementById("ConfrimMsgPopUp");
        //     applynowPopup.style.display = "block";

        //     const okButton = document.getElementById("okapplynow");
        //     okButton.addEventListener("click", function () {
        //         applynowPopup.style.display = "none";
        //         goBack();
        //     });
        //     // Call the goBack function after a 5-second delay
        //     setTimeout(goBack, 5000);
        // }

        // Event listener for the button
        const openHomePageModalButton = document.querySelector('.btn');
        const closeHomePageModalButton = document.querySelector('.close-button');
        openHomePageModalButton.addEventListener('click', openHomePageModal);
        closeHomePageModalButton.addEventListener('click', closeHomePageModal);
    });
