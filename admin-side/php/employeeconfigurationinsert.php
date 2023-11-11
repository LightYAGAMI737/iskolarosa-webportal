<?php
include 'config_iskolarosa_db.php';

// Map role names to role IDs
$roleNameToId = [
    'Staff' => 1,
    'Admin' => 2,
    'Head Admin' => 3,
];

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get the selected role name from the form
    $selectedRoleName = $_POST['role_name'];

    // Use the mapping to get the corresponding role ID
    $selectedRoleId = $roleNameToId[$selectedRoleName];

    // Get form data with proper validation and sanitization
    $employeeId = filter_input(INPUT_POST, 'employee_id_no', FILTER_SANITIZE_STRING);
    $lastName = filter_input(INPUT_POST, 'last_Name', FILTER_SANITIZE_STRING);
    $firstName = filter_input(INPUT_POST, 'first_Name', FILTER_SANITIZE_STRING);
    $contactNumber = filter_input(INPUT_POST, 'contact_Number', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $department = $selectedRoleId;
    $username = strtoupper(filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING)); // Convert to uppercase
    $password = $_POST['confirmPassword'];

    // Hash the password securely
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // File handling for the picture upload
    $targetDir = "../employee-picture/"; // Change this to the directory where you want to store the images
    $targetFile = $targetDir . $lastName . "," . $firstName . ".jpg";
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($_FILES["picture"]["name"], PATHINFO_EXTENSION));

    // Check if the file is an actual JPG image
    if (isset($_POST["submit"])) {
        if ($imageFileType != "jpg" && $imageFileType != "jpeg") {
            echo "Only JPG and JPEG images are allowed.";
            $uploadOk = 0;
        }
    }

    // Check file size (limit to 2MB)
    if ($_FILES["picture"]["size"] > 2 * 1024 * 1024) {
        echo "Sorry, your file is too large. It should be 2MB or less.";
        $uploadOk = 0;
    }

    // If $uploadOk is set to 0, there was an issue with the image upload
    if ($uploadOk == 0) {
        echo "Sorry, your image was not uploaded.";
        // You can add redirect or error handling code here
    } else {
        // If everything is fine, try to upload the file
        if (move_uploaded_file($_FILES["picture"]["tmp_name"], $targetFile)) {
            // Insert the data into the database using prepared statements
            $sql = "INSERT INTO employee_list (employee_id_no, last_Name, first_Name, contact_Number, email, role_id, username, password, picture) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param(
                "sssssssss",
                $employeeId,
                $lastName,
                $firstName,
                $contactNumber,
                $email,
                $department,
                $username,
                $hashedPassword, // Hashed password instead of plain text
                $targetFile
            );

            if ($stmt->execute()) {
                // Data inserted successfully
                echo "Data inserted successfully!";
                // Redirect to the desired page after successful insertion
                exit; // Terminate the script after redirection
            } else {
                // Error occurred while inserting data
                if ($conn->errno === 1062) {
                    echo "Error: Duplicate entry found. Please check your data.";
                } else {
                    echo "Error: " . $conn->error;
                }
            }

            // Close the statement
            $stmt->close();
        } else {
            // File upload failed
            echo "Sorry, there was an error uploading your file.";
            // You can add redirect or error handling code here
        }

        // Close the connection
        $conn->close();
    }
}
?>
