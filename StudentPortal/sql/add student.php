<?php
$host = "localhost";
$user = "root";
$password = ""; // Replace with your MySQL password if needed
$dbname = "student_portal";

// Create connection
$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM students";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Students</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<header>
    <h1>Student Portal</h1>
</header>
<main>
    <h2>Student Records</h2>
    <?php if ($result->num_rows > 0): ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Grade</th>
                <th>Date of Birth</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= htmlspecialchars($row['grade']) ?></td>
                    <td><?= htmlspecialchars($row['dob']) ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p class="error">No student records found.</p>
    <?php endif; ?>
</main>
<footer>
    <p>&copy; <?= date("Y") ?> Student Portal</p>
</footer>
</body>
</html>

<?php $conn->close(); ?>
