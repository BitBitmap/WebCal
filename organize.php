<?PHP error_reporting(-1); ?>
<?php
require_once('mysql.php');
require_once('status.php');
require_once('dates.php');

session_start();

// Used to show if a request was successful, or if it resulted in an
// error.
$status = null;
$status_message = "";

function get_last_insert_id($mysqli) {
  if (!($stmt = $mysqli->prepare("SELECT LAST_INSERT_ID()"))) {
    throw new Exception("Statement preparation failed with error: ".$mysqli->error);
  }
  if (!$stmt->execute()) {
    throw new Exception("Execution failed with error: ".$mysqli->error);
  }
  if (!$stmt->bind_result($id)) {
    throw new Exception("Result binding failed with error: ".$mysqli->error);
  }
  if (!$stmt->fetch()) {
    throw new Exception("Fetching failed with error: ".$mysqli->error);
  }
  if (!$stmt->close()) {
    throw new Exception("Closing eid fetching failed with error: ".$mysqli->error);
  }
  return $id;
}

/* Inserts an event into the database.

$dates is an array of date strings.

** POTENTIAL SECURITY RISK **
Be sure to sanitize $dates before running this function!
*/
function organize_event($mysqli, $pid, $start, $duration, $description, $dates) {
  // Start by inserting event...
  if($stmt = $mysqli -> prepare("INSERT INTO event (start_time, duration, description, pid) VALUES (?, ?, ?, ?)")) {
    $stmt -> bind_param("ssss", $start, $duration, $description, $pid);
    if (!$stmt -> execute()) {
      throw new Exception("Execution failed with error: ".$mysqli->error);
    }
  } else {
    throw new Exception("Statement preparation failed with error: ".$mysqli->error);
  }

  $eid = get_last_insert_id($mysqli);

  // Now insert the dates... If the dates are properly sanitized, then
  // building the query string via string concatenation should be ok.
  $insert_date_stmt = "INSERT INTO eventdate (eid, edate) VALUES ";
  foreach ($dates as $date) {
    $insert_date_stmt .= "($eid, '$date'), ";
  }
  // Remove the trailing ", "
  $insert_date_stmt = substr($insert_date_stmt, 0, strlen($insert_date_stmt) - 2);

  if($stmt = $mysqli -> prepare($insert_date_stmt)) {
    if (!$stmt -> execute()) {
      throw new Exception("Execution of the statement <b>$insert_date_stmt</b> failed with error: ".$mysqli->error);
    }
  } else {
    throw new Exception("Statement preparation for <b>$insert_date_stmt</b> failed with error: ".$mysqli->error);
  }

}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_SESSION['pid'])) {
    // User is logged in, check for validity.
    $pid = $_SESSION['pid'];
    $start = $_POST['start-time'];
    $duration = $_POST['duration'];
    $description = $_POST['description'];

    // Collect the list of dates that we should add. We should make sure
    // that each date is an actual date, and we be very particular at
    // which values we accept by carefully looking at the keys in the
    // POST variable.
    $dates = array();
    foreach ($_POST as $key => $value) {
      if ((strpos($key, "date-") === 0) && is_numeric(substr($key, 5))) {
        $dates[count($dates)] = parse_date($value);
      }
    }

    try {
      organize_event($mysqli, $pid, $start, $duration, $description, $dates);
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
  <script src="js/organize.js" type="text/javascript"></script>
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
              <tr>
                <td>Dates</td>
                <td>
                  <div class="date-entries">
                    <input type="text" class="datepicker event-date" name="date-0" />
                  </div>
                  <!-- type needs to be redundantly re-specified so that it does not trigger a form submission... -->
                  <button type="button" class="add-date"><span class="glyphicon glyphicon-plus"></span></button>
                  <button type="button" class="remove-date"><span class="glyphicon glyphicon-minus"></span></button>
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
  <?php enable_datepicker(); ?>
</body>
</html>
