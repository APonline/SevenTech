<?php

include('controllers/site/controller.blog.php');

//blog cards
echo "
<div class='blog'>
	<div class='project'>
		<ul class='gallery project_navigation'>
			$blogCard
		</ul>
		";

		//blog info
		echo"
		<ul class='project_info'>
		  $blogCardInfo
		</ul>
	</div>
</div>
";

?>