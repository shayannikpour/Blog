<?php include('./inc/inc_header.php'); ?>

<div class="background-blur"></div>

<div class="content">
    <div class="form-container">
        <h2>Login</h2>
        <form method="post" action="login_process.php">
            <div class="input-group">
                <label for="username">Username</label>
                <input type="email" name="username" id="username" required>
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required>
            </div>
            <button type="submit" class="btn-primary">Login</button>
        </form>
        <br>
        <p class="form-footer">
            <a href="register.php">Register</a>
        </p>
    </div>
</div>

<?php include('./inc/inc_footer.php'); ?>
