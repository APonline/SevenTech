<?php
	echo "
	<nav>
		<ul>
			<li><a href='home'><img src='assets/img/se7en-logo-W.png' alt='' width='60' style='margin-top: -3px;' /></a></li>
			";
			for($x=0; $x<count($nav); $x++){
				if($active==$nav[$x]){
					echo "<li><a href='".$nav[$x]."' class='active'>".$nav[$x]."</a></li>";
				}else{
					echo "<li><a href='".$nav[$x]."'>".$nav[$x]."</a></li>";
				}
			}
	echo "</ul>
	</nav>
    ";
?>