<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 1.2em;
            background-color: #fff;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        table th,
        table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table tr:hover {
            background-color: #f1f1f1;
        }

        .edit-button,
        .delete-button,
        .add-button,
        .logout-button {
            background-color: #16C3B0;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-right: 10px;
        }

        .edit-button:hover,
        .add-button:hover {
            background-color: #13a391;
        }

        .delete-button,
        .logout-button {
            background-color: #FD5B4E;
        }

        .delete-button:hover,
        .logout-button:hover {
            background-color: #e04b3d;
        }

        .save-button {
            background-color: #FD5B4E;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            display: none;
        }

        .save-button:hover {
            background-color: #e04b3d;
        }

        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0, 0, 0);
            background-color: rgba(0, 0, 0, 0.4);
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 400px;
            border-radius: 5px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>

<body>

    <h1>User Management</h1>

    <div style="margin-left : 1019px;">
        <button class="add-button" onclick="openModal()">Add User</button>
        <button class="logout-button" onclick="window.location.href='logout.php';">Logout</button>

    </div>

    <?php
    include('config.php');

    // Fetch the user data to display in the table
    try {
        $sql = "SELECT id, first_name, last_name, email FROM users";
        $stmt = $conn->query($sql);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($result) > 0) {
            echo "<table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>";

            foreach ($result as $row) {
                echo "<tr id='row-{$row['id']}'>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td class='editable' data-id='{$row['id']}' data-field='first_name'>" . htmlspecialchars($row['first_name']) . "</td>";
                echo "<td class='editable' data-id='{$row['id']}' data-field='last_name'>" . htmlspecialchars($row['last_name']) . "</td>";
                echo "<td class='editable' data-id='{$row['id']}' data-field='email'>" . htmlspecialchars($row['email']) . "</td>";
                echo "<td>
                        <button class='edit-button' onclick='editRow(" . $row['id'] . ")'>Edit</button>
                        <button class='save-button' id='save-" . $row['id'] . "' onclick='saveRow(" . $row['id'] . ")'>Save</button>
                        <button class='delete-button' id='delete-" . $row['id'] . "' onclick='deleteUser(" . $row['id'] . ")'>Delete</button>
                      </td>";
                echo "</tr>";
            }

            echo "</tbody></table>";
        } else {
            echo "No results found.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    ?>

    <!-- Add User Modal -->
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Add User</h2>
            <label for="first_name">First Name:</label>
            <input type="text" id="first_name" required>
            <br><br>
            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name" required>
            <br><br>
            <label for="email">Email:</label>
            <input type="email" id="email" required>
            <br><br>
            <button class="add-button" onclick="addUser()">Add User</button>
        </div>
    </div>

    <script>
        // Open the modal
        function openModal() {
            document.getElementById("myModal").style.display = "block";
        }

        // Close the modal
        function closeModal() {
            document.getElementById("myModal").style.display = "none";
            clearModalFields();
        }

        // Clear modal input fields
        function clearModalFields() {
            document.getElementById("first_name").value = '';
            document.getElementById("last_name").value = '';
            document.getElementById("email").value = '';
        }

        function editRow(id) {
            let cells = document.querySelectorAll("#row-" + id + " .editable");
            cells.forEach(cell => {
                let fieldValue = cell.innerHTML;
                let fieldName = cell.getAttribute("data-field");
                cell.innerHTML = `<input type='text' value='${fieldValue}' id='${fieldName}-${id}' />`;
            });

            // Show the save button, hide the delete button
            document.querySelector(`#save-${id}`).style.display = 'inline-block';
            document.querySelector(`#delete-${id}`).style.display = 'none';
        }

        function saveRow(id) {
            let first_name = document.querySelector(`#first_name-${id}`).value;
            let last_name = document.querySelector(`#last_name-${id}`).value;
            let email = document.querySelector(`#email-${id}`).value;

            // Send an AJAX request to update the user data
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "edit.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onload = function () {
                if (xhr.status == 200) {
                    alert("User updated successfully!");
                    location.reload(); // Reload the page to update the table
                } else {
                    alert("Error updating user.");
                }
            };
            xhr.send(`id=${id}&first_name=${first_name}&last_name=${last_name}&email=${email}`);
        }

        function deleteUser(id) {
            if (confirm("Are you sure you want to delete this user?")) {
                // Send an AJAX request to delete the user
                let xhr = new XMLHttpRequest();
                xhr.open("POST", "delete.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onload = function () {
                    if (xhr.status == 200) {
                        alert("User deleted successfully!");
                        location.reload(); // Reload the page to remove the deleted row
                    } else {
                        alert("Error deleting user.");
                    }
                };
                xhr.send(`id=${id}`);
            }
        }

        function addUser() {
            let first_name = document.getElementById("first_name").value;
            let last_name = document.getElementById("last_name").value;
            let email = document.getElementById("email").value;

            // Send an AJAX request to add the user
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "add.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onload = function () {
                if (xhr.status == 200) {
                    alert("User added successfully!");
                    closeModal(); // Close the modal after adding
                    location.reload(); // Reload the page to update the table
                } else {
                    alert("Error adding user.");
                }
            };
            xhr.send(`first_name=${first_name}&last_name=${last_name}&email=${email}`);
        }

        // Close the modal when clicking outside of it
        window.onclick = function (event) {
            if (event.target == document.getElementById("myModal")) {
                closeModal();
            }
        }
    </script>
</body>

</html>