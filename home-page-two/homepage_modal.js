    document.addEventListener('DOMContentLoaded', function() {
        const HomePageModalCEAP = document.getElementById("HomePageModalCEAP");
        const HomePageModalLPPP = document.getElementById("HomePageModalLPPP");

        function openHomePageModalCEAP() {
            HomePageModalCEAP.style.display = "block";
        }

         function openHomePageModalLPPP() {
            HomePageModalLPPP.style.display = "block";
        }

            function closeHomePageModalCEAP() {
            HomePageModalCEAP.style.display = "none";
            }
        function closeHomePageModalLPPP() {
            HomePageModalLPPP.style.display = "none";
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
        //     closeHomePageModalCEAP();
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
        const openHomePageModalCEAPButton = document.querySelector('.ceap');
        const openHomePageModalLPPPButton = document.querySelector('.lppp');
        const closeHomePageModalCEAPButton = document.querySelector('.ceap-btn-close');
        const closeHomePageModalLPPPButton = document.querySelector('.lppp-btn-close');
        openHomePageModalCEAPButton.addEventListener('click', openHomePageModalCEAP);
        openHomePageModalLPPPButton.addEventListener('click', openHomePageModalLPPP);
        closeHomePageModalCEAPButton.addEventListener('click', closeHomePageModalCEAP);
        closeHomePageModalLPPPButton.addEventListener('click', closeHomePageModalLPPP);
    });

