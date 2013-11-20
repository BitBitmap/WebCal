<?PHP error_reporting(-1); ?>
<?php
require_once('mysql.php');
require_once('status.php');

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_SESSION['pid'])) {
    // User is logged in, check for validity.
    $pid = $_SESSION['pid'];
    $start = $_POST['start-time'];
    $duration = $_POST['duration'];
    $description = $_POST['description'];

    if($stmt = $mysqli -> prepare("INSERT INTO event (start_time, duration, description, pid) VALUES (?, ?, ?, ?)")) {
      $stmt -> bind_param("ssss", $start, $duration, $description, $pid);
      if ($stmt -> execute()) {
        $status = Status::Success;
        $status_message = "Event successfully created!";
      } else {
        $status = Status::Error;
        $status_message = "Unfortunately, we can't process your request!\n";
        $status_message = $status_message."SQL arguments: '$pid', '$start', '$duration', '$description'\n";
        $status_message = $status_message.$mysqli->error;
      }
    }
  } else {
    // User is not logged in...
    $status = Status::Error;
    $status_message = "You have to be logged in to create events!";
  }
}
?>


<!DOCTYPE html>
<html>
<head>
  <?php include_once('header.php'); ?>
  <link href="css/organize.css" rel="stylesheet">
</head>
<body>
<?php require_once('navbar.php'); ?>
  <div class="container" style="padding-top: 60px;">
    <div class="row">
      <div class="span12">
        <h1>Organize Event</h1>
<?php
if (isset($_SESSION['pid'])) {
  // Only allow user to create event if they are logged in.
  display_status($status, $status_message);
?>
          <form method="post">
            <table class="event">
              <tr>
                <td>Start time</td>
                <td>
                  <input name="start-time" type="text" value='<?php echo date('H:m:s', time()); ?>' />
                </td>
              </tr>
              <tr>
                <td>Duration</td>
                <td><input name="duration" type="text" value='01:00:00' /></td>
              </tr>
              <tr>
                <td>Description</td>
                <td>
                  <textarea name="description" id="event-description"></textarea>
                </td>
              </tr>
            </table>
            <input type="submit" value="Submit" />
          </form>
<?php
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
