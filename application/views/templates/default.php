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

    </body>
</html>
