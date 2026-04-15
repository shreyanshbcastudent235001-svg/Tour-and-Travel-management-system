<?php
session_start();

// Destroy admin session
unset($_SESSION['admin_id']);
session_destroy();

// Redirect to main website homepage
header("Location: http://localhost/tour_travel_project/");
exit;
?>
