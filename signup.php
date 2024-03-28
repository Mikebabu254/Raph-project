<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "raph";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $re_password = $_POST['re_password'];

    // Check if passwords match
    if ($password != $re_password) {
        echo '<script>alert("Passwords do not match");</script>';
    } else {
        try{
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            $stmt = $conn->prepare("INSERT INTO user (firstName, secondName, email, password) VALUES (?,?,?,?)");
            $stmt->bind_param("ssss", $first_name, $last_name, $email, $hashed_password);

            if($stmt->execute() === true){
                echo "<script>alert('you have sign up successfully')</script>";
                header("index.php");
            }else{
                echo "<script>alert('you have sign up unsuccessfully please try again')</script>";
            }
        }catch(mysqli_sql_exception ){
            echo "<script>alert('you have sign up unsuccessfully please try again')</script>";
        } 
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
    <nav>
        Welcome to freeze flames entertainment
    </nav>

    <form class="sign_up" action="signup.php" method="post">
        <input type="text" placeholder="First Name" name="first_name" required>
        <input type="text" placeholder="Last Name" name="last_name" required>
        <input type="email" placeholder="Email" name="email" required>
        <input type="password" placeholder="Password" name="password" required>
        <input type="password" placeholder="Re-write Password" name="re_password" required>
        If you have an account <a href="index.php">login in here</a>
        <input type="submit" value="Sign Up">
    </form>
</body>
</html>