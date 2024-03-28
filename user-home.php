<?php
session_start(); // Start session to store user data

if (!isset($_SESSION['email']) || empty($_SESSION['email']) || $_SESSION['email'] === 'admin@mail.com') {
    header("location: index.php");
    exit();
}else
$email = $_SESSION['email'];

$servername = "localhost";
$username = "root";
$password = "";
$database = "raph";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT firstName FROM user WHERE email = '$email'";
$result = $conn->query($sql);

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $first_name = $row['firstName'];
} else {
    // Handle the case where user data is not found
    $first_name = "Admin"; // Default value if first name is not found
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $event_type = $_POST['event_type'];
    $event_date = $_POST['date'];
    $venue = $_POST['venue'];
    $contact_number = $_POST['contact_number'];
    $speaker = $_POST['speaker'];
    $message = $_POST['message'];

    $stmt = $conn->prepare("INSERT INTO pendingbooking (eventType,date,venue,phone_no,speaker,email,message) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $event_type, $event_date, $venue, $contact_number, $speaker, $email, $message);
    $stmt->execute();

    $stmt->close();
}
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
    <nav>
        <h1><h1>Welcome, <?php echo $first_name; ?> to Freezeflames entertainment</h1>
        
        <a href="logout.php"><button class="logout">Logout</button></a> 

    </nav>
    <form class="book" action="user-home.php" method="post">
        <input type="text" placeholder="Event Type" name="event_type" class="input" required>
        <input type="date" name="date" class="input" required>
        <input type="text" placeholder="Venue" name="venue" class="input" required>
        <input type="tel" placeholder="0123456789" name="contact_number" class="input" required>
        <input type="radio" name="speaker" value="JBL 2 speaker 1 deck">
        JBL 2 speaker 1 deck
        <input type="radio" name="speaker" value="JBL 4 speaker 1 deck">
        JBL 4 speaker 1 deck<input type="radio" name="speaker" value="JBL 6 speaker 2 deck">
        JBL 6 speaker 2 deck
        <textarea name="message" id="message" cols="45" rows="10" placeholder="short description of event"></textarea>

        <input type="submit" class="input" value="submit">
    </form>
</body>
</html>
