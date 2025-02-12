<?php
session_start();
$db = new SQLite3('info.db');

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

// Handle Creating a New Article
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['create_article'])) {
    $title = trim($_POST['article_title']);
    $body = trim($_POST['article_body']);

    if (!empty($title) && !empty($body)) {
        $stmt = $db->prepare("INSERT INTO Articles (ArticleTitle, ArticleBody, CreateDate, ContributerUsername) VALUES (:title, :body, DATE('now'), :username)");
        $stmt->bindValue(':title', $title, SQLITE3_TEXT);
        $stmt->bindValue(':body', $body, SQLITE3_TEXT);
        $stmt->bindValue(':username', $username, SQLITE3_TEXT);
        $stmt->execute();
        header("Location: home.php");
        exit();
    }
}

// Handle Article Deletion
if (isset($_POST['delete_article'])) {
    $article_id = $_POST['article_id'];

    // Ensure user owns the article before deleting
    $stmt = $db->prepare("DELETE FROM Articles WHERE ArticleId = :id AND ContributerUsername = :username");
    $stmt->bindValue(':id', $article_id, SQLITE3_INTEGER);
    $stmt->bindValue(':username', $username, SQLITE3_TEXT);
    $stmt->execute();
}

// Handle Article Editing
if (isset($_POST['edit_article'])) {
    $article_id = $_POST['article_id'];
    $new_title = trim($_POST['article_title']);
    $new_body = trim($_POST['article_body']);

    if (!empty($new_title) && !empty($new_body)) {
        $stmt = $db->prepare("UPDATE Articles SET ArticleTitle = :title, ArticleBody = :body WHERE ArticleId = :id AND ContributerUsername = :username");
        $stmt->bindValue(':title', $new_title, SQLITE3_TEXT);
        $stmt->bindValue(':body', $new_body, SQLITE3_TEXT);
        $stmt->bindValue(':id', $article_id, SQLITE3_INTEGER);
        $stmt->bindValue(':username', $username, SQLITE3_TEXT);
        $stmt->execute();
    }
}

$articles = $db->query("SELECT * FROM Articles WHERE ContributerUsername = '$username' ORDER BY CreateDate DESC");
?>

<?php include('./inc/inc_header.php'); ?>

<div class="container mt-5">
    <h2 class="text-center mb-4">Manage Your Articles</h2>

    <!-- Create Article Form -->
    <div class="card shadow-lg p-4 mb-5">
        <h3 class="mb-3">Write a New Article</h3>
        <form method="post">
            <div class="mb-3">
                <label for="article_title" class="form-label fw-bold">Title</label>
                <input type="text" class="form-control" name="article_title" id="article_title" required placeholder="Enter article title">
            </div>
            <div class="mb-3">
                <label for="article_body" class="form-label fw-bold">Body</label>
                <textarea class="form-control" name="article_body" id="article_body" rows="6" required placeholder="Write your article here..."></textarea>
            </div>
            <button type="submit" name="create_article" class="btn btn-primary">Post Article</button>
        </form>
    </div>

    <!-- List of User Articles -->
    <h3 class="mb-3">Your Articles</h3>
    <?php while ($row = $articles->fetchArray(SQLITE3_ASSOC)) { ?>
        <div class="card mb-3">
            <div class="card-body">
                <h4 class="card-title"><?= htmlspecialchars($row['ArticleTitle']) ?></h4>
                <p class="card-text"><?= nl2br(htmlspecialchars($row['ArticleBody'])) ?></p>
                <p class="text-muted"><small>Posted on <?= $row['CreateDate'] ?></small></p>

                <!-- Edit Article Button -->
                <button class="btn btn-warning" onclick="editArticle('<?= $row['ArticleId'] ?>', '<?= htmlspecialchars($row['ArticleTitle']) ?>', '<?= htmlspecialchars($row['ArticleBody']) ?>')">Edit</button>

                <!-- Delete Article Form -->
                <form method="post" class="d-inline">
                    <input type="hidden" name="article_id" value="<?= $row['ArticleId'] ?>">
                    <button type="submit" name="delete_article" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this article?');">Delete</button>
                </form>
            </div>
        </div>
    <?php } ?>
</div>

<!-- Edit Article Modal -->
<div id="editArticleModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Article</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="post">
                    <input type="hidden" name="article_id" id="edit_article_id">
                    <div class="mb-3">
                        <label for="edit_article_title" class="form-label fw-bold">Title</label>
                        <input type="text" class="form-control" name="article_title" id="edit_article_title" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_article_body" class="form-label fw-bold">Body</label>
                        <textarea class="form-control" name="article_body" id="edit_article_body" rows="6" required></textarea>
                    </div>
                    <button type="submit" name="edit_article" class="btn btn-success">Update Article</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript to Open Edit Modal -->
<script>
    function editArticle(id, title, body) {
        document.getElementById("edit_article_id").value = id;
        document.getElementById("edit_article_title").value = title;
        document.getElementById("edit_article_body").value = body;
        new bootstrap.Modal(document.getElementById('editArticleModal')).show();
    }
</script>

<?php include('./inc/inc_footer.php'); ?>
