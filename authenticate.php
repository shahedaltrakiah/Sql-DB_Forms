<?php
session_start();
include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare and execute the query to find the user
    $sql = "SELECT * FROM users WHERE username = :username";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Debugging output
        echo "Stored password: " . $user['password'] . "<br>";
        echo "Entered password: " . $password . "<br>";

        // Verify password
        if (password_verify($password, $user['password'])) {
            // Password is correct
            $_SESSION['username'] = $user['username']; // Set session variable
            header("Location: index.php"); // Redirect to a protected page
            exit();
        } else {
            // Password is incorrect
            $_SESSION['error'] = "Invalid username or password.";
            echo $_SESSION['error']; // Debugging error output
            exit();
        }
    } else {
        // User not found
        $_SESSION['error'] = "Invalid username or password.";
        echo $_SESSION['error']; // Debugging error output
        exit();
    }
}
?>
