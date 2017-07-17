<?php
$dir = $GLOBALS['dir'];
$currPg = $_SERVER['REQUEST_URI'];
$page = $GLOBALS['page'];

require_once("inc/config.php");
require_once("model/class.posts.php");

if(isset($_POST['action'])){
	$selectedCat = $_POST['name'];
	$posts = new Posts();
	$posts = $posts->getContent($selectedCat,"admin");
	$posts = json_decode($posts, true);
}else{
	$posts = new Posts();
	$posts = $posts->getContent($page,"admin");
	$posts = json_decode($posts, true);
}

$categories = new Posts();
$categories = $categories->getCategories();
$categories = json_decode($categories, true);


/*SELECTIONS*/
$select = "<select data-id='".$currPg."' class='drop-down' name='contentType'>";
	if(isset($selectedCat)){
		$select .= "<option>".$selectedCat."</option>";
	}
$select .= "<option>Posts</option>";
	foreach($categories as $cat){
		if(isset($selectedCat)&&$cat['name']!=$selectedCat){
			$select .= "<option>".$cat['name']."</option>";
		}elseif(!isset($selectedCat)){
			$select .= "<option>".$cat['name']."</option>";
		}
	}
$select .= "</select>";
/*SELECTIONS*/



/** MAIN **/
function makeCards($currPg,$page,$dir,$select,$posts){
	//Generates feature cards
	$postCard ="";
		$postCard .="
				<div class='item-menu-buttons'>
					<form action='$currPg' method='POST'>
						<!--<button type='submit' name='submit' value='manage' class='view redbk'>MANAGE</button>-->
						<button type='submit' name='submit' value='add' class='view redbk'>ADD NEW</button>
					</form>
					";
					if($page=="Posts"){
						$postCard .= " ".$select." ";
					}
				$postCard .="
				</div>
				<ul>";

	if(!empty($posts)){
		foreach($posts as $index => $post){
			if($post['active']==1){
				$actVal=0;
				$act="<p class='tag bluebk'>Active</p>";
			}else{
				$actVal=1;
				$act="<p class='tag graybk'>Inactive</p>";
			}
			$postCard .="
			  <form action='$currPg' method='POST'>
			  <li class='post-listing'>
					".$act."
			  	<div class='item-thumb-image'>
				  	<img src='".$dir."/".$post['media_main']."'  alt='".$post['topic']."' class='img-responsive' />
					</div>
					<div class='item-thumb-meta'>
						  <h4><b>TITLE:</b> ".$post['title']."</h4>
						  <span class='gray'><b>TOPIC:</b> ".$post['topic']."</span>
					</div>
					<div class='item-menu-buttons'>
						<button type='submit' name='submit' value='view' class='view redbk'>VIEW</button>
						<button type='submit' name='submit' value='edit' class='view redbk'>EDIT</button>
						<button type='submit' name='submit' value='delete' class='view redbk'>DELETE</button>
						<button type='submit' name='submit' value='active' class='view redbk'>ACTIVE</button>
					</div>
			  </li>
				<input type='hidden' name='id' placeholder='' value='".$post['id']."' />
				<input type='hidden' name='activity' placeholder='' value='".$actVal."' />
			  </form>
			";
		}
	}else{
		$postCard .="<li>No Results</li>";
	}
	$postCard .="</ul>";

	return $postCard;
}
/** MAIN **/




if(isset($_POST['submit'])){
//delete default
$postCard="";

	$action = $_POST['submit'];

	$postCard ="<a class='adminLink' href='adminPosts'><h5>< Back</h5></a>";
	$postCard .="<h3>".strtoupper($action)."</h3>";
	switch($action){
		case 'manage':
		break;

		case 'submit':
			$postSubmit = new Posts();
			$postSubmit = $postSubmit->addNewPost($_POST);

			$posts = new Posts();
			$posts = $posts->getContent($page,"admin");
			$posts = json_decode($posts, true);

			$postCard ="<h3 class='errorMsg'>Success!</h3>";
			$postCard .= makeCards($currPg,$page,$dir,$select,$posts);
		break;

		case 'add':
			$postCard .="
				<form action='$currPg' method='POST'>
					<p>
						<h5>Title</h5>
						<input type='text' name='title' placeholder='' value='' />
					</p>
					<p>
						<h5>Topic</h5>
						<input type='text' name='topic' placeholder='' value='' />
					</p>
					<p>
						<h5>Title</h5>
						<select name='category'>
							";
							$select="";
							foreach($categories as $cat){
									$select .= "<option value='".$cat['id']."'>".$cat['name']."</option>";
							}
							$postCard .="".$select."";
							$postCard .="
						</select>
					</p>
					<p>
						<h5>Content</h5>
						<textarea name='content' placeholder='This would be something...'></textarea>
					</p>
					<p>
						<h5>Sources</h5>
						<input type='text' name='sources' placeholder='' value='' />
					</p>
					<p>
						<h5>Attachement</h5>
						<input type='text' name='attachement' placeholder='' value='' />
					</p>

					<button type='submit' name='submit' value='submit' class='view redbk'>SUBMIT</button>
				</form>
			";
		break;

		case 'view':
		break;

		case 'edit':
		break;

		case 'delete':
			$data= array(
				'id'=>$_POST['id'],
			);
			$postDelete = new Posts();
			$postDelete = $postDelete->deletePost($data);

			$posts = new Posts();
			$posts = $posts->getContent($page,"admin");
			$posts = json_decode($posts, true);

			if($postDelete){
				$postCard ="<h3 class='errorMsg'>Success!</h3>";
			}else{
				$postCard ="<h3 class='errorMsg'>Error!</h3>";
			}
			$postCard .= makeCards($currPg,$page,$dir,$select,$posts);
		break;

		case 'active':
			$data= array(
				'id'=>$_POST['id'],
				'active'=>$_POST['activity']
			);
			$postActive = new Posts();
			$postActive = $postActive->activePost($data);

			$posts = new Posts();
			$posts = $posts->getContent($page,"admin");
			$posts = json_decode($posts, true);

			if($postActive){
				$postCard ="<h3 class='errorMsg'>Success!</h3>";
			}else{
				$postCard ="<h3 class='errorMsg'>Error!</h3>";
			}
			$postCard .= makeCards($currPg,$page,$dir,$select,$posts);
		break;
	}
}else{
	$postCard = makeCards($currPg,$page,$dir,$select,$posts);
}



?>
