<?php
$dir = $GLOBALS['dir'];

if($GLOBALS['admin']==0){
	$view = "";
	$bk='';
}else{
	$view = "admin";
	$bk = "style='background-color:#fff!important;'";
}

echo "
<!DOCTYPE html>
<html lang='en' ".$bk.">
    <head>
      <meta charset='utf-8'>
      <title>Se7en Tech</title>
      <meta name='viewport' content='width=device-width, user-scalable=no, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0'>

      <!-- Favicon ================== -->
      <!-- Standard -->
      <link rel='shortcut icon' href='".$dir."/assets/img/icon.png'>
      <!-- Retina iPad Touch Icon-->
      <link rel='apple-touch-icon' sizes='144x144' href='".$dir."/assets/img/icon.png'>
      <!-- Retina iPhone Touch Icon-->
      <link rel='apple-touch-icon' sizes='114x114' href='".$dir."/assets/img/icon.png'>
      <!-- Standard iPad Touch Icon-->
      <link rel='apple-touch-icon' sizes='72x72' href='".$dir."/assets/img/icon.png'>
      <!-- Standard iPhone Touch Icon-->
      <link rel='apple-touch-icon' sizes='57x57' href='".$dir."/assets/img/icon.png'>

      <!--  Resources style ================== -->
      <link href='".$dir."/assets/css/lib/theme-Dark.css' rel='stylesheet' type='text/css' media='all'/>
      <link href='".$dir."/assets/css/common/main.css' rel='stylesheet' type='text/css' media='all'/>

			<link href='https://fonts.googleapis.com/css?family=Oswald:700' rel='stylesheet'>
    </head>
    <body class='".$view."' ".$bk." >
      <section class='animsition".$view."'>
";


?>
