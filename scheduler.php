<?php
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
      <div class="col-md-2">
        <input type="text" class="datepicker" name="begin" placeholder="The beginning of time" <?php if ($begin) echo "value='$begin'"; ?> />
      </div>
      <div class="col-md-2">
        <input type="text" class="datepicker" name="end" placeholder="infinity and beyond" <?php if ($end) echo "value='$end'"; ?> />
      </div>
      <div class="col-md-1">
        <button type="submit" class="btn btn-primary">Filter</button>
      </div>
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

function display_own_events($mysqli, $pid, $begin, $end) {
  if (!($stmt = $mysqli -> prepare("SELECT eid, start_time, duration, edate, description, event.pid, response, visibility FROM event NATURAL JOIN eventdate JOIN invited USING (eid) WHERE event.pid=? AND ? <= edate AND edate <= ? ORDER BY edate, start_time"))) {
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
    <p>You do not have any events created.</p>
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

