<?php include('./inc/inc_header.php'); ?>

<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>

<!-- Blurred background -->
<div class="background-blur"></div>

<!-- Featured Articles Section -->
<section class="py-5 content">
    <div class="container">
        <h2 class="mb-4 text-center">Latest Articles</h2>
        <div class="row g-4">
            <!-- Article Card 1 -->
            <div class="col-md-4">
                <div class="card article-card shadow-lg">
                    <img src="https://via.placeholder.com/350x200" class="card-img-top" alt="Article 1">
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
                    <img src="https://via.placeholder.com/350x200" class="card-img-top" alt="Article 2">
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
                    <img src="https://via.placeholder.com/350x200" class="card-img-top" alt="Article 3">
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
