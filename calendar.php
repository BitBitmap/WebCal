<?PHP error_reporting(-1); ?>
<?php
require_once('mysql.php');
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>WebCal</title>
  <!--Stylesheets-->
  <link href="css/bootstrap.css" rel="stylesheet">
  <link href="css/calendar.css" rel="stylesheet">
  <!--Javascript-->
  <script src="http://code.jquery.com/jquery.js"></script>
  <script src="js/bootstrap.min.js"></script>
</head>
<body>
<?php require_once('navbar.php'); ?>
  <div class="container" style="padding-top: 60px;">
    <div class="row">
      <div class="span12">
        <h1>My Calendar</h1>
        <p class="lead">
<?php

if(isset($_SESSION['pid'])) {
  if ($stmt = $mysqli -> prepare("SELECT start_time, duration, description, pid FROM event")) {
    $stmt -> execute();
    $stmt -> bind_result($start_time, $duration, $description, $pid);

    while ($success = ($stmt -> fetch())) {
      echo "<table class='event'>";
      echo "<tr><td>Event start time</td><td>$start_time</td></tr>";
      echo "<tr><td>Duration</td><td>$duration</td></tr>";
      echo "<tr><td>Organizer</td><td>$pid</td></tr>";
      echo "<tr><td>Description</td><td>$description</td></tr>";
      echo "</table>";
    }
  }
} else {
  echo "You need to log in to view this page!";
}
?>
        </p>
      </div>
    </div>
  </div>
</body>
</html>