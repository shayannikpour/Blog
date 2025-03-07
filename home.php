<?php
session_start();
$db = new SQLite3('info.db');

// Set the default timezone to Pacific Standard Time
date_default_timezone_set('America/Los_Angeles');

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

// Get the current date
$current_date = date('Y-m-d');

// Fetch articles that are within the visibility window
$query = "SELECT Articles.*, Users.FirstName, Users.LastName 
          FROM Articles 
          JOIN Users ON Articles.ContributerUsername = Users.Username 
          WHERE StartDate <= '$current_date' AND EndDate >= '$current_date' 
          ORDER BY CreateDate DESC";

$results = $db->query($query);
?>

<?php include('./inc/inc_header.php'); ?>

<!-- Blurred background -->
<div class="background-blur"></div>

<!-- Admin Button (Moved to top of page) -->
<?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'Admin') { ?>
    <div class="container mt-4">
        <div class="text-center">
            <a href="admin.php" class="btn btn-danger btn-lg">Admin Panel</a>
        </div>
    </div>
<?php } ?>

<!-- Write Article Button -->
<div class="container mt-4">
    <div class="text-center">
        <a href="write.php" class="btn btn-primary btn-lg">Click to manage your articles</a>
    </div>
</div>

<div class="container mt-4">
    <h2 class="text-center mb-4">Latest Articles</h2>

    <?php while ($row = $results->fetchArray(SQLITE3_ASSOC)) { ?>
        <div class="card mb-3 p-3 shadow-sm">
            <!-- Article Title -->
            <h4 class="text-primary"><?= htmlspecialchars($row['ArticleTitle']) ?></h4>

            <!-- Author & Date -->
            <p class="text-muted">
                <?= htmlspecialchars($row['FirstName'] . ' ' . $row['LastName']) ?>, <?= date("F j, Y", strtotime($row['CreateDate'])) ?>
            </p>


            <!-- Article Preview -->
            <p>
                <span id="preview_<?= $row['ArticleId'] ?>">
                    <?= substr($row['ArticleBody'], 0, 100) ?>...
                </span>
                <span id="full_<?= $row['ArticleId'] ?>" style="display: none;">
                    <?= nl2br($row['ArticleBody']) ?>
                </span>
                <a href="javascript:void(0);" class="text-primary" onclick="toggleArticle(<?= $row['ArticleId'] ?>)">
                    <span id="toggle_<?= $row['ArticleId'] ?>">more...</span>
                </a>
            </p>
        </div>
    <?php } ?>
</div>

<!-- JavaScript for Expand/Collapse -->
<script>
    function toggleArticle(id) {
        let preview = document.getElementById("preview_" + id);
        let fullText = document.getElementById("full_" + id);
        let toggleLink = document.getElementById("toggle_" + id);

        if (fullText.style.display === "none") {
            preview.style.display = "none";
            fullText.style.display = "inline";
            toggleLink.innerText = " less";
        } else {
            preview.style.display = "inline";
            fullText.style.display = "none";
            toggleLink.innerText = "more...";
        }
    }
</script>

<?php include('./inc/inc_footer.php'); ?>