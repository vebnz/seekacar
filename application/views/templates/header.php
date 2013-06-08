<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Let's Get Rental - Car comparison</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Let's Get Rental - Car comparison">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="/css/bootstrap.css" rel="stylesheet">
	<link href="/css/bootstrap-responsive.css" rel="stylesheet">
    <style type="text/css">
        body {
        padding-top: 60px;
        padding-bottom: 40px;
        }
		
		div {
			display: block;
		}		
		
		.app_view {
			min-height: 400px;
		}
		
		.hero {
			z-index: 1;
			position: relative;
			height: 620px;
			margin-top: -18px;
		}
		
		.slideshow {
			margin: 0;
		}

		.slideshow li {
			position: absolute;
			z-index: 80;
			height: 100%;
			width: 100%;
			top: -100px;
			overflow: hidden;
			background: #000;
		}
		
		.slideshow li img {
			position: absolute;
			float: none;
			width: 100%;
			margin-left: 0 auto;
		}
		
        .main {
          padding: 10px 60px 60px 60px;            
          -webkit-border-radius: 6px;
          -moz-border-radius: 6px;
          border-radius: 6px;
        }
        .main h1 {
          font-size: 40px;
          margin-bottom: 5px;
          line-height: 1;
          letter-spacing: -1px;
          color:#5bb75b;
        }
        
        .search-form {
			z-index: 160;
			position: absolute;
			height: 300px;
			width: 100%;
			padding-top: 100px;
			text-shadow: 0 0 15px rgba(0,0,0,0.6),0 -1px 1px rgba(0,0,0,0.6);
        }
		
		.contained {
			position: relative;
			width: 280px;
			margin: 0 auto 0 auto;
		}
		
		.car-brands {
			margin-top: -75px;            
		}
        
        .car-brands img {
            padding-left: 10px;
        }        
        
        .nav li {
			padding-top: 5px;
		}
	  
		.sidebar-nav {
			padding: 9px 0;
		}
		
		.leaderboard {
			margin-bottom: 30px;
			background-image: url('/twitter-bootstrap/images/gridbg.gif');
			background-repeat:repeat;
			-webkit-border-radius: 6px;
			-moz-border-radius: 6px;
			border-radius: 6px;
		}
        
        .boxes {
            margin-bottom: 10px;
        }
        
		
		.leaderboard h1 {
			font-size: 40px;
			margin-bottom: 15px;
			line-height: 1;
			letter-spacing: -1px;
			color:#FF6600;
		}
		
		.leaderboard p {
			font-size: 18px;
			font-weight: 200;
			line-height: 27px;
		}

		.well {
			background-image: url('/twitter-bootstrap/images/gridbg.gif');
			background-repeat:repeat;
			-webkit-border-radius: 6px;
			-moz-border-radius: 6px;
			border-radius: 6px;
		}

		.nav .nav-header {
			font-size: 18px;
			color:#FF9900;
		}
        
        .left-search-form {
            margin: 10px 0 0 15px;
            width: 100%;
        }
        .full-width {
            box-sizing: border-box;
            width: 90%;
            height: 30px;
        }
        
        .sixty-width {
            box-sizing: border-box;
            width: 55%;
            height: 30px;
        }
        
        .forty-width {
            box-sizing: border-box;
            width: 33%;
            height: 30px;
        }
        
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