<?PHP error_reporting(-1); ?>
<?php
require_once('mysql.php');
require_once('dates.php');
require_once('status.php');
require_once('scheduler.php');
session_start();

// Used to show if a request was successful, or if it resulted in an
// error.
$status = null;
$status_message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_SESSION['pid'])) {
    // User is logged in, check for validity.
    $eid = $_POST['event'];
    $pid = $_SESSION['pid'];
    if ($stmt = $mysqli -> prepare("UPDATE event SET valid = 0 WHERE eid = ? AND pid = ?")) {
      $stmt -> bind_param("is", $eid, $pid);
      if ($stmt -> execute()) {
        $status = Status::Success;
        $status_message = "Event was sucessfully canceled.";
      } else {
        $status = Status::Error;
        $status_message = "Unfortunately, we can't process your request!\n";
        $status_message = $status_message."SQL arguments: '$pid, $eid'\n";
        $status_message = $status_message.$mysqli->error;
      }
    } else {
      $status = Status::Error;
      $status_message = "Unfortunately, we can't process your request!\n";
      $status_message = $status_message."SQL arguments: '$pid, $eid'\n";
      $status_message = $status_message.$mysqli->error;
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
  <?php require_once('header.php') ?>
  <link href="css/organize.css" rel="stylesheet">
</head>
<body>
  <?php require_once('navbar.php'); ?>
  <div class="container" style="padding-top: 60px;">
    <div class="row">
      <div class="span12">
        <h1>Cancel Event</h1>
        <?php
        if (isset($_SESSION['pid'])) {
  // Only allow user to create event if they are logged in.
          display_status($status, $status_message);
          display_own_events($mysqli, $_SESSION['pid'], DATE_MIN, DATE_MAX);
          ?>
          <form method="post">
            <table class="event">
              <tr>
                <td>Event ID</td>
                <td>
                  <input name="event" type="text" />
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