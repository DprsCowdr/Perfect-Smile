<?= view('templates/header') ?>

<style>
.login-split-container {
    min-height: 100vh;
    display: flex;
    align-items: stretch;
    background: #F5ECFE;
}
.login-left {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    padding: 0 2rem;
}
.login-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 2.5rem;
    color: #2d2d2d;
    font-family: 'Nunito', sans-serif;
}
.login-form {
    width: 100%;
    max-width: 400px;
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}
.login-form label {
    font-weight: 600;
    color: #2d2d2d;
    margin-bottom: 0.5rem;
    display: block;
}
.login-form input[type="email"],
.login-form input[type="password"] {
    border: 2px solid #c7aefc;
    border-radius: 8px;
    padding: 0.7rem 1rem;
    font-size: 1rem;
    background: #f9f6ff;
    color: #2d2d2d;
    outline: none;
    transition: border 0.2s;
    width: 100%;
    box-sizing: border-box;
    margin-bottom: 0.2rem;
}
.login-form input[type="email"]:focus,
.login-form input[type="password"]:focus {
    border-color: #a47be5;
}
.login-form .login-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.5rem;
}
.login-form .forgot-link {
    font-size: 0.95rem;
    color: #6c63ff;
    text-decoration: none;
    font-weight: 500;
    margin-left: 1rem;
}
.login-form .forgot-link:hover {
    text-decoration: underline;
}
.login-form .remember-row {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.5rem;
}
.login-form button {
    background: #c7aefc;
    color: #fff;
    border: none;
    border-radius: 8px;
    padding: 0.8rem 0;
    font-size: 1.1rem;
    font-weight: 700;
    cursor: pointer;
    transition: background 0.2s;
    width: 100%;
    margin-top: 0.5rem;
}
.login-form button:hover {
    background: #a47be5;
}
.login-right {
    flex: 1;
    background: #fff url('<?= base_url('img/bg.jpg') ?>') center center/cover no-repeat;
    border-top-left-radius: 40px;
    border-bottom-left-radius: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
    /* Placeholder for image */
}
@media (max-width: 900px) {
    .login-split-container {
        flex-direction: column;
    }
    .login-right {
        display: none;
    }
}
</style>

<div class="login-split-container">
    <div class="login-left">
        <div class="login-title">Perfect Smile :)</div>
        <form class="login-form">
            <div>
                <label for="email">Email address</label>
                <input type="email" id="email" placeholder="Enter your email" required>
            </div>
            <div>
                <div class="login-row">
                    <label for="password" style="margin-bottom:0;">Password</label>
                    <a href="#" class="forgot-link">forgot password</a>
                </div>
                <input type="password" id="password" placeholder="Name" required>
            </div>
            <div class="remember-row">
                <input type="checkbox" id="remember">
                <label for="remember" style="margin-bottom:0;">Remember Me</label>
            </div>
            <button type="submit">Login</button>
        </form>
    </div>
    <div class="login-right">
        <!-- Image goes here later -->
    </div>
</div>
<!-- SAMPLE CHANGE -->
<?= view('templates/footer') ?>
