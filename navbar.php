<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="./">WebCal</a>
        </div>
        <div class="navbar-collapse collapse">
           <ul class="nav navbar-nav">
              <li class="active"><a href="./">Home</a></li>
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  Schedule
                  <span class="glyphicon glyphicon-chevron-down"></span>
                </a>
                <ul class="dropdown-menu">
                  <li><a href="today.php">Today's Schedule</a></li>
                  <li><a href="calendar.php">My Schedule</a></li>
                  <li><a href="calendar.php">View Friend's Schedule</a></li>
                </ul>
              </li>
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  Invitations
                  <span class="glyphicon glyphicon-chevron-down"></span>
                </a>
                <ul class="dropdown-menu">
                  <li><a href="calendar.php">Pending Invitations</a></li>
                  <li><a href="calendar.php">All Invitations</a></li>
                </ul>
              </li>
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  Organize
                  <span class="glyphicon glyphicon-chevron-down"></span>
                  </a>
                <ul class="dropdown-menu">
                  <li><a href="organize.php">Organize Event</a></li>
                  <li><a href="invite.php">Invite To Event</a></li>
                </ul>
              </li>
              <li><a href="friend.php">Manage Friends</a></li>
          </ul>
          <?php if (!isset($_SESSION['pid'])) { ?>
          <form action="login.php" method="POST" class="navbar-form navbar-right">
              <div class="form-group">
                 <input name="pid" type="text" placeholder="PID" class="form-control">
             </div>
             <div class="form-group">
                 <input name="password" type="password" placeholder="Password" class="form-control">
             </div>
             <button type="submit" class="btn btn-success">Sign in</button>
         </form>
         <?php } else { ?>
         <ul class="nav navbar-nav navbar-right">
            <li><a href="logout.php">Logout</a></li>
        </ul>
        <?php } ?>
    </div>
    <!--/.navbar-collapse -->
</div>
</div>