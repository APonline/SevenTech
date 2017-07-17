<?php
date_default_timezone_set('America/Toronto');

$GLOBALS['admin'] = 0;

/**
 * Routing
 */
include "model/class.route.php";

//Start Nav
$nav = array();
	//site nav
	$navSite = array();
	$navSite[] = array("name"=>"about","url"=>"about");
	$navSite[] = array("name"=>"blog","url"=>"blog");
	$navSite[] = array("name"=>"products","url"=>"products");
	$navSite[] = array("name"=>"contact","url"=>"contact");

	//site nav
	$navAdmin = array();
	$navAdmin[] = array("name"=>"Posts","url"=>"adminPosts");
	$navAdmin[] = array("name"=>"Featured","url"=>"adminFeatured");
	$navAdmin[] = array("name"=>"Info","url"=>"adminInfo");
	$navAdmin[] = array("name"=>"Blog","url"=>"adminBlog");
	$navAdmin[] = array("name"=>"Products","url"=>"adminProducts");
	$navAdmin[] = array("name"=>"Logout","url"=>"adminLogout");

$GLOBALS['navSite'] = $navSite;
$GLOBALS['navAdmin'] = $navAdmin;

//Start Route
$route = new Route();
$u = $_REQUEST['uri'];
$GLOBALS['active'] = $u;

//Navigation swtich
if (strpos($u, 'admin') !== false){
	$GLOBALS['nav'] = $GLOBALS['navAdmin'];
	$GLOBALS['admin'] = 1;
}else{
	$GLOBALS['nav'] = $GLOBALS['navSite'];
	$GLOBALS['admin'] = 0;
}



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

	include("appRoute.php");
}
