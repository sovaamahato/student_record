<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
</head>
<body>
    <h2>Signup</h2>
    <form action="signup.php" method="POST">
        <label>Username:</label>
        <input type="text" name="username" required><br>
        <label>Email:</label>
        <input type="email" name="email" required><br>
        <label>Password:</label>
        <input type="password" name="password" required><br>
        <label>Phone Number:</label>
        <input type="text" name="phone_number" required><br>
        <button type="submit" name="submit">Sign Up</button>
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        include 'config.php';

        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash the password
        $phone_number = $_POST['phone_number'];

        // Insert data into the users table
        $sql = "INSERT INTO users (username, email, password, phone_number) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            die("SQL error: " . $conn->error); // Output the exact error
        }

        $stmt->bind_param("ssss", $username, $email, $password, $phone_number);

        if ($stmt->execute()) {

            header("Location: login.php");
            exit; 
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
        $conn->close();
    }
    ?>
</body>
</html>
