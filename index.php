<?PHP error_reporting(-1); ?>  

<!DOCTYPE html>
<html>
<?php require_once('header.php'); ?>
<body>
    <?php require_once('navbar.php'); ?>
    <div class="container" style="padding-top: 60px;">
     <div class="row">
      <div class="span12">
       <strong><h1>WebCal</h1><strong>
         <p class="lead">
          Welcome to WebCal! WebCal is a web-based calendar system to help people keep 
          track of and schedule events.
          <?php
          if(isset($_SESSION['pid']))
            echo $_SESSION['pid'];
        ?>
    </p>
</div>
</div>
</div>
<!--Javascript-->
<?php require_once('js.php'); ?>
</body>
</html>