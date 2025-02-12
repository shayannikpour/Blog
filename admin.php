<?php
session_start();
$db = new SQLite3('info.db');

// Ensure the user is logged in
if (!isset($_SESSION['username'])) {
    echo "<script>alert('You must be logged in.'); window.location.href='login.php';</script>";
    exit();
}

// Fetch the latest role from the database to sync session role dynamically
$stmt = $db->prepare("SELECT Role FROM Users WHERE Username = :username");
$stmt->bindValue(':username', $_SESSION['username'], SQLITE3_TEXT);
$result = $stmt->execute();
$user = $result->fetchArray(SQLITE3_ASSOC);

if ($user) {
    $_SESSION['role'] = $user['Role']; // Refresh session role dynamically
}

// Check if the user is an admin
if ($_SESSION['role'] !== 'Admin') {
    echo "<script>alert('Access Denied! You are not an admin.'); window.location.href='home.php';</script>";
    exit();
}

// Handle Approvals
if (isset($_POST['approve'])) {
    $username = $_POST['username'];
    $stmt = $db->prepare("UPDATE Users SET IsApproved = 1 WHERE Username = :username");
    $stmt->bindValue(':username', $username, SQLITE3_TEXT);
    $stmt->execute();
}

// Handle Role Change
if (isset($_POST['change_role'])) {
    $username = $_POST['username'];
    $newRole = $_POST['role'];
    $stmt = $db->prepare("UPDATE Users SET Role = :role WHERE Username = :username");
    $stmt->bindValue(':username', $username, SQLITE3_TEXT);
    $stmt->bindValue(':role', $newRole, SQLITE3_TEXT);
    $stmt->execute();

    // If the currently logged-in user has their role changed, update session
    if ($username === $_SESSION['username']) {
        $_SESSION['role'] = $newRole;
    }
}

// Fetch All Users
$result = $db->query("SELECT Username, FirstName, LastName, Role, IsApproved FROM Users");
?>

<h2>Admin Panel - Manage Users</h2>
<table border="1">
    <tr>
        <th>Username</th>
        <th>Name</th>
        <th>Role</th>
        <th>Status</th>
        <th>Actions</th>
    </tr>

    <?php while ($row = $result->fetchArray(SQLITE3_ASSOC)) { ?>
        <tr>
            <td><?= $row['Username'] ?></td>
            <td><?= $row['FirstName'] . " " . $row['LastName'] ?></td>
            <td><?= $row['Role'] ?></td>
            <td><?= $row['IsApproved'] ? '✅ Approved' : '❌ Pending' ?></td>
            <td>
                <?php if (!$row['IsApproved']) { ?>
                    <form method="post">
                        <input type="hidden" name="username" value="<?= $row['Username'] ?>">
                        <button type="submit" name="approve">Approve</button>
                    </form>
                <?php } ?>
                
                <form method="post">
                    <input type="hidden" name="username" value="<?= $row['Username'] ?>">
                    <select name="role">
                        <option value="Contributor" <?= $row['Role'] == 'Contributor' ? 'selected' : '' ?>>Contributor</option>
                        <option value="Admin" <?= $row['Role'] == 'Admin' ? 'selected' : '' ?>>Admin</option>
                    </select>
                    <button type="submit" name="change_role">Change Role</button>
                </form>
            </td>
        </tr>
    <?php } ?>
</table>
