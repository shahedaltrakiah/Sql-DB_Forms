<?php
include('config.php');

$id = $_GET['id'];

// Fetch the user data to display in the form
try {
    $sql = "SELECT id, first_name, last_name, email FROM users WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Handle form submission for updating the user details
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];

    try {
        // Prepare an UPDATE statement
        $sql = "UPDATE users SET first_name = :first_name, last_name = :last_name, email = :email WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':first_name', $first_name, PDO::PARAM_STR);
        $stmt->bindParam(':last_name', $last_name, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!-- HTML form for editing the user -->
<form method="POST">
    <label>First Name:</label>
    <input type="text" name="first_name" value="<?php echo $user['first_name']; ?>" required><br>
    <label>Last Name:</label>
    <input type="text" name="last_name" value="<?php echo $user['last_name']; ?>" required><br>
    <label>Email:</label>
    <input type="email" name="email" value="<?php echo $user['email']; ?>" required><br>
    <input type="submit" value="Update">
</form>