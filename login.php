<?php
include_once './lib/config.php';

$errors = ['username' => '', 'password' => '', 'login' => ''];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    if (empty($_POST['username'])) {
        $errors['username'] = 'Username is required. Please ensure the username is filled out. </br>';
    }
    if (empty($_POST['password'])) {
        $errors['password'] = 'Password is required. Please ensure the password is filled out. </br>';
    }

    if (!array_filter($errors)) {
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $password = $_POST['password'];

        $result = mysqli_query($conn, "SELECT username, password FROM users WHERE username = '$username'");

        if (password_verify($password, mysqli_fetch_array($result)['password'])) {
            session_start();
            $_SESSION['username'] = $username;
            header("Location: index.php");
        } else {
            $errors['login'] = 'The password or username is not valid.';
        }
    }
}
$content = '<form action="login.php" method="POST">
    <h2>Login
    <small>Please fill in your username and password</small>
    </h2>
    <table>
        <tr>
            <td> Username:</td>
            <td>
                <input type="text" name="username" value="' . ($_POST['username'] ?? '') . '">
                <div class="error">' . $errors['username'] . '</div>
            </td>
        </tr>
        <tr>
            <td> Password:</td>
            <td>
                <input type="password" name="password" value="' . ($_POST['password'] ?? '') . '">
                <div class="error">' . $errors['password'] . '</div>
            </td>
        </tr>
    </table>
    <div class="error">' . $errors['login'] . '</div>
    <a href="#" class="forgot">Forgot Password</a>
    <div>
        <input type="submit" name="submit" value="Submit">
    </div>
</form>';
$styles = '<link href="./css/login.css" rel="stylesheet">';

include './lib/template.php';
