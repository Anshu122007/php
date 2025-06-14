<?php include 'includes/header.php'; ?>
<h2>All Students</h2>
<table>
    <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Grade</th>
        <th>Date of Birth</th>
    </tr>
    <?php
    include 'db_connect.php';
    $result = $conn->query("SELECT * FROM students ORDER BY name");
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . htmlspecialchars($row['name']) . "</td>
                    <td>" . htmlspecialchars($row['email']) . "</td>
                    <td>" . htmlspecialchars($row['grade']) . "</td>
                    <td>" . htmlspecialchars($row['dob']) . "</td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='4'>No students found.</td></tr>";
    }
    ?>
</table>
<?php include 'includes/footer.php'; ?>

