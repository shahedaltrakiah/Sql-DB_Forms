<?php
include('config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'] ?? null; // Use null coalescing operator

    if ($id) {
        try {
            // Prepare a DELETE statement
            $sql = "DELETE FROM users WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            // Execute the statement
            if ($stmt->execute()) {
                echo "User deleted successfully.";
            } else {
                echo "Error deleting user.";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "No user ID provided.";
    }
} else {
    echo "Invalid request method.";
}
?>
