<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Portal</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <nav>
        <img src="images/logo.png" alt="Logo" height="40">
        <a href="add_student.php">Add Student</a> |
        <a href="view_students.php">View Students</a>
    </nav>
</header>
<main>
    <h1>Welcome to the Student Portal!</h1>
    <p>Use the navigation above to add new student records or view existing ones.</p>
</main>
<footer>
    <p>&copy; <?php echo date('Y'); ?> Student Portal</p>
</footer>
</body>
</html>