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
    <link href="/css/bootstrap.css" rel="stylesheet" />
    <link href="/css/extra.css" rel="stylesheet" />
    <link href="/css/jquery.timepicker.css" rel="stylesheet" />
    <link href="/css/datepicker.css" rel="stylesheet" />
    <link href="/css/slider.css" rel="stylesheet" />
	<link rel="shortcut icon" href="/images/favicon.ico" type="image/x-icon">
	<link rel="icon" href="/images/favicon.ico" type="image/x-icon">
    <style type="text/css">

    </style>
    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
      <![endif]-->
      <!-- <script language='JavaScript' type='text/javascript' src='/js/jquery.js'></script> -->
      <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.0/jquery.min.js"></script>
      <script>
	  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
	
	  ga('create', 'UA-48131048-1', 'auto');
	  ga('send', 'pageview');

      </script>
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
          <a class="navbar-brand" href="http://seekacar.co.nz"><img id="logo" src="/images/seekacar-logo.png" /></a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li id="navHome" class="active"><a href="http://seekacar.co.nz">Home</a></li>
            <li id="navAbout"><a href="about">About</a></li>
            <li id="navContact"><a href="contact">Contact</a></li>
          </ul>
        </div><!--/.navbar-collapse -->
      </div>
    </div>    
    <?php echo ! empty($content) ? $content : ""?>
    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <!--<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>-->
    <script type="text/javascript" src="/js/document.ready.all.js"></script>
    <script type="text/javascript" src="/js/jquery.timepicker.min.js"></script>
    <script type="text/javascript" src="/js/bootstrap-datepicker.js"></script>
    <script type="text/javascript" src="/js/bootstrap-slider.js"></script>
	<script type="text/javascript" src="/js/bootstrap.min.js"></script>
    </body>
</html>
