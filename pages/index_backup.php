<!DOCTYPE html>
<html style="height:100%">
<head>
<title>Home</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="Truck Responsive web template, Bootstrap Web Templates, Flat Web Templates, Andriod Compatible web template,Smartphone Compatible web template, free webdesigns for Nokia, Samsung, LG, SonyErricsson, Motorola web design" />
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<!-- bootstarp-css -->
<link href="../css/bootstrap.css" rel="stylesheet" type="text/css" media="all" />

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
<link rel="stylesheet" href="dist/css/bootstrap-select.css">
<!--// bootstarp-css -->
<!-- css -->
<link rel="stylesheet" href="../css/style.css" type="text/css" media="all" />
<!--// css -->
<!--dropdown css-->
<link rel="stylesheet" href="../css/combo.select.css" type="text/css" />
<!--//dropdown css-->
<script src="../js/jquery-1.11.1.min.js"></script>
<!--bootstrap select-->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
<script src="dist/js/bootstrap-select.js"></script>
<!--//bootstrap select-->
<!--fonts-->
<link href='http://fonts.useso.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,800,700,600' rel='stylesheet' type='text/css'>
<!--/fonts-->
<link href="../css/animate.css" rel="stylesheet" type="text/css" media="all">
<script src="../js/wow.min.js"></script>
<script src="../js/homepageJS.js"></script>
	<script src="../js/homepageJQ.js"></script>
<script>
	 new WOW().init();
</script>
<!--start-smoth-scrolling-->
		<script type="text/javascript" src="../js/move-top.js"></script>
		<script type="text/javascript" src="../js/easing.js"></script>
<script type="text/javascript">
	var route_count;
	 var routeGet = false;
	var $ = jQuery.noConflict();
	jQuery(document).ready(function($) {
		$topEffect(".scroll").click(function(event){
			event.preventDefault();
			$('html,body').animate({scrollTop:$(this.hash).offset().top},900);
		});
	});
</script>
<!--start-smoth-scrolling-->

<!--Expandable table part-->
<script src="http://libs.useso.com/js/jquery/1.11.0/jquery.min.js" type="text/javascript"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
<link rel="stylesheet" type="text/css" href="../css/expandable_default.css">
<!-- INCLUDES -->
<link rel="stylesheet" href="../css/bootstrap-table-expandable.css">
<script src="../js/bootstrap-table-expandable.js"></script>
<!--Expandable table part-->
<!--Google Map-->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>  
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>  
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<!--//Google Map-->
<!--Scroll one page at at a time-->

