<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">WebCal</a>
        </div>
        <div class="navbar-collapse collapse">
         <ul class="nav navbar-nav">
          <li class="active"><a href="#">Home</a></li>
          <li><a href="#about">About</a></li>
          <li><a href="#contact">Contact</a></li>
      </ul>
      <form action="index.php" method="POST" class="navbar-form navbar-right">
          <div class="form-group">
           <input name="pid" type="text" placeholder="PID" class="form-control">
       </div>
       <div class="form-group">
           <input name="password" type="password" placeholder="Password" class="form-control">
       </div>
       <button type="submit" class="btn btn-success">Sign in</button>
   </form>
</div><!--/.navbar-collapse -->
</div>
</div>