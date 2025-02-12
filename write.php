<?php include('./inc/inc_header.php'); ?>



<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-lg p-4">
                <h2 class="text-center mb-4">Write Article</h2>
                <form method="post" action="home.php">
                    <!-- Title -->
                    <div class="mb-3">
                        <label for="article_title" class="form-label fw-bold">Title</label>
                        <input type="text" class="form-control" name="article_title" id="article_title" required placeholder="Enter article title">
                    </div>

                    <!-- Body (Large Text Area) -->
                    <div class="mb-3">
                        <label for="article_body" class="form-label fw-bold">Body</label>
                        <textarea class="form-control" name="article_body" id="article_body" rows="6" required placeholder="Write your article here..."></textarea>
                    </div>

                    <!-- Submit Button -->
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary btn-lg">Post Article</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<?php
session_start();
if (!isset($_SESSION['username'])) 
{
    header("Location: login.php");
    exit();
}
?>

<?php include('./inc/inc_footer.php'); ?>