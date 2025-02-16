<?php
session_start();
$db = new SQLite3('info.db');

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Retrieve user information
    $stmt = $db->prepare("SELECT Password, Role, IsApproved FROM Users WHERE Username = :username");
    $stmt->bindValue(':username', $username, SQLITE3_TEXT);
    $result = $stmt->execute();
    $row = $result->fetchArray(SQLITE3_ASSOC);

    if ($row) 
    {
        // Verify password
        if (password_verify($password, $row['Password'])) 
        {
            // Check if the account is approved
            if ($row['IsApproved'] == 0) 
            {
                echo "<script>alert('Your account is not approved yet. Please wait for admin approval.'); window.location.href='login.php';</script>";
                exit();
            }

            // Store username and role in session
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $row['Role'];

            // Always redirect to home.php regardless of role
            header("Location: home.php");
            exit();
        } 
        else 
        {
            echo "<script>alert('Invalid password. Please try again.'); window.location.href='login.php';</script>";
        }
    } 
    else 
    {
        echo "<script>alert('Username not found. Please register first.'); window.location.href='register.php';</script>";
    }
}
?>