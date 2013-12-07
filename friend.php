<?PHP error_reporting(-1); ?>
<?php
require_once('mysql.php');
require_once('status.php');

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
        <h1>Manage Friends</h1>
<?php

$status = null;
$status_message = "";

if (isset($_SESSION['pid'])) {
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // User is logged in, check for validity.
    // $pid = $_POST['person'];
    // $eid = $_POST['event'];

    foreach ($_POST as $friend_pid => $friend_level) {
      if ($stmt = $mysqli -> prepare("INSERT INTO friend_of (sharer, viewer, level) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE level=?")) {
        $stmt -> bind_param("ssii", $_SESSION['pid'], $friend_pid, $friend_level, $friend_level);
        if ($stmt -> execute()) {
          $status = Status::Success;
          $status_message = "Changes saved.";
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
    }
  }

  display_status($status, $status_message);

  // Only show information about invitations belonging to this
  // particular user.
  if ($stmt = $mysqli -> prepare(
    "SELECT pid, fname, lname, level
    FROM person
    LEFT OUTER JOIN friend_of ON friend_of.viewer = person.pid
    WHERE (sharer IS NULL
    OR sharer=?)
    AND viewer!=?")) {

    $stmt -> bind_param("ss", $_SESSION['pid'], $_SESSION['pid']);
    $stmt -> execute();
    $stmt -> bind_result($pid, $first_name, $last_name, $level);

?>
          <form method="post">
            <table class='event color-code'>
              <tr>
                <td>Friend</td>
                <td>Friend Level</td>
              </tr>
<?php

    while ($success = ($stmt -> fetch())) {
      // Begin create a row for each friend.
      $viewer_name = $first_name.' '.$last_name;
?>
              <tr>
                <td><?php echo $viewer_name; ?></td>
                <td><input name="<?php echo $pid; ?>" type="text" value="<?php echo $level; ?>"></td>
              </tr>
<?php
    } // End create a row for each friend.
?>
           </table>
           <input type="submit" />
          </form>
<?php
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
