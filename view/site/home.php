<?php

include('controllers/site/controller.home.php');

//cards
echo "
<div class='project'>
	<ul class='gallery project_navigation'>
		$featuredCard
	</ul>
";


//car info
echo "
	<ul class='project_info'>
		$featuredCardInfo
	</ul>
</div>
";

?>
