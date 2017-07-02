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

/****************/
	/*LOGIN*/
/****************/
//require_once(__DIR__ . "/../model/class.database.php");
/*require __DIR__ . "/../model/LS.php";

//Development path for my machine.
$developmentPath = "/TraceLogix/dev/web.TraceLogix";
if(__DIR__ == "/home/teamgit/its.matthewsullivan.media/inc") $developmentPath = "";

\Fr\LS::config(array(
  "db" => array(
    "host" => "dev.its.matthewsullivan.media",
    "port" => 3306,
    "username" => "socreativeteam",
    "password" => "sM5AayLMvkXPTv2XGu",
    "name" => "its_tracelogix",
    "table" => "users"
  ),
  "features" => array(
    "auto_init" => true
  ),
  "pages" => array(
    "no_login" => array(
      "/",
      $developmentPath . "/services/reset.php"
    ),
    "login_page" => $developmentPath . "/login",
    "home_page" =>  $developmentPath . "/dashboard"
  )
));*/
/****************/
	/*LOGIN*/
/****************/
	
	$nav = array();
		$nav[] = "about";
		$nav[] = "blog";
		$nav[] = "contact";


	include("template/header.php");
	include("appRoute.php");
}
