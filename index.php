<?php include('./inc/inc_header.php'); ?>

<?php
$db = new SQLite3('info.db');
$version = $db->querySingle('SELECT SQLITE_VERSION()');
// echo "<br />version: " . $version . "<br />";


$SQL_create_table = "CREATE TABLE IF NOT EXISTS Users (
    Username VARCHAR(255) NOT NULL PRIMARY KEY,
    Password VARCHAR(255) NOT NULL,
    FirstName VARCHAR(50) NOT NULL,
    LastName VARCHAR(50) NOT NULL,
    RegistrationDate DATETIME DEFAULT CURRENT_TIMESTAMP,
    IsApproved BOOLEAN DEFAULT 0,
    Role VARCHAR(20) DEFAULT 'Contributor'
);
CREATE TABLE IF NOT EXISTS Articles (
    ArticleId INTEGER PRIMARY KEY AUTOINCREMENT,
    ArticleTitle VARCHAR(80),
    ArticleBody VARCHAR(500),

    CreateDate DATE,
    StartDate DATE,
    EndDate DATE,

    ContributerUsername VARCHAR(80)
);";



$db->exec($SQL_create_table);

?>

<!-- Hey -->

<div class="background-blur"></div> <!-- Blurred background -->

<section class="vh-100 d-flex align-items-center justify-content-center text-center">
    <div class="container">
        <h1 class="display-4 fw-bold">Welcome to Our Blog Platform!</h1>
        <p class="lead text-muted mt-3">Discover engaging articles and thought-provoking insights. Let's explore!</p>
        <a href="register.php" class="btn btn-primary btn-lg mt-4" style="width: 100px;">Sign up</a>
<!-- </br> -->
        <a href="login.php" class="btn btn-primary btn-lg mt-4" style="width: 100px;">Log in</a>
    </div>
</section>


<?php include('./inc/inc_footer.php'); ?>