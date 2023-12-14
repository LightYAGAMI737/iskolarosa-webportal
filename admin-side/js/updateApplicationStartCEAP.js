// Function to update the database state
function updateDatabaseState() {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', './php/updateConfigStart.php', true);

    xhr.onload = function () {
        if (xhr.status >= 200 && xhr.status < 300) {
            // Request was successful, you can log or handle the response
            console.log(xhr.responseText);
        } else {
            // Request failed, handle the error
            console.error('Error updating database state. Status:', xhr.status, 'Response:', xhr.responseText);
        }
    };

    xhr.onerror = function () {
        // Handle network errors
        //console.error('Network error while updating database state.');
    };

    xhr.send();
}

// Call the function immediately on page load
updateDatabaseState();

// Set up a setInterval to periodically call the update function (every 5 minutes in this example)
setInterval(updateDatabaseState, 5 * 60 * 1000); 