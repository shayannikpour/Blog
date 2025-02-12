<?php
$db = new SQLite3('info.db');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirmpassword = trim($_POST['confirmpassword']);

    // Validate email format
    if (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format!'); window.location.href='register.php';</script>";
        exit();
    }

    // Validate password strength
    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password)) {
        echo "<script>alert('Password must be at least 8 characters long, including uppercase, lowercase, number, and special character.'); window.location.href='register.php';</script>";
        exit();
    }

    // Check if passwords match
    if ($password !== $confirmpassword) {
        echo "<script>alert('Passwords do not match!'); window.location.href='register.php';</script>";
        exit();
    }

    // Check if username already exists
    $stmt = $db->prepare("SELECT COUNT(*) as count FROM Users WHERE Username = :username");
    $stmt->bindValue(':username', $username, SQLITE3_TEXT);
    $result = $stmt->execute();
    $row = $result->fetchArray(SQLITE3_ASSOC);

    if ($row['count'] > 0) {
        echo "<script>alert('Username already exists. Please choose a different one.'); window.location.href='register.php';</script>";
        exit();
    }

    // Hash the password before storing it
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Insert user into database with default values
    $stmt = $db->prepare("INSERT INTO Users (FirstName, LastName, Username, Password, IsApproved, Role) 
                          VALUES (:firstname, :lastname, :username, :password, 0, 'Contributor')");
    $stmt->bindValue(':firstname', $firstname, SQLITE3_TEXT);
    $stmt->bindValue(':lastname', $lastname, SQLITE3_TEXT);
    $stmt->bindValue(':username', $username, SQLITE3_TEXT);
    $stmt->bindValue(':password', $hashedPassword, SQLITE3_TEXT);

    if ($stmt->execute()) {
        echo "<script>alert('Registration successful! Your account is pending approval.'); window.location.href='login.php';</script>";
    } else {
        echo "<script>alert('Error occurred during registration. Please try again.'); window.location.href='register.php';</script>";
    }
}
?>