<!--//end scroll-->
</head>
<body onLoad="onloadFunctions()">
	<!-- banner -->
	<div id="home" class="banner a-banner">
		<!-- container -->
		<div class="container">
			<div class="header">
				<div class="head-logo">
					<a href="index.php"><img src="../images/logo.png" alt="" /></a>
				</div>
				<div class="top-nav">
					<span class="menu"><img src="../images/menu.png" alt=""></span>
					<ul class="nav1">
						<li class="hvr-sweep-to-bottom active"><a href="index.php">Home<i><img src="../images/nav-but1.png" alt=""/></i></a></li>
						<li class="hvr-sweep-to-bottom"><a href="about.html">About<i><img src="../images/nav-but2.png" alt=""/></i></a></li>
						<li class="hvr-sweep-to-bottom"><a href="mail.html">Contact<i><img src="../images/nav-but5.png" alt=""/></i></a></li>
						<div class="clearfix"> </div>
					</ul>
					<!-- script-for-menu -->
							 <script>
							   $( "span.menu" ).click(function() {
								   $( "ul.nav1" ).slideToggle( 300, function() {
								   // Animation complete.
								   });
							   });
							</script>
						<!-- /script-for-menu -->
				</div>
				<div class="clearfix"> </div>
			</div>
		</div>
        
		<!-- //container -->
		<div class="container">
				<script src="../js/responsiveslides.min.js"></script>
					 <script>
						// You can also use "$(window).load(function() {"
						var $j = jQuery.noConflict();
						$j(function () {
						  // Slideshow 4
						  $j("#slider3").responsiveSlides({
							auto: true,
							pager: true,
							nav: false,
							speed: 500,
							namespace: "callbacks",
							before: function () {
							  $j('.events').append("<li>before event fired.</li>");
							},
							after: function () {
							  $j('.events').append("<li>after event fired.</li>");
							}
						  });
					
						});
					  </script>

			<div  id="top" class="callbacks_container">
				<ul class="rslides" id="slider3">
					<li>
						<div id="word1" class="banner-info">
								<h2>Where you <span> always </span> find a great delivery</h2>  
								<div class="line"> </div>
								<p>Fast, efficient, safe</p>
						</div>
					</li> 
					<li>
						<div id="word2" class="banner-info">
								<h2>Focus on <span> delivery </span> in Victoria, Australia</h2>
								<div class="line"> </div>
								<p>Analysing traffic volumes, accident rates and incorporating weather information</p>
						</div>
					</li>
					<li>
						<div id="word3" class="banner-info">
								<h2>Recommend <span> BEST ROUTEs </span> for delivery</h2>
								<div class="line"> </div>
								<p>We give several optional routes and recommendation evaluations for you to make a decision. </p>
						</div>
					</li> 
				</ul>
			</div>      	
		</div>
        	
       <div id="arrowAlert" style="width:100%;height:auto;display:block;border:1px solid #fff">
       		<div class="arrow bounce"></div>
       </div>
 		<!--Monitor mouse event-->
	   <script>
		   $(document).ready(function(){
				$(window).scroll(function () { 
				  if ($(window).scrollTop() > $('body').height() / 2) {
					  $("#arrowAlert").css("display","none");
				  }else{
					  $("#arrowAlert").css("display","block");
				  }
				});
			});
		</script>
	</div>
	<!-- //banner -->
	<!-- Map Layer -->
	<div id="map_container" class="specialty">
    	<!--Left part-->
       <div id="left_part" class="left_part">
           <!--Search Box-->
           <div id="control" class="panel" style="height:60%; background: #2fa0ec;">
           	<!--<form action="dataRetrieval.php" method="post" target="getInfoFrame">-->
                    <strong style="font-size:14px;color:#fff;font-family:sans-serif;">Start:</strong>
                    <input id="start" name="startPoint" class="controls" type="text" placeholder="Start Location" />
                    <strong style="font-size:14px;color:#fff;font-family:sans-serif;">End:</strong>
                    <input id="end" name="endPoint" class="controls" type="text" placeholder="End Location" />  
                    <strong style="font-size:14px;color:#fff;font-family:sans-serif;">Mode of Travel: </strong>
                    <select id="mode" class="selectpicker show-menu-arrow form-control">
                      <option value="DRIVING">Driving</option>
                      <option value="WALKING">Walking</option>
                      <option value="BICYCLING">Bicycling</option>
                    </select> 
                    <input id="map_search" type="submit" class="button" value="Search" onClick="searchClickEvents()">
               <!--</form>
               <iframe id="getInfoFrame" name="getInfoFrame" style="display:block"></iframe>-->
               <!--Instruction part-->
           	 <div id="pre_route" style="overflow-y: scroll;">
                    <p style="padding:10px;color:#fff;width:100%;background:#2fa0ec;-webkit-box-pack:justify;font-size:12px;">
                    <span align="center" style="color:#fff;font-size:24px;font-family:Microsoft Yahei"> Instructions</span><br/>
                        Type into the start and destination addresses.<br/><br/> Then click search, we will give you the best routes based on historical data as well as real-time information.<br/> <br/>Enjoy!
                    </p>
               </div>
               <!--//End Instruction-->
               <script>
				    $(document).ready(function(){
						$("#map_search").click(function(){
							$("#control").css({"height":"30%","overflow":"hidden"});
							$("#map_container").css("height", "100%");
							$("#tableAndGraph").css("display","block");
							$("#map").css("height","60%");
						});
					});
				 </script>
            </div>
           <!--//Search Box-->
           
           <!--Route Detail-->
           <div id="route_instruction_bak" style="overflow-y: scroll;display: none"></div>
		   <div id="route_instruction"></div>
           <!--//Route Detail-->
       </div>
       <!--//Left Part-->
       
       <!--Map part-->
		<div class="mapAndDetail" style="position: relative">
       		<!--Google Map-->
           <div id="map" data-wow-delay="0.4s" style="visibility: visible; -webkit-animation-delay: 0.4s;"></div>
			<div id="loading">
				<img src="http://bradsknutson.com/wp-content/uploads/2013/04/page-loader.gif" height="30"
				 width="30" style="margin-top:18%;margin-left:48%">
			</div>
			<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
			<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&libraries=places&language=en-AU"></script>
			<script type="text/javascript" src="../js/googleMap.js"></script>
           <!--Google Map-->
           
           <!--Table and Detail-->
           <div id="tableAndGraph" class="tableAndGraph">
			<!--<table id="routesTable" class="table table-hover table-expandable">-->
