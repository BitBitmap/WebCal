<?php
class Status {
  const Success = "success";
  const Error = "error";
};

// Should be set to either null, or a constant under Status.
$status = null;
// Will be displayed if $status is set.
$status_message = "";

function display_status() {
  if ($status != null) {
?>
  <div class="status <?php echo $status; ?>">
    <p><?php echo $status_message; ?></p>
  </div>
<?php
  }
}
?>