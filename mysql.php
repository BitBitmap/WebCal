<?php
$mysqli = new mysqli("localhost", "webcal", "Hf5S42dScW5ZaHD9", "webcal");

if (mysqli_connect_errno()) {
  echo "Connection Failed: " . mysqli_connect_errno();
  exit();
}
?>
