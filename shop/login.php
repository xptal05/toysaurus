<form id="login-form">
    <input type="text" id="login-username" placeholder="Your user name" name="login-username" style="display: inline-block;">
    <div class="flex-row no-wrap"><input type="password" id="login-password" placeholder="Your password" name="login-pasword"><span class="show-password-btn" style="display: inline-block;"><svg class="icon"width="800" height="800" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path d="M21.92 11.6C19.9 6.91 16.1 4 12 4s-7.9 2.91-9.92 7.6a1 1 0 0 0 0 .8C4.1 17.09 7.9 20 12 20s7.9-2.91 9.92-7.6a1 1 0 0 0 0-.8M12 18c-3.17 0-6.17-2.29-7.9-6C5.83 8.29 8.83 6 12 6s6.17 2.29 7.9 6c-1.73 3.71-4.73 6-7.9 6m0-10a4 4 0 1 0 4 4 4 4 0 0 0-4-4m0 6a2 2 0 1 1 2-2 2 2 0 0 1-2 2" />
        </svg></span></div>
    <button type="submit" value="Login" id="login-submit-btn">LOGIN</button>
    <a href="#">Forgot my password</a>
</form>
<script src="./assets/script/ajax.js"></script>
<script>
    const showPaswordBtn = document.querySelector('.show-password-btn')
    const passwordInput = document.getElementById('login-password')

    showPaswordBtn.addEventListener('click', (e) => {
        if (passwordInput.type === "password") {
            passwordInput.type = "text"
        } else if (passwordInput.type === "text") {
            passwordInput.type = "password"
        }
    })

    const loginForm = document.getElementById('login-form')

    loginForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        console.log('submitted')

        const loginId = document.getElementById('login-username').value;
        const password = document.getElementById('login-password').value;

        // 🔹 Send AJAX request to login.php
        const response = await sendAjaxRequest("./functions/shop-login-db.php", "POST", {
            loginId,
            password
        });

        if (response.success) {
            alert("✅ Login Successful!");
            window.location.href = "my-account";
        } else {
            alert("❌ Login Failed: " + response.message);
        }

    })
</script>