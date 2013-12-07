<?PHP error_reporting(-1); ?>
<?php
require_once('mysql.php');
require_once('dates.php');
require_once('scheduler.php');
session_start();

function retrieve_friend_names($mysqli) {
  $friends = array();
  if (!($stmt = $mysqli -> prepare("SELECT pid, fname, lname FROM person"))) {
    throw new Exception("Preparing statement failed: ".$mysqli->error);
  }
  if (!$stmt->execute()) {
    throw new Exception("Execution failed: ".$mysqli->error);
  }
  if (!$stmt->store_result()) {
    throw new Exception("Store result failed: ".$mysqli->error);
  }
  if (!$stmt -> bind_result($pid, $first_name, $last_name)) {
    throw new Exception("Bind result failed: ".$mysqli->error);
  }
  for ($i = 0; $i < $stmt->num_rows; ++$i) {
    if (!$stmt->fetch()) {
      throw new Exception("Error fetching: ".$mysqli->error);
    }
    $friends[$i] = array("pid"=>$pid, "first_name"=>$first_name, "last_name"=>$last_name);
  }
  return $friends;
}

function display_friend_filter($friends) {
  ?><div class="btn-group"><?php
  foreach ($friends as $friend) {
    ?>
      <button type="submit" name="pid" value="<?php echo $friend['pid']; ?>" class="btn btn-default"><?php echo $friend['first_name']." ".$friend['last_name']; ?></button>
    <?php
  }
  ?></div><?php
}


// We must generate the URL in this format, since we're comparing
// strings to figure out if the user wanted to get today's schedule. Our
// javascript date-selection utility escapes the '/' character, so we
// can safely generate them here. This isn't a guaranteed approach,
// since the user can manually type in the '/' characters themselves.
$today = date("Y/m/d");
$begin_set = (isset($_GET['begin']) && $_GET['begin'] != "");
$end_set = (isset($_GET['end']) && $_GET['end'] != "");

$begin = $begin_set ? $_GET['begin'] : DATE_MIN;
$end = $end_set ? $_GET['end'] : DATE_MAX;

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <?php require_once('header.php'); ?>
</head>
<body>
  <?php require_once('navbar.php'); ?>
  <div class="container" style="padding-top: 60px;">
    <div class="row">
      <div class="span12">
        <h1>
          <?php
          // This will only work if we get here from today.php, or if
          // the user manually types in the dates with '/' as
          // separators. This is because the date selection utility we
          // use will escape the '/' characters.
          if ($begin == $end && $begin == $today) {
          ?>
            Today's Calendar
          <?php } else { ?>
            Calendar
          <?php } ?>
        </h1>
      </div>
<?php
if (isset($_SESSION['pid'])) {
  ?>
  <hr />
    <form method="GET" action="">
  <?
  if ($begin != $end || $begin != $today || $end != $today) {
    // We want to only display the value if the user specified it
    // themselves.  Otherwise, we want the default value to show.
    display_date_filter($begin_set ? $begin : null, $end_set ? $end : null);
    ?> <hr />
  <? }
  ?><p>Select Schedule to View</p><?php
  $friends = retrieve_friend_names($mysqli);
  display_friend_filter($friends);
  ?>
  <hr />
  </form>
  <?php
  // Only show information about invitations belonging to this
  // particular user.
  display_event_tables($mysqli, $_GET['pid'], parse_date($begin), parse_date($end));
} else {
  // User is not logged in.
  echo "You need to log in to view this page!";
}
?>
      </div>
    </div>
  </div>
  <?php enable_datepicker(); ?>
</body>
</html>
