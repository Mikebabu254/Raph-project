<?php
session_start(); // Start session to store user data

if(!isset($_SESSION['user_id']) && $_SESSION['email'] === 'admin@mail.com') {
    header("Location: index.php"); // Redirect to index.php if user is not logged in or not admin
    exit();
} 

$servername = "localhost";
$username = "root";
$password = "";
$database = "raph";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user details
$user_query = "SELECT * FROM user";
$user_result = $conn->query($user_query);

// Fetch booking details
$booking_query = "SELECT * FROM booking";
$booking_result = $conn->query($booking_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
    <nav>
        Welcome to freezeflames entertainment
        <a href="logout.php"><button class="logout">Logout</button></a> 
    </nav>

    <h2>User Details</h2>
    <table class="detail_table">
        <tr>
            <th>User ID</th>
            <th>First Name</th>
            <th>Second Name</th>
            <th>Email</th>
            <th>join Time</th>
        </tr>
        <?php
        if ($user_result->num_rows > 0) {
            while($row = $user_result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["id"] . "</td>";
                echo "<td>" . $row["firstName"] . "</td>";
                echo "<td>" . $row["secondName"] . "</td>";
                echo "<td>" . $row["email"] . "</td>";
                echo "<td>" . $row["joinTime"] . "</td>";
                // Add more columns as needed
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='3'>No users found</td></tr>";
        }
        ?>
    </table>

    <h2>Booking Details</h2>
    <table border="1">
        <tr>
            <th>Booking ID</th>
            <th>Event Type</th>
            <th>Event Date</th>
            <th>Event Venue</th>
            <th>Phone No</th>
            <th>speaker</th>
        </tr>
        <?php
        if ($booking_result->num_rows > 0) {
            while($row = $booking_result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["id"] . "</td>";
                echo "<td>" . $row["eventType"] . "</td>";
                echo "<td>" . $row["date"] . "</td>";
                echo "<td>" . $row["venue"] . "</td>";
                echo "<td>" . $row["phone_no"] . "</td>";
                echo "<td>" . $row["speaker"] . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='3'>No bookings found</td></tr>";
        }
        ?>
    </table>
</body>
</html>
