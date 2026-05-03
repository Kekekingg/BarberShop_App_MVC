<h1 class="page-name">Forget My Password</h1>
<p class="page-description">Reset your password by entering your email address</p>

<?php 
    include_once __DIR__ . "/../templates/alerts.php"
?>

<form action="/forget" class="form" method="POST">
    <div class="form-field">
        <label for="email">Email</label>
        <input 
            type="email"
            id="email"
            name="email"
            placeholder="Your Email"
        />
    </div>

    <input type="submit" class="button" value="Send Instructions">
</form>

<div class="actions">
    <a href="/">Do you already have an account? Log in</a>
    <a href="/create-account">Don't have an account yet?</a>
</div>