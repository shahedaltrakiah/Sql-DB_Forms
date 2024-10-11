<?php
// Include the database configuration file
include('config.php');

// Function to validate email
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Initialize an empty array to hold error messages
$errors = [];

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize input data
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);

    // Validate input data
    if (empty($first_name)) {
        $errors[] = "First name is required.";
    }
    if (empty($last_name)) {
        $errors[] = "Last name is required.";
    }
    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!isValidEmail($email)) {
        $errors[] = "Invalid email format.";
    }

    // If there are no errors, proceed to insert the user into the database
    if (empty($errors)) {
        try {
            // Prepare an SQL statement to insert user data
            $sql = "INSERT INTO users (first_name, last_name, email) VALUES (:first_name, :last_name, :email)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':first_name', $first_name);
            $stmt->bindParam(':last_name', $last_name);
            $stmt->bindParam(':email', $email);

            // Execute the statement
            if ($stmt->execute()) {
                // Return a success response
                echo json_encode(['status' => 'success', 'message' => 'User added successfully!']);
            } else {
                // Return an error response for database execution failure
                echo json_encode(['status' => 'error', 'message' => 'Failed to add user.']);
            }
        } catch (PDOException $e) {
            // Handle database connection errors
            echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
        }
    } else {
        // Return an error response with validation errors
        echo json_encode(['status' => 'error', 'message' => implode(' ', $errors)]);
    }
} else {
    // Return an error response if the request method is not POST
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>
