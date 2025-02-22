# Dynamic Event Booking System

A simple event booking system where users can browse, book, and manage their event registrations. Admins can add, edit, and delete events while managing bookings.

---

## Features

- User Registration & Login (PHP & MySQL)
- Event Management (Admins can create, edit, and delete events)
- Event Booking System (Users can book & cancel events)
- AJAX-Based Event Booking (No Page Refresh)
- Secure Password Hashing (bcrypt)
- SQL Injection & XSS Protection
- Responsive UI with Modern Styling

---

## Setup Instructions

### 1. Install XAMPP or WAMP
- Download and install [XAMPP](https://www.apachefriends.org/index.html) or [WAMP](https://www.wampserver.com/en/).
- Start Apache and MySQL in the control panel.

### 2. Clone the Repository
Run the following command in your terminal to clone the project:
```sh
git clone https://github.com/your-username/event-booking-system.git
```
Then, move the project folder to:
```sh
XAMPP: htdocs/
WAMP: www/
```

### 3. Create & Import the Database
1. Open **phpMyAdmin** (`http://localhost/phpmyadmin/`).
2. Create a new database called **`event_booking`**.
3. Click **Import** and select the `event_booking.sql` file from the project.
4. Click **Go** to import the database.

### 4. Configure Database Connection
- Open `db.php` and check the database credentials:
```php
$servername = "localhost";
$username = "root";  // Change if needed
$password = "";      // Change if needed
$database = "event_booking";
```
- Update **`username`** and **`password`** if required.

### 5. Start the Server & Access the Project
- Open a browser and go to:
  ```sh
  http://localhost/event-booking-system/index.php
  ```
- You can now register, log in, and book events.

---

## Admin Account Setup (Manually Add Admin to Database)

By default, new users are registered as regular users. You must manually add an admin account in the database.

### Make a User an Admin (Run this in phpMyAdmin SQL Query):
```sql
UPDATE users SET role = 'admin' WHERE email = 'your-admin-email@example.com';
```
Replace `'your-admin-email@example.com'` with the email of a registered user.

---

## Sample Login Credentials

### Regular User:
- **Email:** `user@example.com`
- **Password:** `password123`

### Admin User (You Must Add Manually)
- **Email:** `admin@example.com`
- **Password:** `admin123` (Use the SQL command above to make this user an admin)

---

---

## Technology Stack

- **Frontend:** HTML, CSS, JavaScript
- **Backend:** PHP, MySQL
- **Security:** Password Hashing (bcrypt), SQL Injection Prevention
- **AJAX:** Fetch API for event booking without page refresh

---

## Contributing

Feel free to fork the repository and submit pull requests. Suggestions and improvements are always welcome.

---

## License

This project is open-source. You can use and modify it as needed.
