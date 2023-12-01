$(document).ready(function() {
        $('#guardian_firstname, #guardian_lastname').on('input', function() {
            // Get the user input for guardian's first name and last name
            var guardian_firstname = $('#guardian_firstname').val();
            var guardian_lastname = $('#guardian_lastname').val();

            // Get the error message span element
            var errorSpan = $('#guardian_fullname_duplicate_error');

            // Get the input elements
            var firstnameInput = $('#guardian_firstname');
            var lastnameInput = $('#guardian_lastname');

            // Make an AJAX request to your PHP script for duplication check
            $.ajax({
                url: 'php/check_duplicate.php', // Update the URL to your PHP script
                method: 'POST',
                data: {
                    guardian_firstname: guardian_firstname,
                    guardian_lastname: guardian_lastname
                },
                success: function(response) {
                    if (response === 'duplicate') {
                        // Display the error message in the designated span element
                        errorSpan.text('One Applicant per Family only.');

                        // Add the 'invalid' class to the input elements
                        firstnameInput.addClass('invalid');
                        lastnameInput.addClass('invalid');
                    } else {
                        // Clear the error message if no duplication is found
                        errorSpan.text('');

                        // Remove the 'invalid' class from the input elements
                        firstnameInput.removeClass('invalid');
                        lastnameInput.removeClass('invalid');
                    }
                }
            });
        });
    });

    $(document).ready(function() {
        $('#active_email_address').on('input', function() {
            // Get the user input for guardian's first name and last name
            var active_email_address = $('#active_email_address').val();

            // Get the error message span element
            var errorSpan = $('#active_email_address_error');

            // Get the input elements
            var firstnameInput = $('#active_email_address');

            // Make an AJAX request to your PHP script for duplication check
            $.ajax({
                url: 'php/check_duplicate_email.php', // Update the URL to your PHP script
                method: 'POST',
                data: {
                    active_email_address: active_email_address,
                },
                success: function(response) {
                    if (response === 'duplicate') {
                        // Display the error message in the designated span element
                        errorSpan.text('email already exist.');

                        // Add the 'invalid' class to the input elements
                        firstnameInput.addClass('invalid');
                        lastnameInput.addClass('invalid');
                    } else {
                        // Clear the error message if no duplication is found
                        errorSpan.text('');

                        // Remove the 'invalid' class from the input elements
                        firstnameInput.removeClass('invalid');
                        lastnameInput.removeClass('invalid');
                    }
                }
            });
        });
    });