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
  <link href="css/base.css" rel="stylesheet">
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
<?php

if (isset($_SESSION['pid'])) {
  // Only show information about invitations belonging to this
  // particular user.
  if ($stmt = $mysqli -> prepare("SELECT start_time, duration, description, event.pid, response, visibility FROM event JOIN invited USING (eid) WHERE invited.pid=?")) {
    $stmt -> bind_param("s", $_SESSION['pid']);
    $stmt -> execute();
    $stmt -> bind_result($start_time, $duration, $description, $organizer_pid, $response, $visibility);

    while ($success = ($stmt -> fetch())) {
      // Begin create a table for each event.
?>
          <table class='event color-code'>
            <tr>
              <td>Event start time</td>
              <td><?php echo $start_time; ?></td>
            </tr>
            <tr>
              <td>Duration</td>
              <td><?php echo $duration; ?></td>
            </tr>
            <tr>
              <td>Organizer</td>
              <td><?php echo $organizer_pid; ?></td>
            </tr>
            <tr>
              <td>Description</td>
              <td><?php echo $description; ?></td>
            </tr>
            <tr>
              <td>Response</td>
              <td><?php echo $response; ?></td>
            </tr>
            <tr>
              <td>Visibility</td>
              <td><?php echo $visibility; ?></td>
            </tr>
          </table>
<?php
    } // End create a table for each event.
  }
} else {
  // User is not logged in.
  echo "You need to log in to view this page!";
}
?>
      </div>
    </div>
  </div>
</body>
</html>
