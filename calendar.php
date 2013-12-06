<?PHP error_reporting(-1); ?>
<?php
require_once('mysql.php');
session_start();

function fetch_dates($mysqli, $eid) {
  if (!($date_stmt = $mysqli->prepare("SELECT edate FROM eventdate WHERE eid=?"))) {
    throw new Exception("Preparing statement failed: ".$mysqli->error);
  }
  if (!$date_stmt->bind_param('s', $eid)) {
    throw new Exception("Binding argument failed: ".$mysqli->error);
  }

  if (!$date_stmt->bind_result($date)) {
    throw new Exception("Binding result failed: ".$mysqli->error);
  }

  if (!$date_stmt->execute()) {
    throw new Exception("Execution failed: ".$mysqli->error);
  }

  if (!$date_stmt->store_result()) {
    throw new Exception("Store result failed: ".$mysqli->error);
  }

  $dates = array();
  $rowNumber = 0;
  for ($i = 0; $i < $date_stmt->num_rows; ++$i) {
    if (!$date_stmt->fetch()) {
      throw new Exception("Fetching failed: ".$mysql->error);
    }
    $dates[$rowNumber] = $date;
  }

  return $dates;
}

function display_event_tables($mysqli, $pid) {
  if (!($stmt = $mysqli -> prepare("SELECT eid, start_time, duration, description, event.pid, response, visibility FROM event JOIN invited USING (eid) WHERE invited.pid=?"))) {
    throw new Exception("Preparing statement failed: ".$mysqli->error);
  }
  if (!($date_stmt = $mysqli->prepare("SELECT edate FROM eventdate WHERE eid=?"))) {
    throw new Exception("Preparing statement failed: ".$mysqli->error);
  }
  if (!$stmt->bind_param('i', $pid)) {
    throw new Exception("Binding argument failed: ".$mysqli->error);
  }
  if (!$stmt->execute()) {
    throw new Exception("Execution failed: ".$mysqli->error);
  }
  if (!$stmt->store_result()) {
    throw new Exception("Store result failed: ".$mysqli->error);
  }
  $stmt -> bind_result($eid, $start_time, $duration, $description, $organizer_pid, $response, $visibility);

  $rows = array();
  for ($i = 0; $i < $stmt->num_rows; ++$i) {
    if (!$stmt -> fetch()) {
      throw new Exception("Error fetching: ".$mysqli->error);
    }
    $rows[$i] = array(
        'eid' => $eid,
        'start_time' => $start_time,
        'duration' => $duration,
        'description' => $description,
        'organizer_pid' => $organizer_pid,
        'response' => $response,
        'visibility' => $visibility
      );
  }

  for ($i = 0; $i < $stmt->num_rows; ++$i) {
    try {
      $row = $rows[$i];
      $dates = fetch_dates($mysqli, $row['eid']);
      display_row($row['eid'], $row['start_time'], $row['duration'], $row['description'], $row['organizer_pid'], $row['response'], $row['visibility'], $dates);
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

function display_row($eid, $start_time, $duration, $description, $organizer_pid, $response, $visibility, $dates) {
?>
  <table class='event color-code'>
    <tr>
      <td>Event <?php echo $eid; ?> start time</td>
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
      <td><?php echo htmlentities($description); ?></td>
    </tr>
    <tr>
      <td>Dates</td>
      <td><?php echo JSON_encode($dates); ?></td>
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
  display_event_tables($mysqli, $_SESSION['pid']);
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
