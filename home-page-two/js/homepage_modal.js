    document.addEventListener('DOMContentLoaded', function() {
        const HomePageModalCEAP = document.getElementById("HomePageModalCEAP");
        const HomePageModalLPPP = document.getElementById("HomePageModalLPPP");

        function openHomePageModalCEAP() {
            HomePageModalCEAP.style.display = "block";
        
            // Add event listener to prevent body scrolling
            document.body.addEventListener('click', () => {
                if (HomePageModalCEAP.style.display === 'block') {
                    document.body.style.overflowY = 'hidden';
                } else {
                    // Remove the styling when the modal is closed
                    document.body.style.overflowY = '';
                }
            });
        }
        function openHomePageModalLPPP() {
            HomePageModalLPPP.style.display = "block";
        
            // Add event listener to prevent body scrolling
            document.body.addEventListener('click', () => {
                if (HomePageModalLPPP.style.display === 'block') {
                    document.body.style.overflowY = 'hidden';
                } else {
                    // Remove the styling when the modal is closed
                    document.body.style.overflowY = '';
                }
            });
        }

            function closeHomePageModalCEAP() {
            HomePageModalCEAP.style.display = "none";
            }
        function closeHomePageModalLPPP() {
            HomePageModalLPPP.style.display = "none";
            }

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
}
function openLPPPApplyNowBtn(){
    applynowLPPPNextButton.style.display = "block";
}
function closeLPPPApplyNowBtn(){
    applynowLPPPNextButton.style.display = "none";
}

//ceap cooldown timer
document.addEventListener("DOMContentLoaded", function () {
    var confirmButton = document.getElementById('confirm-button-CEAP');
    var cooldownSeconds = 10;
    var countdownInterval;
    var startTime;

    function startCooldown() {
        var remainingTime;

        confirmButton.disabled = true;

        function updateCountdown() {
            remainingTime = Math.max(0, cooldownSeconds - Math.floor((Date.now() - startTime) / 1000));
            confirmButton.innerHTML = `<i class="ri-check-fill"></i> <span>OK (${remainingTime})</span>`;
        }

        function enableButton() {
            confirmButton.disabled = false;
            confirmButton.classList.remove("cooldown");
            confirmButton.innerHTML = `<i class="ri-check-fill"></i> <span>OK</span>`;
            clearInterval(countdownInterval);
        }

        // Calculate remaining time once
        updateCountdown();

        // Check if cooldown has already ended
        if (remainingTime <= 0) {
            enableButton();
        } else {
            // Start the countdown interval
            countdownInterval = setInterval(function () {
                updateCountdown();
                if (remainingTime <= 0) {
                    enableButton();
                }
            }, 1000);
        }
    }

    const HomeModalCEAP = document.getElementById("HomePageModalCEAP");

    function closeHomePageModalCEAP() {
        HomeModalCEAP.style.display = "none";
    }

    applyNowButtonCEAP.addEventListener('click', function () {
        closeHomePageModalCEAP();
        openCEAPApplyNowBtn();
        startTime = Date.now(); // Record the start time
        startCooldown();
    });
});

//lppp cooldown timer
document.addEventListener("DOMContentLoaded", function () {
    var confirmButton = document.getElementById('confirm-button-LPPP');
    var cooldownSeconds = 10;
    var countdownInterval;
    var startTime;
    var remainingTime;  // Declare remainingTime here

    function startCooldown() {
        confirmButton.disabled = true;

        function updateCountdown() {
            remainingTime = Math.max(0, cooldownSeconds - Math.floor((Date.now() - startTime) / 1000));
            confirmButton.innerHTML = `<i class="ri-check-fill"></i> <span>OK (${remainingTime})</span>`;
        }

        function enableButton() {
            confirmButton.disabled = false;
            confirmButton.classList.remove("cooldown");
            confirmButton.innerHTML = `<i class="ri-check-fill"></i> <span>OK</span>`;
            clearInterval(countdownInterval);
        }

        // Calculate remaining time once
        updateCountdown();

        // Check if cooldown has already ended
        if (remainingTime <= 0) {
            enableButton();
        } else {
            // Start the countdown interval
            countdownInterval = setInterval(function () {
                updateCountdown();
                if (remainingTime <= 0) {
                    enableButton();
                }
            }, 1000);
        }
    }

    const HomeModalLPPP = document.getElementById("HomePageModalLPPP");

    function closeHomePageModalLPPP() {
        HomeModalLPPP.style.display = "none";
    }

    applyNowButtonLPPP.addEventListener('click', function () {
        closeHomePageModalLPPP();
        openLPPPApplyNowBtn();
        startTime = Date.now(); // Record the start time
        startCooldown();
    });
});
