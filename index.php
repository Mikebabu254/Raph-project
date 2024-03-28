<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$database = "raph";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];
    
    $email = mysqli_real_escape_string($conn, $email);

    $sql = "SELECT password FROM user WHERE email = '$email'";
    
    // Execute the SQL query
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $hashed_password = $row['password'];

        if (password_verify($password, $hashed_password)) {
            $_SESSION['email'] = $email;
            
            if ($email === 'admin@mail.com') {
                header("location: admin.php");
            } else {
                header("location: user-home.php");
            }
            exit();
        } else {
            echo "<script>alert('Login unsuccessful')</script>";
        }
    } else {
        echo "<script>alert('Login unsuccessful')</script>";
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
        <h1>Welcome to freeze flames entertainment</h1>
    </nav>
    <form class="signin" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input type="email" name="email" placeholder="email" required>
        <input type="password" name="password" placeholder="password" required>
        <p>If you don't have an account, <a href="signup.php">create an account here</a></p>
        <input type="submit" value="Login" class="btn">
    </form>
</body>
</html>
