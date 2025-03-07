<?php
session_start();
$db = new SQLite3('info.db');

// Define validation variables
$errors = [];
$success_message = '';

if (!isset($_SESSION['username'])) 
{
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

// Helper function to validate and sanitize inputs
function validateInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

// Helper function to validate date format (YYYY-MM-DD)
function isValidDate($date) {
    if (empty($date)) return false;
    
    $date_parts = explode('-', $date);
    if (count($date_parts) !== 3) return false;
    
    return checkdate($date_parts[1], $date_parts[2], $date_parts[0]);
}

// Helper function to validate chronological order of dates
function datesInOrder($start_date, $end_date) {
    $start = strtotime($start_date);
    $end = strtotime($end_date);
    
    return $start <= $end;
}

// Handle Creating a New Article
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['create_article'])) 
{
    // Validate and sanitize inputs
    $title = validateInput($_POST['article_title'] ?? '');
    $body = validateInput($_POST['article_body'] ?? '');
    $start_date = validateInput($_POST['start_date'] ?? '');
    $end_date = validateInput($_POST['end_date'] ?? '');
    
    // Check required fields
    if (empty($title)) {
        $errors[] = "Article title is required";
    }
    
    if (empty($body)) {
        $errors[] = "Article body is required";
    }
    
    // Validate dates
    if (empty($start_date)) {
        $errors[] = "Start date is required";
    } elseif (!isValidDate($start_date)) {
        $errors[] = "Invalid start date format";
    }
    
    if (empty($end_date)) {
        $errors[] = "End date is required";
    } elseif (!isValidDate($end_date)) {
        $errors[] = "Invalid end date format";
    }
    
    // Check that end date is not before start date
    if (isValidDate($start_date) && isValidDate($end_date) && !datesInOrder($start_date, $end_date)) {
        $errors[] = "End date cannot be before start date";
    }
    
    // If validation passes, insert the article
    if (empty($errors)) 
    {
        try {
            // Get the current date in Pacific Standard Time
            $current_date = new DateTime('now', new DateTimeZone('America/Los_Angeles'));
            $formatted_date = $current_date->format('Y-m-d');

            $stmt = $db->prepare("INSERT INTO Articles (ArticleTitle, ArticleBody, CreateDate, StartDate, EndDate, ContributerUsername) VALUES (:title, :body, :create_date, :start_date, :end_date, :username)");
            $stmt->bindValue(':title', $title, SQLITE3_TEXT);
            $stmt->bindValue(':body', $body, SQLITE3_TEXT);
            $stmt->bindValue(':create_date', $formatted_date, SQLITE3_TEXT);
            $stmt->bindValue(':username', $username, SQLITE3_TEXT);
            $stmt->bindValue(':start_date', $start_date, SQLITE3_TEXT);
            $stmt->bindValue(':end_date', $end_date, SQLITE3_TEXT);
            
            $result = $stmt->execute();
            
            if ($result) {
                $success_message = "Article created successfully!";
                // Clear form data after successful submission
                $title = $body = $start_date = $end_date = "";
            } else {
                $errors[] = "Failed to create article. Database error.";
            }
        } catch (Exception $e) {
            $errors[] = "An error occurred: " . $e->getMessage();
        }
    }
}

// Handle Article Deletion
if (isset($_POST['delete_article'])) 
{
    $article_id = filter_var($_POST['article_id'] ?? 0, FILTER_VALIDATE_INT);
    
    if (!$article_id) {
        $errors[] = "Invalid article ID for deletion";
    } else {
        try {
            // Ensure user owns the article before deleting
            $stmt = $db->prepare("DELETE FROM Articles WHERE ArticleId = :id AND ContributerUsername = :username");
            $stmt->bindValue(':id', $article_id, SQLITE3_INTEGER);
            $stmt->bindValue(':username', $username, SQLITE3_TEXT);
            $result = $stmt->execute();
            
            if ($result) {
                $success_message = "Article deleted successfully!";
            } else {
                $errors[] = "Failed to delete article. Database error.";
            }
        } catch (Exception $e) {
            $errors[] = "An error occurred: " . $e->getMessage();
        }
    }
}

