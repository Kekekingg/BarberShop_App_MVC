<h1 class="page-name">Login</h1>
<p class="page-description">Sign in to your account</p>

<form action="/" class="form" method="post">
    <div class="form-field">
        <label for="email">Email</label>
        <input 
            type="email"
            id="email"
            placeholder="Your Email"
            name="email" 
        /> 
    </div>

    <div class="form-field">
        <label for="password">Password</label>
        <input 
            type="password"
            id="password"
            placeholder="Your Password"
            name="password"
        />
    </div>

    <input type="submit" class="button" value="Login">
</form>

<div class="actions">
    <a href="/create-account">Still don’t have an account? Create one</a>
    <a href="/forget">Forgot your password?</a>
</div>