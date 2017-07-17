<?php

require_once("inc/config.php");
require_once("model/class.posts.php");


$featured = new Posts();
$featured = $featured->getContent('Featured','site');
$featured = json_decode($featured, true);


if(!empty($featured)){
	//Generates feature cards
	$featuredCard="";
	foreach($featured as $index => $feature){
		$featuredCard .="
		  <li class='item col-sm-6 col-xs-12'>
			<figure>
			  <!-- Your picture -->
			  <img src='".$feature['media_main']."'  alt='".$feature['topic']."' class='img-responsive' />
			  <!-- Picture's description below this one -->
			  <figcaption class='caption'>
				<div class='photo-details'>
				  <h4>".$feature['title']."</h4>
				  <span class='gray'>".$feature['topic']."</span>
				</div>
				<a href='#project".$index."' class='view redbk'>VIEW</a>
			  </figcaption>
			</figure>
		  </li>	
		";
	} 


	//Generates feature card info
	$featuredCardInfo="";
	foreach($featured as $index => $feature){
		$featuredCardInfo .="
		  <li id='project".$index."'>
			<h2 class='projectTitle'>".$feature['title']."</h2>
			<div class='project_content'>
			
				<h5>Gallery -</h5>	
				<div class='gallerySet'>
					<ul>";
					foreach($feature['media'] as $i => $mediaItem){
						$featuredCardInfo .="
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
				
					$featuredCardInfo .="
					</ul>
				</div>	  
		
			  <h5>".$feature['topic']." -</h5>
			  <p>
				".$feature['content']."
			  </p>		  
			  ";
		  
			  if($feature['sources']!=""){
				$featuredCardInfo .="
				 <h5>Sources -</h5>
				  <ul>
					".$feature['sources']."
				  </ul>
				";
			  }
		  
			  $featuredCardInfo .="
			</div>
		  </li>
		";
	}
}else{
	$featuredCard ="<li>No Results</li>";
	$featuredCardInfo="";

}




?>
