<?php
echo "
<div id='leftSideTop'>
  <div class='grid'></div>
  <div id='home' class='gradient'>
  <div class='h-content' style='top: 15vh;text-align: center;font-weight: bold;'>
		<h1 style='font-weight: bold;'>Admin Area</h1>
	</div>
  </div>
</div>
<div id='rightSideTop'>
";
?>

<?php
/*
 * Only the config show be required at this level. Everything else should be below our isset and post checks.
 */
//require "./inc/config.php";

if(isset($_POST['action_login'])){

  $identification = $_POST['login'];
  $password = $_POST['password'];

  if($identification == "" || $password == ""){

    $msg = array("Error", "Username / Password is incorrect.");

  }else{

    $login = \Fr\LS::login($identification, $password, isset($_POST['remember_me']));

    if($login === false){

      $msg = array("Error", "Username / Password is incorrect.");

    }else if(is_array($login) && $login['status'] == "blocked"){

      $msg = array("Error", "Too many login attempts. You can attempt to login after ". $login['minutes'] ." minutes (". $login['seconds'] ." seconds)");
      $enable = "disabled";

    }
  }
}

require_once("template/header.php");

?>

<div class="container-fluid">
  <div class="row">
    <div class="col-sm-3 col-md-3 col-lg-4"></div>
      <div class="col-sm-6 col-md-6 col-lg-4 loginBox">
          <?php

              $enable = "";
              $problem = "";
              if(isset($msg)) $problem =  "<h2>{$msg[0]}</h2><p>{$msg[1]}</p>";
          ?>
          <div class="main-login main-center">
              <form action="login" method="POST">
                <div class="form-group">
                  <label for="username" class="cols-sm-2 control-label">Username</label>
                  <div class="cols-sm-10">
        						<div class="input-group">
        							<span class="input-group-addon"><i class="fa fa-user fa-lg" aria-hidden="true"></i></span>
                      <input type="text" name="login" class="form-control inputField" placeholder="Username OR Email" required autofocus>
        						</div>
        					</div>
                </div>
                <div class="form-group">
    							<label for="password" class="cols-sm-2 control-label">Password</label>
    							<div class="cols-sm-10">
    								<div class="input-group">
    									<span class="input-group-addon"><i class="fa fa-lock fa" aria-hidden="true"></i></span>
                      <input type="password" name="password" class="form-control inputField" id="password" placeholder="Password" required>
    								</div>
    							</div>
    						</div>
                <?php echo $problem; ?>
                <div class="form-group cols-lg-12">
        					<button type="submit" name="action_login" class="btn btn-primary btn-lg btn-block login-button" <?php echo $enable; ?>>Sign in</button>
        				</div>
                <div class="form-group">
                  <input type="checkbox" value="remember-me" id="remember_me" name="remember_me">
                  <label for="remember_me" class="cols-sm-2 control-label">Remember me</label>
                  <a href="reset-password" class="need-help cols-sm-2 pull-right">Forgot Password?</a>
                </div>
              </form>
          </div>
      </div>
      <div class="col-sm-3 col-md-3 col-lg-4"></div>
  </div>
</div>



<?php
$dir = $GLOBALS['dir'];

echo "
	  <div class='screen'></div>
	  <footer>
		<p class='uppercase'>© Se<span style='font-size: 20px;' class='red'>7</span>en Tech | <span class='gray'>Guitar effects</span></p>
		<div class='drag'>
		  <i class='up ion-arrow-up-c'></i>
		</div>
	  </footer>

	</section>
      <script src='".$dir."/assets/js/lib/jquery-1.11.3.min.js'></script>
      <script src='".$dir."/assets/js/lib/bootstrap.min.js'></script>
      <script src='".$dir."/assets/js/lib/animsition.min.js'></script>
      <script src='".$dir."/assets/js/lib/jquery.magnific-popup.min.js'></script>
      <script src='".$dir."/assets/js/lib/jquery.countdown.min.js'></script>
      <script src='".$dir."/assets/js/lib/twitterFetcher_min.js'></script>
      <script src='".$dir."/assets/js/lib/masonry.pkgd.min.js'></script>
      <script src='".$dir."/assets/js/lib/imagesloaded.pkgd.min.js'></script>
      <script src='".$dir."/assets/js/lib/jquery.flexslider-min.js'></script>
      <script src='".$dir."/assets/js/lib/photoswipe.min.js'></script>
      <script src='".$dir."/assets/js/lib/photoswipe-ui-default.min.js'></script>
      <script src='".$dir."/assets/js/lib/jqinstapics.min.js'></script>
      <script src='".$dir."/assets/js/lib/script.js'></script>
      <script src='".$dir."/assets/js/common/main.js'></script>
  </body>
</html>
";

?>
