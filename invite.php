<?PHP error_reporting(-1); ?>
<?php
require_once('mysql.php');
require_once('status.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_SESSION['pid'])) {
    // User is logged in, check for validity.
    $pid = $_POST['person'];
    $eid = $_POST['event'];

    if($stmt = $mysqli -> prepare("INSERT INTO invited (pid, eid) VALUES (?, ?)")) {
      $stmt -> bind_param("si", $pid, $eid);
      if ($stmt -> execute()) {
        $status = Status::Success;
        $status_message = "Invitation was successfully sent!";
        echo 'sd';
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
  <meta charset="utf-8">
  <title>WebCal</title>
  <!--Stylesheets-->
  <link href="css/bootstrap.css" rel="stylesheet">
  <link href="css/base.css" rel="stylesheet">
  <link href="css/organize.css" rel="stylesheet">
  <!--Javascript-->
  <script src="http://code.jquery.com/jquery.js"></script>
  <script src="js/bootstrap.min.js"></script>
</head>
<body>
<?php require_once('navbar.php'); ?>
  <div class="container" style="padding-top: 60px;">
    <div class="row">
      <div class="span12">
        <h1>Invite Friends</h1>
<?php
if (isset($_SESSION['pid'])) {
  // Only allow user to create event if they are logged in.
  display_status($status, $status_message);
?>
          <form method="post">
            <table class="event">
              <tr>
                <td>Person to Invite</td>
                <td>
                  <input name="person" type="text" />
                </td>
              </tr>
              <tr>
                <td>Event</td>
                <td><input name="event" type="text" /></td>
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
