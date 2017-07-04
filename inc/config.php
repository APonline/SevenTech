<?php
date_default_timezone_set('America/Toronto');

/**
 * Routing
 */
include "model/class.route.php";

$nav = array();

$route = new Route();
$u = $_REQUEST['uri'];

if($u=="auto-report-daily"||$u=="auto-report-monthly"){
	//AUTOMATIC PAGES
    /*if($u=="auto-report-daily"){
        $report = "daily";
    }elseif($u=="auto-report-monthly"){
        $report = "monthly";
    }
    include('inc/autoReport.php');*/
}else{


/**
 * For Development Purposes
 */
ini_set("display_errors", "on");

//Admin
$route->add("/admin", function() {
	/****************/
	/*LOGIN*/
	/****************/
	//require_once(__DIR__ . "/../model/class.database.php");
	require __DIR__ . "/../model/class.login.php";

	//Development path for my machine.

	\Fr\LS::config(array(
	  "db" => array(
		"host" => "mysql.se7en-tech.com",
		"port" => 3306,
		"username" => "andphi22",
		"password" => "Milkmilk1!",
		"name" => "se7entecheffects",
		"table" => "users"
	  ),
	  "features" => array(
		"auto_init" => true
	  ),
	  "pages" => array(
		"no_login" => array(
		  "/",
		  "/services/reset.php"
		),
		"login_page" => "template/login",
		"home_page" => "view/admin/dashboard"
	  )
	));
	include('template/login.php');
	/****************/
		/*LOGIN*/
	/****************/
});
	
	$nav = array();
		$nav[] = "about";
		$nav[] = "blog";
		$nav[] = "products";
		$nav[] = "contact";


	include("template/header.php");
	include("appRoute.php");
}
