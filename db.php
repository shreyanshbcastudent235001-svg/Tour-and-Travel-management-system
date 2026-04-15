<?php
// Database connection - update credentials if needed
$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'tour_travel_db';

$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if($conn->connect_error){
    die('Database connection error: ' . $conn->connect_error);
}
?>