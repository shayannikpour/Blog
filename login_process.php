<?php
session_start();
$db = new SQLite3('info.db');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Check if the user exists
    $stmt = $db->prepare("SELECT Password FROM Users WHERE Username = :username");
    $stmt->bindValue(':username', $username, SQLITE3_TEXT);
    $result = $stmt->execute();
    $row = $result->fetchArray(SQLITE3_ASSOC);

    if ($row) {
        // Verify password using password_verify()
        if (password_verify($password, $row['Password'])) {
            $_SESSION['username'] = $username;
            header("Location: home.php");
            exit();
        } else {
            echo "<script>alert('Invalid password. Please try again.'); window.location.href='login.php';</script>";
        }
    } else {
        echo "<script>alert('Username not found. Please register first.'); window.location.href='register.php';</script>";
    }
}
?>
