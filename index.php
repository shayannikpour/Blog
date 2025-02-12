<?php include('./inc/inc_header.php'); ?>

<?php
$db = new SQLite3('info.db');
$version = $db->querySingle('SELECT SQLITE_VERSION()');
// echo "<br />version: " . $version . "<br />";


$SQL_create_table = "CREATE TABLE IF NOT EXISTS Users
(
Username VARCHAR(30) NOT NULL,
Password VARCHAR(80),
PRIMARY KEY (Username)
);";


$db->exec($SQL_create_table);

?>


<div class="background-blur"></div> <!-- Blurred background -->

<section class="vh-100 d-flex align-items-center justify-content-center text-center">
    <div class="container">
        <h1 class="display-4 fw-bold">Welcome to Our Blog Platform!</h1>
        <p class="lead text-muted mt-3">Discover engaging articles and thought-provoking insights. Let's explore!</p>
        <a href="register.php" class="btn btn-primary btn-lg mt-4">Sign up</a>
<!-- </br> -->
        <a href="login.php" class="btn btn-primary btn-lg mt-4">Log in</a>
    </div>
</section>


<?php include('./inc/inc_footer.php'); ?>