<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?=$page_title?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Let's Get Rental - Car comparison">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" rel="stylesheet">
    <link href="/css/bootstrap.css" rel="stylesheet">
    <link href="/css/bootstrap-responsive.css" rel="stylesheet">
    <link href="/css/extra.css" rel="stylesheet">
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
    <div class="navbar navbar-fixed-top">
        <div class="navbar-inner">
            <div class="container-fluid">
                <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>
                <a class="brand" href="#"><img src="" width="111" height="30" alt="LetsGetRental" /></a>
                <div class="nav-collapse">
                    <ul class="nav">
                        <li class="active"><a href="#">Home</a></li>
                        <li><a href="#about">About</a></li>
                        <li><a href="#contact">Contact</a></li>
                    </ul>
                </div><!--/.nav-collapse -->
            </div>
        </div>
    </div>
    <?php echo ! empty($content) ? $content : ""?>
    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="twitter-bootstrap-v2/docs/assets/js/jquery.js"></script>
    <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
    <script src="twitter-bootstrap-v2/docs/assets/js/bootstrap-transition.js"></script>
    <script src="twitter-bootstrap-v2/docs/assets/js/bootstrap-alert.js"></script>
    <script src="twitter-bootstrap-v2/docs/assets/js/bootstrap-modal.js"></script>
    <script src="twitter-bootstrap-v2/docs/assets/js/bootstrap-dropdown.js"></script>
    <script src="twitter-bootstrap-v2/docs/assets/js/bootstrap-scrollspy.js"></script>
    <script src="twitter-bootstrap-v2/docs/assets/js/bootstrap-tab.js"></script>
    <script src="twitter-bootstrap-v2/docs/assets/js/bootstrap-tooltip.js"></script>
    <script src="twitter-bootstrap-v2/docs/assets/js/bootstrap-popover.js"></script>
    <script src="twitter-bootstrap-v2/docs/assets/js/bootstrap-button.js"></script>
    <script src="twitter-bootstrap-v2/docs/assets/js/bootstrap-collapse.js"></script>
    <script src="twitter-bootstrap-v2/docs/assets/js/bootstrap-carousel.js"></script>
    <script src="twitter-bootstrap-v2/docs/assets/js/bootstrap-typeahead.js"></script>
    <script type="text/javascript" src="<?=base_url()?>/js/document.ready.all.js"></script>

    </body>
</html>
