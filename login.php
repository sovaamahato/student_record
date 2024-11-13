<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>
    <form action="login.php" method="POST">
        <label>Username:</label>
        <input type="text" name="username" required><br>
        <label>Password:</label>
        <input type="password" name="password" required><br>
        <button type="submit" name="login">Login</button>
    </form>

    <?php
    session_start();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        include 'config.php';

        $username = $_POST['username'];
        $password = $_POST['password'];

        // Check if the username/email exists in the users table
        $sql = "SELECT Id, password FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($id, $hashed_password);
        $stmt->fetch();

        if ($hashed_password && password_verify($password, $hashed_password)) {
            // Password is correct; set session variables and redirect to welcome page
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $username;
            header("Location: welcome.php");
            exit;
        } else {
            echo "Invalid username or password.";
        }

        $stmt->close();
        $conn->close();
    }
    ?>
</body>
</html>
