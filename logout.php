<?php
session_start();

// Remove all session variables
session_unset();

// Destroy the session
session_destroy();

// direct to student login (or a landing page)
header("Location: home.php");
exit();
