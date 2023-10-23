$(document).ready(function () {
    // Select the email input field
    var emailInput = $("#floatingInput");

    // Select the emailCheckMsg span
    var emailCheckMsg = $(".emailCheckMsg");

    // Select the Submit button
    var submitBtn = $("#submitForgotPassword");

    // Attach a "blur" event to the email input
    emailInput.on("blur", function () {
        // Get the email value from the input
        var email = emailInput.val();

        // Send an AJAX request to your PHP file
        $.ajax({
            type: "POST",
            url: "php/checkemailForgotpassword.php",
            data: { email: email }, // Send the email as data
            success: function (response) {
                // Handle the response from the PHP file
                if (response === "exists") {
                    emailCheckMsg.text("");
                    // Enable the Submit button
                    submitBtn.prop("disabled", false);
                } else if (response === "not_exists") {
                    emailCheckMsg.text("Email doesn't exist in the database.");
                    // Disable the Submit button
                    submitBtn.prop("disabled", true);
                } else {
                    emailCheckMsg.text("Error: " + response);
                    // Disable the Submit button in case of an error
                    submitBtn.prop("disabled", true);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                // Log the error details to the console
                console.log("AJAX Error:", textStatus, errorThrown);
                // Disable the Submit button in case of an error
                submitBtn.prop("disabled", true);
            }
        });
    });
});

var cancelBtn = document.getElementById("cancelForgotPassword");
cancelBtn.addEventListener("click", function () {
    window.location.href = "admin_index.php";
});
