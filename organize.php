<?PHP error_reporting(-1); ?>
<?php
require_once('mysql.php');
require_once('status.php');

session_start();

// Used to show if a request was successful, or if it resulted in an
// error.
$status = null;
$status_message = "";

// Inserts an event into the database.
function organize_event($mysqli, $pid, $start, $duration, $description) {
  // Start by inserting event...
  if($stmt = $mysqli -> prepare("INSERT INTO event (start_time, duration, description, pid) VALUES (?, ?, ?, ?)")) {
    $stmt -> bind_param("ssss", $start, $duration, $description, $pid);
    if (!$stmt -> execute()) {
      throw new Exception("Execution failed with error: ".$mysqli->error);
    }
  } else {
    throw new Exception("Statement preparation failed with error: ".$mysqli->error);
  }

  // Now get the event ID so we know what to attach the dates to...
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_SESSION['pid'])) {
    // User is logged in, check for validity.
    $pid = $_SESSION['pid'];
    $start = $_POST['start-time'];
    $duration = $_POST['duration'];
    $description = $_POST['description'];
    try {
      organize_event($mysqli, $pid, $start, $duration, $description);
      $status = Status::Success;
      $status_message = "Event successfully created!";
    } catch (Exception $e) {
      $status = Status::Error;
      $status_message = "Unfortunately, we can't process your request!\n";
      $status_message = $status_message.$e;
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
