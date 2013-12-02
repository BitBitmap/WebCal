<?PHP error_reporting(-1); ?>
<?php
require_once('mysql.php');
session_start();
?>

<!DOCTYPE html>
<html>
<head>
  <?php require_once('header.php'); ?>
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

    $rows = 0;
    while ($success = ($stmt -> fetch())) {
      ++$rows;
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

    if ($rows == 0) {
      ?>
        <p>You do not have any events to go to.</p>
      <?php
    }
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
