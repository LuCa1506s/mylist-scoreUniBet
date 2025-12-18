<?php
require_once __DIR__.'/../includes/config.php';
require_once __DIR__.'/../templates/header.php';
?>

<link rel="stylesheet" href="../template/auth.css">

<section class="auth-container">
    <div class="auth-card">
        <h2>Accedi</h2>
        <form method="post" action="../includes/clogin.php" class="auth-form">
            <div class="form-group">
                <label for="userName">name</label>
                <input id="userName" name="userName" required>
            </div>

            <div class="form-group">
                <label for="password-login">Password</label>
                <input id="password-login" type="password" name="password-login" required>
            </div>

            <button class="btn" type="submit">Login</button>
        </form>
    </div>

    <div class="auth-card">
        <h2>Registrati</h2>
        <form method="post" action="../includes/register.php" class="auth-form">
            <div class="form-group">
                <label for="username-reg">Username</label>
                <input id="username-reg" type="text" name="username-reg" required>
            </div>

            <div class="form-group">
                <label for="email-reg">Email</label>
                <input id="email-reg" type="email" name="email-reg" required>
            </div>

            <div class="form-group">
                <label for="password-reg">Password</label>
                <input id="password-reg" type="password" name="password-reg" required>
            </div>

            <button class="btn" type="submit">Crea account</button>
        </form>
    </div>
</section>

<?php
require_once __DIR__.'/../templates/footer.php';
?>

<style>
/* Container */
.auth-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 2rem;
    padding: 3rem;
    max-width: 1000px;
    margin: 0 auto;
}

/* Card */
.auth-card {
    background: #ffffff;
    padding: 2rem;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    transition: transform 0.2s ease;
}

.auth-card:hover {
    transform: translateY(-5px);
}

.auth-card h2 {
    margin-bottom: 1.5rem;
    font-weight: 600;
    text-align: center;
}

/* Form */
.auth-form .form-group {
    margin-bottom: 1.2rem;
    display: flex;
    flex-direction: column;
}

.auth-form label {
    margin-bottom: 0.4rem;
    font-weight: 500;
}

.auth-form input {
    padding: 0.75rem;
    border: 1px solid #ccc;
    border-radius: 10px;
    outline: none;
    transition: border-color 0.2s;
}

.auth-form input:focus {
    border-color: #4f46e5;
}

/* Button */
.btn {
    width: 100%;
    padding: 0.9rem;
    background: #4f46e5;
    color: white;
    border: none;
    border-radius: 10px;
    font-size: 1rem;
    cursor: pointer;
    transition: background 0.2s;
}

.btn:hover {
    background: #4338ca;
}

</style>