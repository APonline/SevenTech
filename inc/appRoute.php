<?php

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



//Admin
$route->add("/admin", function() {
	$loginNav = array();
	$GLOBALS['nav'] = $loginNav;
	include('template/navigation.php');
	include('template/login.php');
});
//Login
$route->add("/login", function() {
	$loginNav = array();
	$GLOBALS['nav'] = $loginNav;
	include('template/navigation.php');
	include('template/login.php');
});

//Admin Home
$route->add("/dashboard", function() {
	include('view/admin/home.php');
});

//Admin Home
$route->add("/adminHome", function() {
	include('view/admin/home.php');
});

//Admin Home
$route->add("/adminPosts", function() {
	include('view/admin/posts.php');
});

//Admin Home
$route->add("/adminFeatured", function() {
	include('view/admin/featured.php');
});

//Admin About
$route->add("/adminInfo", function() {
	include('view/admin/info.php');
});

//Admin Blog
$route->add("/adminBlog", function() {
	include('view/admin/blog.php');
});

//Admin Products
$route->add("/adminProducts", function() {
	include('view/admin/products.php');
});

//Admin Products
$route->add("/adminLogout", function() {
	include('template/logout.php');
});



$active=$u;
$GLOBALS['active'] = $active;
include("template/header.php");
include('template/navigation.php');

$route->submit();

//FOOTER
if($GLOBALS['admin']==0){
	include("template/footer.php");
}else{
	echo "
	</section>
      <script src='".$dir."/assets/js/lib/jquery-1.11.3.min.js'></script>
      <script src='".$dir."/assets/js/lib/bootstrap.min.js'></script>
      <!--<script src='".$dir."/assets/js/lib/animsition.min.js'></script>-->
      <script src='".$dir."/assets/js/lib/jquery.magnific-popup.min.js'></script>
      <script src='".$dir."/assets/js/lib/jquery.countdown.min.js'></script>
      <script src='".$dir."/assets/js/lib/twitterFetcher_min.js'></script>
      <script src='".$dir."/assets/js/lib/masonry.pkgd.min.js'></script>
      <script src='".$dir."/assets/js/lib/imagesloaded.pkgd.min.js'></script>
      <script src='".$dir."/assets/js/lib/jquery.flexslider-min.js'></script>
      <script src='".$dir."/assets/js/lib/photoswipe.min.js'></script>
      <script src='".$dir."/assets/js/lib/photoswipe-ui-default.min.js'></script>
      <script src='".$dir."/assets/js/lib/jqinstapics.min.js'></script>
      <!--<script src='".$dir."/assets/js/lib/script.js'></script>-->
      <script src='".$dir."/assets/js/common/main.js'></script>
  	</body>
	</html>
	";
}

?>
