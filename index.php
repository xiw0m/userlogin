<?php
session_start();

// Database connection
include 'dbConfig.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

// Retrieve user information
$userId = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
  $user = $result->fetch_assoc();
  echo "Welcome, " . $user['username'] . "!";
} else {
    }
// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Prepare and bind parameters to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO customers (pet_name, pet_birthday, phone_number, created_by) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $_POST['pet_name'], $_POST['pet_birthday'], $_POST['phone_number'], $_SESSION['user_id']);
  
    if ($stmt->execute()) {
      echo "New customer added successfully!";
    } else {
      echo "Error: " . $stmt->error;
    }
  
    $stmt->close();
  }

// Retrieve customer data
$sql = "SELECT c.*, u1.username AS created_by_username, u2.username AS updated_by_username, DATE_FORMAT(c.created_at, '%Y-%m-%d %H:%i:%s') AS created_date, DATE_FORMAT(c.updated_at, '%Y-%m-%d %H:%i:%s') AS updated_date 
        FROM customers c 
        LEFT JOIN users u1 ON c.created_by = u1.user_id
        LEFT JOIN users u2 ON c.updated_by = u2.user_id";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Pet Grooming Salon</title>
    <style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap');
body {
    font-family: 'Poppins', sans-serif;
    background-color: #fffed7;
    margin: 0;
    padding: 20px;
    text-align: center;
}
form {
    background-color: #e6e6fa;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
    width: 80%;
    margin: 0 auto;
}
label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
    font-size: 18px;
    color: #333;
}
input[type="text"],
input[type="date"],
input[type="tel"],
input[type="number"] {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
}
input[type="submit"] {
    background-color: #4CAF50;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 18px;
}
table {
    width: 80%;
    margin: 20px auto;
    border-collapse: collapse;
    border: 1px solid #ccc;
    border-spacing: 0;
}
th, td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: left;
}
th {
    background-color: #f2f2f2;
    font-weight: bold;
}
a {
    text-decoration: none;
    color: blue;
}
h2 {
    color: #333;
    font-weight: bold;
    margin-bottom: 20px;
}
table th {
    background-color: #f0f0f0;
}
table tr:nth-child(even) {
    background-color: #f2f2f2;
}
    </style>
    </head>
<body>
    <h2>Add a New Pet</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="pet_name">Pet Name:</label>
        <input type="text" name="pet_name" required><br>

        <label for="pet_birthday">Pet Birthday:</label>
        <input type="date" name="pet_birthday" required><br>

        <label for="phone_number">Phone Number:</label>
        <input type="tel" name="phone_number" required><br>

        <input type="submit" value="Add Customer">
    </form>

    <h2>Pet List</h2>
  <table>
    <tr>
      <th>Customer ID</th>
      <th>Pet Name</th>
      <th>Pet Birthday</th>
      <th>Phone Number</th>
      <th>Created By</th>
      <th>Created Date</th>
      <th>Updated Date</th>
      <th>Actions</th>
    </tr>
    <?php
    while ($row = $result->fetch_assoc()) {
      echo "<tr>";
      echo "<td>" . $row["customer_id"] . "</td>";
      echo "<td>" . $row["pet_name"] . "</td>";
      echo "<td>" . $row["pet_birthday"] . "</td>";
      echo "<td>" . $row["phone_number"] . "</td>";
      echo "<td>" . $row["created_by_username"] . "</td>";
      echo "<td>" . $row["created_date"] . "</td>";
      echo "<td>" . $row["updated_date"] . "</td>";
      echo "<td><a href='update_pet.php?id=" . $row["customer_id"] . "'>Update</a> | <a href='delete_pet.php?id=" . $row["customer_id"] . "'>Delete</a>";
      echo "</tr>";
    }
    ?>
    </table>
    <a href="logout.php">Logout</a>
    <?php $conn->close(); ?>
</body>
</html>