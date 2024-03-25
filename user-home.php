<?php
session_start(); // Start session to store user data

/* if(isset($_SESSION['email']) && $_SESSION['email'] === 'admin@mail.com') {
    header("Location: index.php");
    exit();
} */

$servername = "localhost";
$username = "root";
$password = "";
$database = "raph";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$first_name = ""; // Initialize username variable

if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
    $stmt = $conn->prepare("SELECT firstName FROM user WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $first_name = $row['firstName'];
    } else {
        echo "No rows returned from the query."; // Debugging message
    }
    $stmt->close();
} else {
    
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $event_type = $_POST['event_type'];
    $event_date = $_POST['date'];
    $venue = $_POST['venue'];
    $contact_number = $_POST['contact_number'];
    $speaker = $_POST['speaker'];

    // Prepare and bind the SQL statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO booking (eventType,date,venue,phone_no,speaker) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $event_type, $event_date, $venue, $contact_number, $speaker);
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
    <title>Home Page</title>
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
    <nav>
        Welcome to freeze flames entertainment
        <a href="logout.php"><button>Logout</button></a> 
    </nav>

    <form class="book" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input type="text" placeholder="event type" name="event_type" class="input" required>
        Event Date
        <input type="date" name="date" class="input" required>
        <input type="text" placeholder="Venue" name="venue" class="input" required>
        <input type="tel" placeholder="0123456789" name="contact_number" class="input" required>
        <input type="radio" name="speaker" value="JBL 2 speaker 1 deck">
        JBL 2 speaker 1 deck
        <input type="radio" name="speaker" value="JBL 4 speaker 1 deck">
        JBL 4 speaker 1 deck<input type="radio" name="speaker" value="JBL 6 speaker 2 deck">
        JBL 6 speaker 2 deck
        <input type="submit" value="Submit" class="input">
    </form>
</body>
</html>
