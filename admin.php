<?php include('./inc/inc_header.php'); ?>
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

// Handle One-Time Approval
if (isset($_POST['approve_user'])) {
    $username = $_POST['username'];
    
    $stmt = $db->prepare("UPDATE Users SET IsApproved = 1 WHERE Username = :username AND IsApproved = 0");
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

<div class="container mt-5">
    <div class="card shadow-lg p-4">
        <h2 class="text-center mb-4 text-primary">Admin Panel - Manage Users</h2>

        <div class="table-responsive">
            <table class="table table-hover table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Username</th>
                        <th>Name</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Approve button</th>
                        <th>Actions</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetchArray(SQLITE3_ASSOC)) { ?>
                        <tr>
                            <td><?= $row['Username'] ?></td>
                            <td><?= $row['FirstName'] . " " . $row['LastName'] ?></td>
                            <td><?= $row['Role'] ?></td>
                            <td>
                                <span class="badge <?= $row['IsApproved'] ? 'bg-success' : 'bg-danger' ?>">
                                    <?= $row['IsApproved'] ? 'Approved' : 'Pending' ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($row['IsApproved'] == 0) { ?>
                                    <!-- Approve Button (One-Time Only) -->
                                    <form method="post">
                                        <input type="hidden" name="username" value="<?= $row['Username'] ?>">
                                        <button type="submit" name="approve_user" class="btn btn-success btn-sm">Approve</button>
                                    </form>
                                <?php } ?>
                            </td>
                            <td>
                                <!-- Role Change Form -->
                                <form method="post">
                                    <input type="hidden" name="username" value="<?= $row['Username'] ?>">
                                    <select name="role" class="form-select form-select-sm">
                                        <option value="Contributor" <?= $row['Role'] == 'Contributor' ? 'selected' : '' ?>>Contributor</option>
                                        <option value="Admin" <?= $row['Role'] == 'Admin' ? 'selected' : '' ?>>Admin</option>
                                    </select>
                                    <button type="submit" name="change_role" class="btn btn-info btn-sm">Change</button>
                                </form>
                            </td>
                            <td>
                                <!-- Delete User Button (Separate Column) -->
                                <form method="post" onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone!');">
                                    <input type="hidden" name="username" value="<?= $row['Username'] ?>">
                                    <button type="submit" name="delete_user" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include('./inc/inc_footer.php'); ?>
