$(document).ready(function () {
    var emailInput = $("#floatingInput");
    var emailCheckMsg = $(".emailCheckMsg");
    var submitBtn = $("#submitForgotPassword");

    emailInput.on("blur", function () {
        var email = emailInput.val();

        if (email.trim() === "") {
            // Clear the error message and disable the Submit button if the email input is empty
            emailCheckMsg.text("");
            submitBtn.prop("disabled", true);
        } else {
            $.ajax({
                type: "POST",
                url: "php/checkemailForgotpassword.php",
                data: { email: email },
                success: function (response) {
                    if (response === "exists") {
                        emailCheckMsg.text("");
                        submitBtn.prop("disabled", false);
                    } else if (response === "not_exists") {
                        emailCheckMsg.text("Email doesn't exist.");
                        submitBtn.prop("disabled", true);
                    } else {
                        emailCheckMsg.text("Error: " + response);
                        submitBtn.prop("disabled", true);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log("AJAX Error:", textStatus, errorThrown);
                    submitBtn.prop("disabled", true);
                }
            });
        }
    });

    emailInput.on("focus", function () {
        emailCheckMsg.text("");
    });

    var cancelForgotBtn = document.getElementById("cancelForgotPassword");
    cancelForgotBtn.addEventListener("click", function () {
        window.location.href = "admin_index.php";
    });
});
