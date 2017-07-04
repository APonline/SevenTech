<?php

require_once("inc/config.php");
require_once("model/class.posts.php");


$blogs = new Posts();
$blogs = $blogs->getContent('Blog');
$blogs = json_decode($blogs, true);

//var_dump($featured);

//Generates feature cards
$blogCard="";
foreach($blogs as $index => $blog){
	$blogCard .="
	  <li class='item col-sm-6 col-xs-12'>
		<figure>
		  <!-- Your picture -->
		  <img src='".$blog['media_main']."'  alt='".$blog['topic']."' class='img-responsive' />
		  <!-- Picture's description below this one -->
		  <figcaption class='caption'>
			<div class='photo-details'>
			  <h4>".$blog['title']."</h4>
			  <span class='gray'>".$blog['topic']."</span> /
			  <span class='gray'>".$blog['dated']."</span>
			</div>
			<a href='#project".$index."' class='view redbk'>READ</a>
		  </figcaption>
		</figure>
	  </li>	
	";
} 


//Generates feature card info
$blogCardInfo="";
foreach($blogs as $index => $blog){
	$blogCardInfo .="
	  <li id='project".$index."'>
		<h2 class='projectTitle'>".$blog['title']."</h2>
		<div class='project_content'>
			
			<h5>Gallery -</h5>	
			<div class='gallerySet'>
				<ul>";
				
				foreach($blog['media'] as $i => $mediaItem){
					$blogCardInfo .="
					<li>
					<a href='".$mediaItem['content']."' title='".$mediaItem['title']."'>
						<figure>
						  <!-- Your picture -->
						  <img src='".$mediaItem['content']."' alt='".$mediaItem['title']."' class='img-responsive'>
						<!-- Picture's description below this one -->
						<figcaption class='caption'>
						  <div class='photo-details'>
							<h4>".$i." ".$mediaItem['title']."</h4>
							<span>".$mediaItem['dated']."</span>
						  </div>
						</figcaption>
						</figure>
					</a>
					</li>
					";
				}
				
				$blogCardInfo .="
				</ul>
			</div>	  
		
		  <h5>".$blog['topic']." -</h5>
		  <p>
			".$blog['content']."
		  </p>
		  
		  ";
		  
		  if(isset($blog['sources'])){
		  	$blogCardInfo .="
		  	 <h5>Sources -</h5>
			  <ul>
				".$blog['sources']."
			  </ul>
		  	";
		  }
		  
		  $blogCardInfo .="
		</div>
	  </li>
	";
} 





?>
