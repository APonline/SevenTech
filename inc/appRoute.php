<?php
$active=$u;
include('template/navigation.php');


//Login
$route->add("/login", function() {	
	//include('template/panel_large.php');
	include('template/login.php');
});





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

//Products
$route->add("/products", function() {
	include('template/panel_top.php');
	include('view/site/products.php');
});

//Contact
$route->add("/contact", function() {
	include('template/panel_large.php');
	include('view/site/contact.php');
});




//Admin Home
$route->add("/adminHome", function() {
	//include('template/panel_top.php');
	include('view/admin/home.php');
});

//Admin About
$route->add("/adminAbout", function() {
	//include('template/panel_top.php');
	include('view/admin/about.php');
});

//Admin Blog
$route->add("/adminBlog", function() {
	//include('template/panel_top.php');
	include('view/admin/blog.php');
});

//Admin Products
$route->add("/adminProducts", function() {
	//include('template/panel_top.php');
	include('view/admin/products.php');
});




$route->submit();

?>
