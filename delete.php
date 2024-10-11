<?php
include('config.php');


$id = $_GET['id'];

try {
    // Prepare a DELETE statement
    $sql = "DELETE FROM users WHERE id = :id";
    $stmt = $conn->prepare($sql);
    // Bind the `id` parameter to the SQL query
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}



?>