<?php
include('controllers/admin/controller.posts.php');

$page = $GLOBALS['page'];

echo "
<div class='project'>
	<h1>".strtoupper($page)."</h1>

	".$postCard."
</div>
";

?>
