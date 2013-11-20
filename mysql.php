<?php

$mysqli = new mysqli("localhost", "baka", "bakabaka", "baka");

if(mysqli_connect_errno()){
	echo "Connection Failed: " . mysqli_connect_errno();
	exit();
}
?>