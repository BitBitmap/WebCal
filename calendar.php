<?PHP error_reporting(-1); ?>
<?php
require_once('mysql.php');
require_once('dates.php');
session_start();

/*
Displays a date filter.

$begin and $end are the initial values of the filter.
*/
function display_date_filter($begin, $end) {
  ?>
  <div class="filter container">
    <div class="row">
      <div class="col-md-12">
        <p>Only show events between</p>
      </div>
    </div>
    <div class="row">
      <form method="GET" action="">
        <div class="col-md-2">
          <input type="text" class="datepicker" name="begin" placeholder="The beginning of time" <?php if ($begin) echo "value='$begin'"; ?> />
        </div>
        <div class="col-md-2">
          <input type="text" class="datepicker" name="end" placeholder="infinity and beyond" <?php if ($end) echo "value='$end'"; ?> />
        </div>
        <div class="col-md-1">
          <input type="submit" value="Filter" />
        </div>
      </form>
    </div>
  </div>
  <?php
}

function display_event_tables($mysqli, $pid, $begin, $end) {
  if (!($stmt = $mysqli -> prepare("SELECT eid, start_time, duration, edate, description, event.pid, response, visibility FROM event NATURAL JOIN eventdate JOIN invited USING (eid) WHERE invited.pid=? AND ? <= edate AND edate <= ? ORDER BY edate, start_time"))) {
    throw new Exception("Preparing statement failed: ".$mysqli->error);
  }
  if (!$stmt->bind_param('sss', $pid, $begin, $end)) {
    throw new Exception("Binding argument failed: ".$mysqli->error);
  }
  if (!$stmt->execute()) {
    throw new Exception("Execution failed: ".$mysqli->error);
  }
  if (!$stmt->store_result()) {
    throw new Exception("Store result failed: ".$mysqli->error);
  }
  $stmt -> bind_result($eid, $start_time, $duration, $date, $description, $organizer_pid, $response, $visibility);

  for ($i = 0; $i < $stmt->num_rows; ++$i) {
    if (!$stmt -> fetch()) {
      throw new Exception("Error fetching: ".$mysqli->error);
    }
    try {
      display_row($eid, $start_time, $duration, $description, $organizer_pid, $response, $visibility, $date);
    } catch (Exception $e) {
      // Display the error message so we can debug it...
      ?>
      <div class='error'>
        <p>Oops! This is embarassing. Something bad happened occurred...</p>
        <p><?php echo $e; ?></p>
      </div>
      <?php
    }
  }

  if ($stmt->num_rows == 0) {
  ?>
    <p>You do not have any events to go to.</p>
  <?php
  }
}

function display_row($eid, $start_time, $duration, $description, $organizer_pid, $response, $visibility, $date) {
?>
  <table class='event color-code'>
    <tr>
      <td>Date</td>
      <td><?php echo $date; ?></td>
    </tr>
    <tr>
      <td>Event <?php echo $eid; ?> start time</td>
      <td><?php echo $start_time; ?></td>
    </tr>
    <tr>
      <td>Duration</td>
      <td><?php echo $duration; ?></td>
    </tr>
    <tr>
      <td>Description</td>
      <td><?php echo htmlentities($description); ?></td>
    </tr>
    <tr>
      <td>Organizer</td>
      <td><?php echo $organizer_pid; ?></td>
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
}


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
        <h1>My Calendar</h1>
      </div>
<?php
if (isset($_SESSION['pid'])) {
  ?> <hr /> <?
  $begin_set = (isset($_GET['begin']) && $_GET['begin'] != "");
  $end_set = (isset($_GET['end']) && $_GET['end'] != "");

  $begin = $begin_set ? $_GET['begin'] : DATE_MIN;
  $end = $end_set ? $_GET['end'] : DATE_MAX;

  // We want to only display the value if the user specified it
  // themselves.  Otherwise, we want the default value to show.
  display_date_filter($begin_set ? $begin : null, $end_set ? $end : null);
  ?> <hr /> <?
  // Only show information about invitations belonging to this
  // particular user.
  display_event_tables($mysqli, $_SESSION['pid'], $begin, $end);
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
