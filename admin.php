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

// Handle Approvals (Toggle IsApproved)
if (isset($_POST['toggle_approval'])) {
    $username = $_POST['username'];
    $currentStatus = $_POST['current_status']; // Get current status from form input
    $newStatus = $currentStatus == 1 ? 0 : 1; // Toggle between 1 (Approved) and 0 (Pending)
    
    $stmt = $db->prepare("UPDATE Users SET IsApproved = :newStatus WHERE Username = :username");
    $stmt->bindValue(':username', $username, SQLITE3_TEXT);
    $stmt->bindValue(':newStatus', $newStatus, SQLITE3_INTEGER);
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

// Handle User Deletion
if (isset($_POST['delete_user'])) {
    $username = $_POST['username'];

    // Prevent admin from deleting themselves
    if ($username === $_SESSION['username']) {
        echo "<script>alert('You cannot delete your own account!');</script>";
    } else {
        $stmt = $db->prepare("DELETE FROM Users WHERE Username = :username");
        $stmt->bindValue(':username', $username, SQLITE3_TEXT);
        $stmt->execute();
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
                <!-- Toggle Approval Button -->
                <form method="post">
                    <input type="hidden" name="username" value="<?= $row['Username'] ?>">
                    <input type="hidden" name="current_status" value="<?= $row['IsApproved'] ?>">
                    <button type="submit" name="toggle_approval">
                        <?= $row['IsApproved'] ? 'Revoke Approval' : 'Approve' ?>
                    </button>
                </form>

                <!-- Role Change Form -->
                <form method="post">
                    <input type="hidden" name="username" value="<?= $row['Username'] ?>">
                    <select name="role">
                        <option value="Contributor" <?= $row['Role'] == 'Contributor' ? 'selected' : '' ?>>Contributor</option>
                        <option value="Admin" <?= $row['Role'] == 'Admin' ? 'selected' : '' ?>>Admin</option>
                    </select>
                    <button type="submit" name="change_role">Change Role</button>
                </form>

                <!-- Delete User Button -->
                <form method="post" onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone!');">
                    <input type="hidden" name="username" value="<?= $row['Username'] ?>">
                    <button type="submit" name="delete_user" style="background-color: red; color: white;">Delete User</button>
                </form>
            </td>
        </tr>
    <?php } ?>
</table>
