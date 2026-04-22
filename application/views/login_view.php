<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>

<h2>Login</h2>

<form method="post" action="<?= site_url('login/auth') ?>">
    <p>
        <label>Email:</label><br>
        <input type="text" name="email">
    </p>

    <p>
        <label>Password:</label><br>
        <input type="password" name="password">
    </p>

    <button type="submit">Login</button>
</form>


</body>
</html>
