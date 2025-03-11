<?php include('./inc/inc_header.php'); ?>

<div class="background-blur"></div>

<div class="content">
    <div class="form-container">
        <h2>Register</h2>
        <form method="post" action="register_process.php">
            <div class="input-group">
                <label for="firstname">First Name</label>
                <input type="text" name="firstname" id="firstname" required>
            </div>
            <div class="input-group">
                <label for="lastname">Last Name</label>
                <input type="text" name="lastname" id="lastname" required>
            </div>
            <div class="input-group">
                <label for="username">Username (Email)</label>
                <input type="email" name="username" id="username" required>
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required onkeyup="validatePassword()">
                <ul id="password-requirements">
                    <li id="length" class="invalid">🔴 At least 8 characters</li>
                    <li id="uppercase" class="invalid">🔴 At least one uppercase letter</li>
                    <li id="lowercase" class="invalid">🔴 At least one lowercase letter</li>
                    <li id="number" class="invalid">🔴 At least one number</li>
                    <li id="special" class="invalid">🔴 At least one special character (@$!%*?&)</li>
                </ul>
            </div>
            <div class="input-group">
                <label for="confirmpassword">Confirm Password</label>
                <input type="password" name="confirmpassword" id="confirmpassword" required>
            </div>
            <button class="btn-primary" type="submit">Register</button>
        </form>
        <br>
        <p class="form-footer">
            <a href="login.php">Login</a>
        </p>
    </div>
</div>

<?php include('./inc/inc_footer.php'); ?>

<style>
    /* Style for password validation checklist */
    #password-requirements {
        list-style: none;
        padding: 0;
        margin-top: 5px;
        font-size: 14px;
    }
    #password-requirements li {
        margin-bottom: 3px;
        transition: all 0.2s;
    }
    .valid {
        color: green;
        font-weight: bold;
    }
    .invalid {
        color: red;
    }
</style>

<script>
    function validatePassword() {
        let password = document.getElementById("password").value;

        // Define password criteria
        let length = document.getElementById("length");
        let uppercase = document.getElementById("uppercase");
        let lowercase = document.getElementById("lowercase");
        let number = document.getElementById("number");
        let special = document.getElementById("special");

        // Check password requirements
        length.classList.toggle("valid", password.length >= 8);
        length.classList.toggle("invalid", password.length < 8);
        length.innerHTML = password.length >= 8 ? "✅ At least 8 characters" : "🔴 At least 8 characters";

        uppercase.classList.toggle("valid", /[A-Z]/.test(password));
        uppercase.classList.toggle("invalid", !/[A-Z]/.test(password));
        uppercase.innerHTML = /[A-Z]/.test(password) ? "✅ At least one uppercase letter" : "🔴 At least one uppercase letter";

        lowercase.classList.toggle("valid", /[a-z]/.test(password));
        lowercase.classList.toggle("invalid", !/[a-z]/.test(password));
        lowercase.innerHTML = /[a-z]/.test(password) ? "✅ At least one lowercase letter" : "🔴 At least one lowercase letter";

        number.classList.toggle("valid", /\d/.test(password));
        number.classList.toggle("invalid", !/\d/.test(password));
        number.innerHTML = /\d/.test(password) ? "✅ At least one number" : "🔴 At least one number";

        special.classList.toggle("valid", /[@$!%*?&]/.test(password));
        special.classList.toggle("invalid", !/[@$!%*?&]/.test(password));
        special.innerHTML = /[@$!%*?&]/.test(password) ? "✅ At least one special character (@$!%*?&)" : "🔴 At least one special character (@$!%*?&)";
    }
</script>
