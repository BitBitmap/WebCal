<?php

define("DATE_MIN", "1000-01-01");
define("DATE_MAX", "9999-12-31");

/*
This script will enable click-select functionality for dates.
*/
function enable_datepicker() {
?>
  <script src="./js/bootstrap-datepicker.js" type="text/javascript"></script>
  <script type="text/javascript">
    $(function() {
      $('.datepicker').datepicker();
    })
  </script>
<?php
}
?>
