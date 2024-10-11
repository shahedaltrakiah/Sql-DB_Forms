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

        .edit-button, .delete-button {
            background-color: #16C3B0;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .edit-button:hover {
            background-color: #13a391;
        }

        .delete-button {
            background-color: #FD5B4E;
        }

        .delete-button:hover {
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
    </style>
</head>

<body>

    <h1>User Management</h1>

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
                echo "<td class='editable' data-id='{$row['id']}' data-field='first_name'>" . $row['first_name'] . "</td>";
                echo "<td class='editable' data-id='{$row['id']}' data-field='last_name'>" . $row['last_name'] . "</td>";
                echo "<td class='editable' data-id='{$row['id']}' data-field='email'>" . $row['email'] . "</td>";
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

    <script>
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
            xhr.onload = function() {
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
                xhr.onload = function() {
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
    </script>

</body>

</html>
