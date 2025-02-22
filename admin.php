<?php
session_start();
include "db.php";

// Redirect if not an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Event Management</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <h1>Admin Panel</h1>

    <!-- Navigation -->
    <nav>
        <a href="index.php">Home</a>
        <a href="admin.php">Admin Panel</a>
        <span>Welcome, <?php echo $_SESSION['name']; ?> (Admin)</span>
        <a href="logout.php">Logout</a>
    </nav>

    <!-- Add Event Form -->
    <div class="form-container">
        <h2>Add New Event</h2>
        <form action="admin.php" method="POST">
            <<input type="text" name="title" value="<?php echo htmlspecialchars($row['title']); ?>" required> <br>
            <textarea name="description"><?php echo $row['description']; ?></textarea><br>
            <input type="date" name="date" required><br>
            <input type="text" name="venue" placeholder="Venue" required><br>
            <input type="number" name="available_seats" placeholder="Available Seats" required><br>
            <button type="submit" name="add_event">Add Event</button>
        </form>
    </div>

    <!-- Display Existing Events -->
    <div class="event-container">
        <h2>Manage Events</h2>
        <?php
        $result = $conn->query("SELECT * FROM events");
        while ($row = $result->fetch_assoc()):
        ?>
            <div class="event-card">
                <h3><?php echo $row['title']; ?></h3>
                <p><?php echo $row['description']; ?></p>
                <p><strong>Date:</strong> <?php echo $row['date']; ?></p>
                <p><strong>Venue:</strong> <?php echo $row['venue']; ?></p>
                <p><strong>Seats Available:</strong> <?php echo $row['available_seats']; ?></p>

                <!-- Edit Event Form -->
                <form action="admin.php" method="POST">
                    <input type="hidden" name="event_id" value="<?php echo $row['id']; ?>">
                    <input type="text" name="title" value="<?php echo $row['title']; ?>" required><br>
                    <textarea name="description"><?php echo $row['description']; ?></textarea><br>
                    <input type="date" name="date" value="<?php echo $row['date']; ?>" required><br>
                    <input type="text" name="venue" value="<?php echo $row['venue']; ?>" required><br>
                    <input type="number" name="available_seats" value="<?php echo $row['available_seats']; ?>" required><br>
                    <button type="submit" name="edit_event">Update</button>
                </form>

                <!-- Delete Event Button -->
                <form action="admin.php" method="POST">
                    <input type="hidden" name="event_id" value="<?php echo $row['id']; ?>">
                    <button type="submit" name="delete_event" class="delete-btn">Delete</button>
                </form>
            </div>
        <?php endwhile; ?>
    </div>

    <footer>&copy; <?php echo date("Y"); ?> Admin Panel</footer>

</body>
</html>

<?php
// Handle Event Creation
if (isset($_POST['add_event'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $date = $_POST['date'];
    $venue = $_POST['venue'];
    $available_seats = $_POST['available_seats'];

    $stmt = $conn->prepare("INSERT INTO events (title, description, date, venue, available_seats) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssi", $title, $description, $date, $venue, $available_seats);
    
    if ($stmt->execute()) {
        echo "<script>alert('Event added successfully!'); window.location='admin.php';</script>";
    } else {
        echo "<script>alert('Error adding event!');</script>";
    }
}

// Handle Event Update
if (isset($_POST['edit_event'])) {
    $event_id = $_POST['event_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $date = $_POST['date'];
    $venue = $_POST['venue'];
    $available_seats = $_POST['available_seats'];

    $stmt = $conn->prepare("UPDATE events SET title=?, description=?, date=?, venue=?, available_seats=? WHERE id=?");
    $stmt->bind_param("ssssii", $title, $description, $date, $venue, $available_seats, $event_id);

    if ($stmt->execute()) {
        echo "<script>alert('Event updated successfully!'); window.location='admin.php';</script>";
    } else {
        echo "<script>alert('Error updating event!');</script>";
    }
}

// Handle Event Deletion
if (isset($_POST['delete_event'])) {
    $event_id = $_POST['event_id'];

    $stmt = $conn->prepare("DELETE FROM events WHERE id=?");
    $stmt->bind_param("i", $event_id);

    if ($stmt->execute()) {
        echo "<script>alert('Event deleted successfully!'); window.location='admin.php';</script>";
    } else {
        echo "<script>alert('Error deleting event!');</script>";
    }
}
?>
