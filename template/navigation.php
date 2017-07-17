<?php
$dir = $GLOBALS['dir'];
$nav = $GLOBALS['nav'];
$active = $GLOBALS['active'];

	echo "
	<nav>
		<ul>
			<li><a href='home'><img src='".$dir."/assets/img/icon.png' alt='' width='40' style='margin-top: -3px;' /></a></li>
			";
			for($x=0; $x<count($nav); $x++){
				$pageCurr = $nav[$x]['url'];

				if($active==$pageCurr){
					$GLOBALS['page'] = $nav[$x]['name'];
					echo "<li><a href='".$nav[$x]['url']."' class='active'>".$nav[$x]['name']."</a></li>";
				}else{
					echo "<li><a href='".$nav[$x]['url']."'>".$nav[$x]['name']."</a></li>";
				}
			}
	echo "</ul>
	</nav>
    ";
?>