// Handle Article Editing
if (isset($_POST['edit_article'])) 
{
    // Validate and sanitize inputs
    $article_id = filter_var($_POST['article_id'] ?? 0, FILTER_VALIDATE_INT);
    $new_title = validateInput($_POST['article_title'] ?? '');
    $new_body = validateInput($_POST['article_body'] ?? '');
    $new_start_date = validateInput($_POST['start_date'] ?? '');
    $new_end_date = validateInput($_POST['end_date'] ?? '');
    
    // Check required fields and article ID
    if (!$article_id) {
        $errors[] = "Invalid article ID for editing";
    }
    
    if (empty($new_title)) {
        $errors[] = "Article title is required";
    }
    
    if (empty($new_body)) {
        $errors[] = "Article body is required";
    }
    
    // Validate dates
    if (empty($new_start_date)) {
        $errors[] = "Start date is required";
    } elseif (!isValidDate($new_start_date)) {
        $errors[] = "Invalid start date format";
    }
    
    if (empty($new_end_date)) {
        $errors[] = "End date is required";
    } elseif (!isValidDate($new_end_date)) {
        $errors[] = "Invalid end date format";
    }
    
    // Check that end date is not before start date
    if (isValidDate($new_start_date) && isValidDate($new_end_date) && !datesInOrder($new_start_date, $new_end_date)) {
        $errors[] = "End date cannot be before start date";
    }
    
    // If validation passes, update the article
    if (empty($errors)) 
    {
        try {
            $stmt = $db->prepare("UPDATE Articles SET ArticleTitle = :title, ArticleBody = :body, StartDate = :start_date, EndDate = :end_date WHERE ArticleId = :id AND ContributerUsername = :username");
            $stmt->bindValue(':title', $new_title, SQLITE3_TEXT);
            $stmt->bindValue(':body', $new_body, SQLITE3_TEXT);
            $stmt->bindValue(':start_date', $new_start_date, SQLITE3_TEXT);
            $stmt->bindValue(':end_date', $new_end_date, SQLITE3_TEXT);
            $stmt->bindValue(':id', $article_id, SQLITE3_INTEGER);
            $stmt->bindValue(':username', $username, SQLITE3_TEXT);
            
            $result = $stmt->execute();
            
            if ($result) {
                $success_message = "Article updated successfully!";
            } else {
                $errors[] = "Failed to update article. Database error.";
            }
        } catch (Exception $e) {
            $errors[] = "An error occurred: " . $e->getMessage();
        }
    }
}

$articles = $db->query("SELECT * FROM Articles WHERE ContributerUsername = '$username' ORDER BY CreateDate DESC");
?>

<?php include('./inc/inc_header.php'); ?>

