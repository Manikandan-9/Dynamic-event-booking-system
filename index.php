<?php
session_start();
include "db.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Booking System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <h1>Event Booking System</h1>

    <!-- Navigation -->
    <nav>
        <a href="index.php">Home</a>
        <?php if (isset($_SESSION['user_id'])): ?>
            <?php if ($_SESSION['role'] == 'admin'): ?>
                <a href="admin.php">Admin Panel</a>
            <?php endif; ?>
            <span>Welcome, <?php echo $_SESSION['name']; ?></span>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        <?php endif; ?>
    </nav>

    <!-- Event Listing -->
    <div class="event-container">
        <?php
        $result = $conn->query("SELECT * FROM events");
        while ($row = $result->fetch_assoc()):
        ?>
            <div class="event-card">
            <h3><?php echo htmlspecialchars($row['title']); ?></h3>
            <p><?php echo htmlspecialchars($row['description']); ?></p>
            <p><strong>Date:</strong> <?php echo htmlspecialchars($row['date']); ?></p>
            <p><strong>Venue:</strong> <?php echo htmlspecialchars($row['venue']); ?></p>

                <p><strong>Seats Available:</strong> <?php echo $row['available_seats']; ?></p>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <?php if ($row['available_seats'] > 0): ?>
                        <button class="book-btn" data-event-id="<?php echo $row['id']; ?>">Book Now</button>
                        <p id="status-<?php echo $row['id']; ?>"></p>

                    <?php else: ?>
                        <p class="sold-out">Sold Out</p>
                    <?php endif; ?>
                <?php else: ?>
                    <p><a href='login.php'>Login to book</a></p>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    </div>

    <!-- Footer -->
    <footer>&copy; <?php echo date("Y"); ?> Event Booking System</footer>
    
    <script>
    document.querySelectorAll('.book-btn').forEach(button => {
        button.addEventListener('click', function() {
            let eventId = this.getAttribute('data-event-id');
            let statusMessage = document.getElementById(`status-${eventId}`);

            fetch('book_event.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `event_id=${eventId}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === "success") {
                    statusMessage.innerHTML = `<span style="color: green;">✔ ${data.message}</span>`;
                    this.disabled = true;
                    this.innerText = "Booked";
                } else {
                    statusMessage.innerHTML = `<span style="color: red;">✖ ${data.message}</span>`;
                }
            })
            .catch(error => console.error("Error:", error));
        });
    });
    </script>


</body>
</html>
