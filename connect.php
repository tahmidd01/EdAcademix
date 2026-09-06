<?php
$host = 'localhost'; // Database host
$dbname = 'edacademix'; // Name of the database
$username = 'root'; // Database username
$password = ''; // Database password

// Create a new MySQLi connection
$conn = new mysqli('localhost: 3306', 'root', '', 'edacademix');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>
