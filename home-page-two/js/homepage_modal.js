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

        const applynoNextwButtons = document.querySelectorAll('.confirmBTN');
        applynoNextwButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                this.classList.add('disabled');
                const ApplyNoWcancelButton = this.parentElement.querySelector('.cancel-button');
                ApplyNoWcancelButton.classList.add('disabled');
            });
        });


        const ApplyNoWcancelButtons = document.querySelectorAll(".cancel-button");
        ApplyNoWcancelButtons.forEach((ApplyNoWcancelButton) => {
          ApplyNoWcancelButton.addEventListener("click", closeCEAPApplyNowBtn);
        });

        const okButtonCEAP = document.getElementById("confirm-button-CEAP");
        okButtonCEAP.addEventListener("click", function () {
            // Redirect to another page
            closeLPPPApplyNowBtn();
            window.location.href = "../ceap-reg-form/ceap-reg-form.php";
        });

        const ApplyNoWLPPPcancelButtons = document.querySelectorAll(".cancel-button");
        ApplyNoWcancelButtons.forEach((ApplyNoWLPPPcancelButton) => {
          ApplyNoWLPPPcancelButton.addEventListener("click", closeLPPPApplyNowBtn);
        });
        
        const okButtonLPPP = document.getElementById("confirm-button-LPPP");
        okButtonLPPP.addEventListener("click", function () {
            // Redirect to another page
            closeLPPPApplyNowBtn();
            window.location.href = "../lppp-reg-form/lppp-reg-form.php";
        });

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

const applynowNextButton = document.getElementById('applynowNextButton');
const applynowLPPPNextButton = document.getElementById('applynowLPPPNextButton');

function openCEAPApplyNowBtn(){
    applynowNextButton.style.display = "block";
}
function closeCEAPApplyNowBtn(){
    applynowNextButton.style.display = "none";
    location.reload();
}
function openLPPPApplyNowBtn(){
    applynowLPPPNextButton.style.display = "block";
}
function closeLPPPApplyNowBtn(){
    applynowLPPPNextButton.style.display = "none";
    location.reload();
}

//ceap cooldown time
document.addEventListener("DOMContentLoaded", function () {
    var confirmButton = document.getElementById('confirm-button-CEAP');
    var cooldownSeconds = 10;
    var countdownInterval;

    // Function to update the button text and handle cooldown
    function startCooldown() {
        // Disable the confirm button initially
        confirmButton.disabled = true;

        // Update the button text to show the countdown
        function updateCountdown() {
            confirmButton.innerHTML = `<i class="ri-check-fill"></i> <span>OK (${cooldownSeconds})</span>`;
        }

        // Enable the confirm button after 5 seconds
        setTimeout(function () {
            confirmButton.disabled = false;
            confirmButton.classList.remove("cooldown");
            confirmButton.innerHTML = `<i class="ri-check-fill"></i> <span>OK</span>`; // Reset the button text
            clearInterval(countdownInterval); // Clear the interval once the cooldown is over
        }, cooldownSeconds * 1000);

        // Add cooldown styling during the 5 seconds
        confirmButton.classList.add("cooldown");

        // Set up the countdown timer
        countdownInterval = setInterval(function () {
            cooldownSeconds--;
            if (cooldownSeconds >= 0) {
                updateCountdown();
            }
        }, 1000);

        // Initial update
        updateCountdown();
    }
    
    const HomeModalCEAP = document.getElementById("HomePageModalCEAP");
        function closeHomePageModalCEAP() {
            HomeModalCEAP.style.display = "none";
            }
    // Event listener for the applyNowButtonCEAP click event
    applyNowButtonCEAP.addEventListener('click', function () {
        closeHomePageModalCEAP();
        openCEAPApplyNowBtn();
        startCooldown(); // Start the cooldown after clicking the apply now button
    });
});


//lppp cooldown time
document.addEventListener("DOMContentLoaded", function () {
    var confirmButton = document.getElementById('confirm-button-LPPP');
    var cooldownSeconds = 10;
    var countdownInterval;

    // Function to update the button text and handle cooldown
    function startCooldown() {
        // Disable the confirm button initially
        confirmButton.disabled = true;

        // Update the button text to show the countdown
        function updateCountdown() {
            confirmButton.innerHTML = `<i class="ri-check-fill"></i> <span>OK (${cooldownSeconds})</span>`;
        }

        // Enable the confirm button after 5 seconds
        setTimeout(function () {
            confirmButton.disabled = false;
            confirmButton.classList.remove("cooldown");
            confirmButton.innerHTML = `<i class="ri-check-fill"></i> <span>OK</span>`; // Reset the button text
            clearInterval(countdownInterval); // Clear the interval once the cooldown is over
        }, cooldownSeconds * 1000);

        // Add cooldown styling during the 5 seconds
        confirmButton.classList.add("cooldown");

        // Set up the countdown timer
        countdownInterval = setInterval(function () {
            cooldownSeconds--;
            if (cooldownSeconds >= 0) {
                updateCountdown();
            }
        }, 1000);

        // Initial update
        updateCountdown();
    }

    const HomeModalLPPP = document.getElementById("HomePageModalLPPP");
        function closeHomePageModalLPPP() {
            HomeModalLPPP.style.display = "none";
            }
    // Event listener for the applyNowButtonLPPP click event
    applyNowButtonLPPP.addEventListener('click', function () {
        closeHomePageModalLPPP();
        openLPPPApplyNowBtn();
        startCooldown(); // Start the cooldown after clicking the apply now button
    });
});
