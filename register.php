<?php include('./inc/inc_header.php'); ?>

<div class="background-blur"></div>

<div class="content">
    <div class="form-container">
        <h2>Register</h2>
        <form method="post" action="register_process.php">
            <div class="input-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" required>
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required>
            </div>
            <div class="input-group">
                <label for="confirmpassword">Confirm Password</label>
                <input type="password" name="confirmpassword" id="confirmpassword" required>
            </div>
            <button class="btn-primary" type="submit">Register</button>
        </form>
        <p class="form-footer">
            Already have an account? <a href="login.php">Login</a>
        </p>
    </div>
</div>

<?php include('./inc/inc_footer.php'); ?>
