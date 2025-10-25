<?php
session_start();
if (!isset($_SESSION['tutor_id'])) {
  header("Location: tutor_login.php");
  exit();
}
