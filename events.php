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

$sqli = "SELECT * FROM pendingbooking WHERE email ='$email'";
$results = $conn->query($sqli);

$sqlii = "SELECT * FROM doneevent WHERE email ='$email'";
$resultss = $conn->query($sqlii);

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
        <h1><h1><?php echo $first_name; ?>, Welcome to Freezeflames entertainment</h1>
        
        <a href="logout.php"><button class="logout">Logout</button></a> 

    </nav>
    <a href="user-home.php"><button class="btnOne">Booking page</button></a>
    
    <table>
        <h2>Your pending Booking event(s)</h2>

        <tr>
            <th>Booking ID</th>
            <th>Event Type</th>
            <th>Event Date</th>
            <th>Event Venue</th>
            <th>speaker</th>
            <th>Message</th>
        </tr>
        <?php
            if($results->num_rows >=1){
                while ($row = $results->fetch_assoc()){
                    echo "<tr>";
                        echo "<td>" . $row["id"] . "</td>";
                        echo "<td>" . $row["eventType"] . "</td>";
                        echo "<td>" . $row["venue"] . "</td>";
                        echo "<td>" . $row["phone_no"] . "</td>";
                        echo "<td>" . $row["speaker"] . "</td>";
                        echo "<td>" . $row["message"] . "</td>";
                    echo "</tr>";
                }
            }else{
                echo "you have not booked any session";
            }
        ?>
    </table>

    <table>
        <h2>Your Done Event</h2>

        <tr>
            <th>Booking ID</th>
            <th>Event Type</th>
            <th>Event Date</th>
            <th>Event Venue</th>
            <th>speaker</th>
            <th>Message</th>
        </tr>
        <?php
            if($resultss->num_rows >=1){
                while ($rows = $resultss->fetch_assoc()){
                    echo "<tr>";
                        echo "<td>" . $rows["id"] . "</td>";
                        echo "<td>" . $rows["eventType"] . "</td>";
                        echo "<td>" . $rows["venue"] . "</td>";
                        echo "<td>" . $rows["phone_no"] . "</td>";
                        echo "<td>" . $rows["speaker"] . "</td>";
                        echo "<td>" . $rows["message"] . "</td>";
                    echo "</tr>";
                }
            }else{
                echo "Non of your booked event has taken place";
            }
        ?>
    </table>
</body>
</html>
