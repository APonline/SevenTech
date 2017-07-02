<?php
$active=$u;
include('template/navigation.php');

//Home
$route->add("/", function() {	
	include('template/panel_large.php');
	include('view/site/home.php');
});
//Home
$route->add("/home", function() {
	include('template/panel_large.php');
	include('view/site/home.php');
});

//About
$route->add("/about", function() {
	include('template/panel_large.php');
	include('view/site/about.php');
});

//Blog
$route->add("/blog", function() {
	include('template/panel_small.php');
	include('view/site/blog.php');
});

//Contact
$route->add("/contact", function() {
	include('template/panel_large.php');
	include('view/site/contact.php');
});



$route->submit();

?>
