<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?=$page_title?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Let's Get Rental - Car comparison">
    <meta name="author" content="">

    <!-- Le styles -->
    <!--<link href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" rel="stylesheet" />-->
    <link href="css/bootstrap.css" rel="stylesheet" />
	<link href="css/extra.css" rel="stylesheet" />
    <style type="text/css">
	
    </style>
    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
      <![endif]-->
      <!-- <script language='JavaScript' type='text/javascript' src='/js/jquery.js'></script> -->
      <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.0/jquery.min.js"></script>
  </head>
  <body>
    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">Seek A Car</a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="#">Home</a></li>
            <li><a href="#about">About</a></li>
            <li><a href="#contact">Contact</a></li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a href="#">Action</a></li>
                <li><a href="#">Another action</a></li>
                <li><a href="#">Something else here</a></li>
                <li class="divider"></li>
                <li class="dropdown-header">Nav header</li>
                <li><a href="#">Separated link</a></li>
                <li><a href="#">One more separated link</a></li>
              </ul>
            </li>
          </ul>
        </div><!--/.navbar-collapse -->
      </div>
    </div>

<div id="header" style="display:block;">
	<ul class="list-unstyled" id="bgimage">
		<li>
		<img src="images/background.jpg" height="700" width="1600" style="min-width: 1600px; top: -100.3658536585366px;">
		</li>
	</ul>
	<div class="search-form">
		<div class="contained well">
			<?php echo validation_errors(); ?>
            <?php echo form_open('rental') ?>
				<div class="row formc">
					<div class="form-group col-lg-5">
						<select id="pickuplocation" name="pickuplocation" class="form-control">
							<?php foreach ($locations as $location): ?>
                                <option value="<?php echo $location['city']; ?>"><?php echo  $location['city']; ?></option>
                            <?php endforeach ?>
						</select>
					</div>
					<div class="form-group col-lg-4">
						<div class="input-group">
							<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
							<input type="text" class="form-control" id="pickupdate"  name="pickupdate" placeholder="Pickup Date" value="<?php echo set_value('pickupdate'); ?>" />
						</div>
					</div>
					<div class="form-group col-lg-3">
						<div class="input-group">
							<span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
							<input type="text" class="form-control" id="pickuptime"  name="pickuptime"  placeholder="Time" />
						</div>
					</div>
				</div>
				<div class="row formc">
					<div class="form-group col-lg-5">
						<select id="dropofflocation" name="dropofflocation" class="form-control">
							<?php foreach ($locations as $location): ?>
                                <option value="<?php echo $location['city']; ?>"><?php echo  $location['city']; ?></option>
                            <?php endforeach ?>
						</select>
					</div>
					<div class="form-group col-lg-4">
						<div class="input-group">
							<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
							<input type="text" class="form-control" id="dropoffdate" name="dropoffdate" placeholder="Dropoff Date" value="<?php echo set_value('dropoffdate'); ?>" />
						</div>
					</div>
					<div class="form-group col-lg-3">
						<div class="input-group">
							<span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
							<input type="text" class="form-control" id="dropofftime" name="dropofftime" name="dropofftime" placeholder="Time" />
						</div>
					</div>
				</div>
				<div class="form-group col-lg-offset-9 col-lg-3">
					<button type="submit" class="btn btn-primary btn-block">Submit</button>
				</div>
			</form>
	        
		</div>
	</div>               
</div>
    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron ">
		<div class="container">
			<div class="col-lg-6 centered">
				<img src="/images/ace.jpg" alt="Ace Rental Cars" /><img src="/images/apex.jpg" alt="Apex Rental Cars" /><img src="/images/britz.jpg" alt="Britz Rental Cars" /><img src="/images/budget.jpg" alt="Budget Rental Cars" /><img src="/images/omega.jpg" alt="Omega Rental Cars" /><img src="/images/thrifty.jpg" alt="Thrifty Rental Cars" />
			</div>
		</div>
    </div>

    <div class="container">
      <!-- Example row of columns -->
      <div class="row">
        <div class="col-lg-4">
          <h2>Search</h2>
          <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
          <p><a class="btn btn-default" href="#">View details &raquo;</a></p>
        </div>
        <div class="col-lg-4">
          <h2>Compare</h2>
          <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
          <p><a class="btn btn-default" href="#">View details &raquo;</a></p>
       </div>
        <div class="col-lg-4">
          <h2>Book</h2>
          <p>Donec sed odio dui. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Vestibulum id ligula porta felis euismod semper. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</p>
          <p><a class="btn btn-default" href="#">View details &raquo;</a></p>
        </div>
      </div>

      <hr>

      <footer>
        <p>&copy; Seek A Car 2013</p>
      </footer>
    </div> <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="assets/js/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>

