<?php 
if(!isset($_SESSION)){
	session_start();
}
?>
<!DOCTYPE html>
<html>
	<head>		
		<meta charset="utf-8">
		<title>WebCal</title>
		<!--Stylesheets-->
		<link href="css/bootstrap.css" rel="stylesheet">
		<link href="css/bootstrap-theme.css" rel="stylesheet">
		</style>
	</head>

	<body>
    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="brand" href="#">WebCal</a>
          <div class="nav-collapse collapse">
            <ul class="nav">
              <li class="active"><a href="#">Home</a></li>
              <li><a href="#">Login</a></li>
              <li><a href="#">About</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>
    <div class="container" style="padding-top: 60px;">
    	<div class="row">
				<div class="span12">
					<h1>WebCal</h1>
					<p class="lead">
						Welcome to WebCal! WebCal is a web-based calendar system to help people keep 
						track of and schedule events.
					</p>
				</div>
			</div>
		</div>
		<!--Javascript-->
		<script src="http://code.jquery.com/jquery.js"></script>
    	<script src="js/bootstrap.js"></script>
    </body>