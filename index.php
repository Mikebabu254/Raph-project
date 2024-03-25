<?php
session_start(); // Start session to store user data

$servername = "localhost";
$username = "root";
$password = "";
$database = "raph";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email']) && isset($_POST['password'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM user WHERE email=? AND password=?");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $_SESSION['user_id'] = $row['id']; // Store user ID in session

        // Regenerate session ID to prevent session fixation
        session_regenerate_id(true);

        // Check if the email is admin's email
        if ($email !== 'admin@mail.com') {
            header("Location: user-home.php"); // Redirect admin to admin.php
            exit();
        } else {
            header("Location: admin.php"); // Redirect regular users to home.php
            exit();
        }
    } else {
        echo '<script>alert("Invalid email or password. Please try again.");</script>';
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
    <nav>
        Welcome to freeze flames entertainment
    </nav>
    <form class="signin" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input type="email" name="email" placeholder="email" required>
        <input type="password" name="password" placeholder="password" required>
        <?php if(isset($error_message)) { ?>
            <div><?php echo $error_message; ?></div>
        <?php } ?>
        <p>If you don't have an account, <a href="signup.php">create an account here</a></p>
        <input type="submit" value="Login">
    </form>
</body>
</html>
