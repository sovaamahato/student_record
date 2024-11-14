<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
            font-size: 24px;
        }

        label {
            font-size: 14px;
            color: #333;
            display: block;
            margin-bottom: 6px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 8px 0;
            border-radius: 5px;
            border: 1px solid #ddd;
            font-size: 16px;
            box-sizing: border-box;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            border-color: #3498db;
            outline: none;
        }

        button[type="submit"] {
            background-color: #3498db;
            color: white;
            padding: 14px;
            width: 100%;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button[type="submit"]:hover {
            background-color: #2980b9;
        }

        .error-message {
            color: red;
            font-size: 14px;
            text-align: center;
            margin-top: 10px;
        }

        .forgot-password {
            text-align: center;
            margin-top: 10px;
        }

        .forgot-password a {
            color: #3498db;
            text-decoration: none;
        }

        .forgot-password a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <h2>Login</h2>
        <form action="login.php" method="POST">
            <label>Username:</label>
            <input type="text" name="username" required><br>
            <label>Password:</label>
            <input type="password" name="password" required><br>
            <button type="submit" name="login">Login</button>
        </form>

        <!-- Error message (if any) -->
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
                echo '<div class="error-message">Invalid username or password.</div>';
            }

            $stmt->close();
            $conn->close();
        }
        ?>

        <!-- Optional "Forgot Password" link -->
        <div class="forgot-password">
            <a href="forgot_password.php">Forgot Password?</a>
        </div>
    </div>

</body>
</html>
