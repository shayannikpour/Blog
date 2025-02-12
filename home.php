<?php include('./inc/inc_header.php'); ?>

<?php

$id_count = 1;

session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
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
        <a href="write.php" class="btn btn-primary btn-lg">Write New Article</a>
    </div>
</div>

<!-- Articles Section -->
<section class="py-5 content">
    <div class="container">
        <h2 class="mb-4 text-center">Latest Articles</h2>
        <div class="row g-4">
            <?php
            if ($results) {
                while ($article = $results->fetchArray(SQLITE3_ASSOC)) {
                    // Get excerpt of content (first 150 characters)
                    $excerpt = strlen($article['ArticleBody']) > 150 
                        ? substr($article['ArticleBody'], 0, 150) . '...' 
                        : $article['ArticleBody'];
            ?>
                    <div class="col-md-4">
                        <div class="card article-card shadow-lg">
                            <img src="https://via.placeholder.com/350x200" class="card-img-top" alt="Article thumbnail">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($article['ArticleTitle']); ?></h5>
                                <p class="card-text"><?php echo htmlspecialchars($excerpt); ?></p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="article-details.php?id=<?php echo $article['ArticleId']; ?>" class="btn btn-outline-primary">Read More</a>
                                    <small class="text-muted">
                                        <?php echo htmlspecialchars($article['CreateDate']); ?>
                                    </small>
                                </div>
                                <div class="mt-2">
                                    <small class="text-muted">
                                        By: <?php echo htmlspecialchars($article['ContributerUsername']); ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
            <?php 
                }
            }
            ?>
        </div>
    </div>
</section>

<?php include('./inc/inc_footer.php'); ?>
