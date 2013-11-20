<?php
class Status {
  const Success = "success";
  const Error = "error";
};

// Should be set to either null, or a constant under Status.
// Will be displayed if $status is set.
function display_status($status, $status_message="An error occurred!") {
  if ($status != null) {
?>
  <div class="status <?php echo $status; ?>">
    <p><?php echo $status_message; ?></p>
  </div>
<?php
  }
}
?>