<div class="container mt-5">
    <h2 class="text-center mb-4">Manage Your Articles</h2>
    
    <!-- Display validation errors -->
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <strong>Error!</strong>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    
    <!-- Display success message -->
    <?php if (!empty($success_message)): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($success_message) ?>
        </div>
    <?php endif; ?>

    <!-- Create Article Form -->
    <div class="card shadow-lg p-4 mb-5">
        <h3 class="mb-3">Write a New Article</h3>
        <form method="post" novalidate>
            <div class="mb-3">
                <label for="article_title" class="form-label fw-bold">Title</label>
                <input type="text" class="form-control" name="article_title" id="article_title" required placeholder="Enter article title" value="<?= htmlspecialchars($title ?? '') ?>">
            </div>
            <div class="mb-3">
                <label for="article_body" class="form-label fw-bold">Body</label>
                <textarea class="form-control" name="article_body" id="article_body" rows="6" required placeholder="Write your article here..."><?= htmlspecialchars($body ?? '') ?></textarea>
            </div>
           <!-- Start Date Field -->
           <div class="mb-3">
            <label for="start_date" class="form-label fw-bold">Start Date</label>
            <input type="date" class="form-control" name="start_date" id="start_date" required value="<?= htmlspecialchars($start_date ?? '') ?>">
        </div>
        <!-- End Date Field -->
        <div class="mb-3">
            <label for="end_date" class="form-label fw-bold">End Date</label>
            <input type="date" class="form-control" name="end_date" id="end_date" required value="<?= htmlspecialchars($end_date ?? '') ?>">
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
                <p class="text-muted"><small>Visible from <?= $row['StartDate'] ?> to <?= $row['EndDate'] ?></small></p>

                <!-- Edit Article Button -->
                <button class="btn btn-warning edit-article-btn" 
                    data-id="<?= $row['ArticleId'] ?>"
                    data-title="<?= htmlspecialchars($row['ArticleTitle'], ENT_QUOTES, 'UTF-8') ?>"
                    data-body="<?= htmlspecialchars($row['ArticleBody'], ENT_QUOTES, 'UTF-8') ?>"
                    data-start-date="<?= $row['StartDate'] ?>"
                    data-end-date="<?= $row['EndDate'] ?>">Edit</button>

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
                <h5 class="modal-title text-dark">Edit Article</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="post">
                    <input type="hidden" name="article_id" id="edit_article_id">
                    <div class="mb-3">
                        <label for="edit_article_title" class="form-label fw-bold text-dark">Title</label>
                        <input type="text" class="form-control" name="article_title" id="edit_article_title" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_article_body" class="form-label fw-bold text-dark">Body</label>
                        <textarea class="form-control" name="article_body" id="edit_article_body" rows="6" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="edit_article_start_date" class="form-label fw-bold text-dark">Start Date</label>
                        <input type="date" class="form-control" name="start_date" id="edit_article_start_date" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_article_end_date" class="form-label fw-bold text-dark">End Date</label>
                        <input type="date" class="form-control" name="end_date" id="edit_article_end_date" required>
                    </div>
                    <button type="submit" name="edit_article" class="btn btn-success">Update Article</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript to Open Edit Modal -->
<script>
    // Add event listeners for all edit buttons
    document.addEventListener('DOMContentLoaded', function() {
        const editButtons = document.querySelectorAll('.edit-article-btn');
        
        // Helper function to safely decode HTML entities
        function decodeHtmlEntities(text) {
            const textArea = document.createElement('textarea');
            textArea.innerHTML = text;
            return textArea.value;
        }
        
        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                try {
                    const id = this.getAttribute('data-id');
                    const title = decodeHtmlEntities(this.getAttribute('data-title'));
                    const body = decodeHtmlEntities(this.getAttribute('data-body'));
                    const startDate = this.getAttribute('data-start-date');
                    const endDate = this.getAttribute('data-end-date');
                    
                    // Ensure proper values are set
                    document.getElementById("edit_article_id").value = id || '';
                    document.getElementById("edit_article_title").value = title || '';
                    document.getElementById("edit_article_body").value = body || '';
                    document.getElementById("edit_article_start_date").value = startDate || '';
                    document.getElementById("edit_article_end_date").value = endDate || '';
                    
                    // Open the modal
                    const modal = new bootstrap.Modal(document.getElementById('editArticleModal'));
                    modal.show();
                } catch (error) {
                    console.error("Error loading edit modal:", error);
                    alert("There was an error trying to edit this article. Please try again.");
                }
            });
        });
    });
    
    // Keep the old function for backward compatibility
    function editArticle(id, title, body, start_date, end_date) {
        try {
            document.getElementById("edit_article_id").value = id || '';
            document.getElementById("edit_article_title").value = title || '';
            document.getElementById("edit_article_body").value = body || '';
            document.getElementById("edit_article_start_date").value = start_date || '';
            document.getElementById("edit_article_end_date").value = end_date || '';
            new bootstrap.Modal(document.getElementById('editArticleModal')).show();
        } catch (error) {
            console.error("Error in editArticle function:", error);
            alert("There was an error trying to edit this article. Please try again.");
        }
    }
</script>

<?php include('./inc/inc_footer.php'); ?>
