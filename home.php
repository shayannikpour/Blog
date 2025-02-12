<?php include('./inc/inc_header.php'); ?>

<?php

$id_count = 1;

session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

$db = new SQLite3('info.db');

// Fetch articles from the database
$query = "SELECT * FROM Articles ORDER BY CreateDate DESC";
$results = $db->query($query);

// SQL statement to drop the Articles table
// $sql = "DROP TABLE IF EXISTS Articles";

// if ($db->exec($sql)) {
//     echo "Table 'Articles' deleted successfully.";
// } else {
//     echo "Error deleting table: " . $db->lastErrorMsg();
// }

// Create table
$SQL_create_table = "CREATE TABLE IF NOT EXISTS Articles (
    ArticleId INTEGER PRIMARY KEY AUTOINCREMENT,
    ArticleTitle VARCHAR(80),
    ArticleBody VARCHAR(500),

    CreateDate DATE,
    StartDate DATE,
    EndDate DATE,

    ContributerUsername VARCHAR(80)
);";

// Execute the table creation query
$db->exec($SQL_create_table);

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
    $article_title = trim($_POST['article_title']);
    $article_body = trim($_POST['article_body']);
    
    $create_date = date("Y/m/d");
    $start_date = date("Y/m/d");
    $end_date = date("Y/m/d");
    
    // $contributer_username_email = $_SESSION['username'];
    $contributer_username_email = "test@test.com";

    // Correct SQL syntax by adding quotes around variables
    $SQL_insert_new_data = "INSERT INTO Articles (ArticleTitle, ArticleBody, 
    CreateDate, StartDate, EndDate, ContributerUsername) 
    VALUES ('$article_title', '$article_body', 
    '$create_date', '$start_date', '$end_date', '$contributer_username_email');";

    
    // Execute the new insert statement
    $db->exec($SQL_insert_new_data);

    $id_count += $id_count;
}

?>

<!-- Blurred background -->
<div class="background-blur"></div>

<!-- Write Article Button -->
<div class="container mt-4">
    <div class="text-center">
        <a href="write.php" class="btn btn-primary btn-lg">Click to manage your articles</a>
        <!-- <a href="write.php" class="btn btn-primary btn-lg">Edit article</a>
        <a href="write.php" class="btn btn-primary btn-lg">Write New Article</a> -->
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
                <?= htmlspecialchars($row['ContributerUsername']) ?>, <?= date("F j, Y", strtotime($row['CreateDate'])) ?>
            </p>

            <!-- Article Preview -->
            <p>
                <span id="preview_<?= $row['ArticleId'] ?>">
                    <?= htmlspecialchars(substr($row['ArticleBody'], 0, 100)) ?>...
                </span>
                <span id="full_<?= $row['ArticleId'] ?>" style="display: none;">
                    <?= nl2br(htmlspecialchars($row['ArticleBody'])) ?>
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