<!--		   	<table id="routesTable" class="table table-hover" style="boder:1px solid #c7c7c7">-->
			   <table class="table table-striped">
				   <thead>
					   <tr>
						   <th>Routes</th>
						   <th>Number of Accidents</th>
						   <th>Traffic Volume</th>
						   <th>Weather Influence</th>
					   </tr>
				   </thead>
				   <tbody>
					   <tr id="route1">
						   <td>Route Option 1</td>
						   <td>
							   <p style="color:darkred" id="accident_no1"></p>
						   </td>
						   <td>
							   <p style="color: #d58512;" id="traffic_volume1"></p>
						   </td>
						   <td>
							   <p style="color:darkblue" class="weatherInfo"></p>
						   </td>
					   </tr>
<!--					   <tr id="route2" style="visibility: hidden">-->
<!--						   <td>Route Option 1</td>-->
<!--						   <td>-->
<!--							   <p style="color:darkred" id="accident_no2"></p>-->
<!--						   </td>-->
<!--						   <td>-->
<!--							   <p style="color: #d58512;" id="traffic_volume2"></p>-->
<!--						   </td>-->
<!--						   <td>-->
<!--							   <p style="color:darkblue" class="weatherInfo"></p>-->
<!--						   </td>-->
<!--					   </tr>-->
<!--					   <tr id="route3" style="visibility:hidden;">-->
<!--						   <td>Route Option 1</td>-->
<!--						   <td>-->
<!--							   <p style="color:darkred" id="accident_no3"></p>-->
<!--						   </td>-->
<!--						   <td>-->
<!--							   <p style="color: #d58512;" id="traffic_volume3"></p>-->
<!--						   </td>-->
<!--						   <td>-->
<!--							   <p style="color:darkblue" class="weatherInfo"></p>-->
<!--						   </td>-->
<!--					   </tr>-->
				   </tbody>
			   </table>
           </div>
			<div id="histogram"></div>

           <!--//table and detail-->
       </div>
       <!--//Map part-->
	</div>
	<!-- //Map Layer -->

 	<!-- banner-bottom -->
	<!-- footer -->
	<div class="footer">
		<!-- container -->
		<div class="container">
			<div class="col-md-6 footer-left  wow fadeInLeft animated" data-wow-delay="0.4s" style="visibility: visible; -webkit-animation-delay: 0.4s;">
				<ul>
					<li><a href="index.php">Home</a></li>
					<li><a href="about.html">About</a></li>
					<li><a href="mail.html">Mail Us</a></li>
				</ul>
				<form>
					<input type="text" placeholder="Email" required>
					<input type="submit" value="Subscribe">
				</form>
			</div>
			<div class="col-md-3 footer-middle wow bounceIn animated" data-wow-delay="0.4s" style="visibility: visible; -webkit-animation-delay: 0.4s;">
				<h3>Address</h3>
				<div class="address">
					<p>900 Dandenong Road,
						<span>Caulfield East VIC 3145</span>
					</p>
				</div>
				<div class="phone">
					<p>(03) 9903 2000</p>
				</div>
			</div>
			<div class="col-md-3 footer-right  wow fadeInRight animated" data-wow-delay="0.4s" style="visibility: visible; -webkit-animation-delay: 0.4s;">
				<a href="#"><img src="../images/logo.png" alt="" /></a>
				<p>Intelligent Logistics: The Best Choice!</p>
			</div>
			<div class="clearfix"> </div>	
		</div>
		<!-- //container -->
	</div>
	<!-- //footer -->


	<!-- //footer -->
	<div class="copyright">
		<!-- container -->
		<div class="container">
			<div class="copyright-left wow fadeInLeft animated" data-wow-delay="0.4s" style="visibility: visible; -webkit-animation-delay: 0.4s;">
				<p>Copyright &copy; 2015. DigiSoft All rights reserved.</p>
			</div>
			<div class="copyright-right wow fadeInRight animated" data-wow-delay="0.4s" style="visibility: visible; -webkit-animation-delay: 0.4s;">
				<ul></ul>
			</div>
			<div class="clearfix"> </div>
			<script type="text/javascript">
				$(document).ready(function() {
					/*
					var defaults = {
						containerID: 'toTop', // fading element id
						containerHoverID: 'toTopHover', // fading element hover id
						scrollSpeed: 1200,
						easingType: 'linear' 
					};
					*/
					
					$().UItoTop({ easingType: 'easeOutQuart' });
					
				});
			</script>
		<a href="#home" id="toTop" class="scroll" style="display: block;"> <span id="toTopHover" style="opacity: 1;"> </span></a>

		</div>
		<!-- //container -->
	</div>
</body>
</html>
