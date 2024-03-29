<?php
session_start(); // Start session to store user data

if (!isset($_SESSION['email']) || empty($_SESSION['email']) || $_SESSION['email'] !== 'admin@mail.com') {
    header("location: index.php");
    exit();
}
$email = $_SESSION['email'];

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

// Fetch pending booking details
$pending_booking_query = "SELECT * FROM pendingbooking";
$pending_booking_result = $conn->query($pending_booking_query);

// Fetch done booking details
$done_booking_query = "SELECT * FROM doneevent";
$done_booking_result = $conn->query($done_booking_query);

$sql = "SELECT firstName FROM user WHERE email = '$email'";
$result = $conn->query($sql);

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $first_name = $row['firstName'];
} else {
    // Handle the case where user data is not found
    $first_name = "Admin"; // Default value if first name is not found
}

if(isset($_POST['done_booking_id'])) {
    $done_booking_id = $_POST['done_booking_id'];
    // Move the booking from pending bookings to done bookings
    $move_to_done_query = "INSERT INTO doneevent SELECT * FROM pendingbooking WHERE id = $done_booking_id";
    if ($conn->query($move_to_done_query) === TRUE) {
        // Delete the booking from pending bookings
        $delete_from_pending_query = "DELETE FROM pendingbooking WHERE id = $done_booking_id";
        if ($conn->query($delete_from_pending_query) === TRUE) {
            // Redirect to avoid re-submitting the form
        } else {
            echo "Error deleting record: " . $conn->error;
        }
    } else {
        echo "Error moving record: " . $conn->error;
    }
}
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
        <h1>Welcome, <?php echo $first_name; ?> to Freezeflames entertainment</h1>
        <a href="logout.php"><button class="logout">Logout</button></a> 
    </nav>
    <h2>User Details</h2>
    <table class="detail_table">
        <tr>
            <th>User ID</th>
            <th>First Name</th>
            <th>Second Name</th>
            <th>Email</th>
            <th>Join Time</th>
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

    <h2>Pending Booking Details</h2>
    <table border="1">
        <tr>
            <th>Booking ID</th>
            <th>Event Type</th>
            <th>Event Date</th>
            <th>Event Venue</th>
            <th>Phone No</th>
            <th>speaker</th>
            <th>Message</th>
            <th>Pending</th> 
        </tr>
        <?php
        if ($pending_booking_result->num_rows > 0) {
            while($row = $pending_booking_result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["id"] . "</td>";
                echo "<td>" . $row["eventType"] . "</td>";
                echo "<td>" . $row["date"] . "</td>";
                echo "<td>" . $row["venue"] . "</td>";
                echo "<td>" . $row["phone_no"] . "</td>";
                echo "<td>" . $row["speaker"] . "</td>";
                echo "<td>" . $row["message"] . "</td>";
                echo "<td><form method='post'><input type='hidden' name='done_booking_id' value='" . $row["id"] . "'><button type='submit'>Done</button></form></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='3'>No bookings found</td></tr>";
        }
        ?>
    </table>

    <h2>Done Event</h2>
    <table border="1">
    <tr>
            <th>Booking ID</th>
            <th>Event Type</th>
            <th>Event Date</th>
            <th>Event Venue</th>
            <th>Phone No</th>
            <th>speaker</th>
            <th>Message</th>
            <th>Done</th> 
        </tr>
        <?php
        if ($done_booking_result->num_rows > 0) {
            while($row = $done_booking_result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["id"] . "</td>";
                echo "<td>" . $row["eventType"] . "</td>";
                echo "<td>" . $row["date"] . "</td>";
                echo "<td>" . $row["venue"] . "</td>";
                echo "<td>" . $row["phone_no"] . "</td>";
                echo "<td>" . $row["speaker"] . "</td>";
                echo "<td>" . $row["message"] . "</td>";
                echo "<td>Done</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='3'>No bookings found</td></tr>";
        }
        ?>
    </table>
</body>
</html>
