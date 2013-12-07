<?PHP error_reporting(-1); ?>
<?php
require_once('mysql.php');
require_once('dates.php');
require_once('scheduler.php');
session_start();
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
    <form method="GET" action=""><?
  if ($begin != $end || $begin != $today || $end != $today) {
    // We want to only display the value if the user specified it
    // themselves.  Otherwise, we want the default value to show.
    display_date_filter($begin_set ? $begin : null, $end_set ? $end : null);
    ?> </form><hr />
  <? }
  // Only show information about invitations belonging to this
  // particular user.
  display_event_tables($mysqli, $_SESSION['pid'], parse_date($begin), parse_date($end));
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

