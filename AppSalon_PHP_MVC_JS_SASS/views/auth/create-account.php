<h1 class="page-name">Create Account</h1>
<p class="page-description">Please fill out the following form to create an account</p>

<?php 
    include_once __DIR__ . "/../templates/alerts.php"
?>

<form action="/create-account" class="form" method="POST">
    <div class="form-field">
        <label for="name">Name</label>
        <input 
            type="text"
            id="name"
            name="name"
            placeholder="Your Name"
            value="<?php echo san($user->name); ?>"
        />
    </div>

    <div class="form-field">
        <label for="last_name">Last name</label>
        <input 
            type="text"
            id="last_name"
            name="last_name"
            placeholder="Your Last Name"
            value="<?php echo san($user->last_name); ?>"
        />
    </div>

    <div class="form-field">
        <label for="phone">Phone Number</label>
        <input 
            type="tel"
            id="phone"
            name="phone"
            placeholder="Your Phone Number"
            value="<?php echo san($user->phone); ?>"
        />
    </div>

    <div class="form-field">
        <label for="email">E-mail</label>
        <input 
            type="email"
            id="email"
            name="email"
            placeholder="Your E-mail"
            value="<?php echo san($user->email); ?>"
        />
    </div>

    <div class="form-field">
        <label for="password">Password</label>
        <input 
            type="password"
            id="password"
            name="password"
            placeholder="Your Password"
        />
    </div>

    <input type="submit" value="Create Account" class="button">

</form>

<div class="actions">
    <a href="/">Do you already have an account? Log in</a>
    <a href="/forget">Forgot your password?</a>
</div>
