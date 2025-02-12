<?php include('./inc/inc_header.php'); ?>

<?php

$id_count = 1;

session_start();
$db = new SQLite3('info.db');


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

<!-- Featured Articles Section -->
<section class="py-5 content">
    <div class="container">
        <h2 class="mb-4 text-center">Latest Articles</h2>
        <div class="row g-4">
            <!-- Article Card 1 -->
            <div class="col-md-4">
                <div class="card article-card shadow-lg">
                    <div class="card-body">
                        <h5 class="card-title">Understanding Web Development</h5>
                        <p class="card-text">A comprehensive guide to modern web development practices and technologies.</p>
                        <a href="article-details.php?id=1" class="btn btn-outline-primary">Read More</a>
                    </div>
                </div>
            </div>

            <!-- Article Card 2 -->
            <div class="col-md-4">
                <div class="card article-card shadow-lg">
                    <div class="card-body">
                        <h5 class="card-title">Boosting Productivity with AI</h5>
                        <p class="card-text">Explore how artificial intelligence is transforming productivity and efficiency.</p>
                        <a href="article-details.php?id=2" class="btn btn-outline-primary">Read More</a>
                    </div>
                </div>
            </div>

            <!-- Article Card 3 -->
            <div class="col-md-4">
                <div class="card article-card shadow-lg">
                    <div class="card-body">
                        <h5 class="card-title">The Future of Technology</h5>
                        <p class="card-text">An in-depth look at emerging trends and technologies shaping the future.</p>
                        <a href="article-details.php?id=3" class="btn btn-outline-primary">Read More</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include('./inc/inc_footer.php'); ?>
