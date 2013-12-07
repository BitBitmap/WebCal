<?php
  // We must generate the URL in this format, since we're comparing
  // strings to figure out if the user wanted to get today's schedule.
  // Our javascript date-selection utility escapes the '/' character, so
  // we can safely generate them here. This isn't a guaranteed approach,
  // since the user can manually type in the '/' characters themselves.
  $today = date("Y/m/d");
  header("Location: ./calendar.php?begin=$today&end=$today");
?>