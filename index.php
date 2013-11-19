<?PHP error_reporting(-1); ?>
<?php
require_once('mysql.php');

if (isset($_POST['pid']) && isset($_POST['password'])){
    if($stmt = $mysqli -> prepare("SELECT pid, fname, lname FROM person WHERE pid=? AND passwd=?")){
        $stmt -> bind_param("ss", $_POST['pid'], hash("md5", $_POST['password']));
        $stmt -> execute();
        $stmt -> bind_result($pid, $fname, $lname);
        $stmt -> fetch();

        $_SESSION['pid'] = $pid;
        $_SESSION['fname'] = $fname;
        $_SESSION['lname'] = $lname;
    }

}
?>   

<!DOCTYPE html>
<html>
<head>		
  <meta charset="utf-8">
  <title>WebCal</title>
  <!--Stylesheets-->
  <link href="css/bootstrap.css" rel="stylesheet">
</style>
</head>
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
<script src="http://code.jquery.com/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>