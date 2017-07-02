<?php 

echo "
<div id='contact' class='row'>
  <h2>Get in <strong>touch</strong>, we'd love to hear from you.</h2>
  <p>Have a question or curious about something that may be done, give us a shout! </p>

  <address class='row'>
	<span class='col-sm-6 col-xs-12'>
	  <i class='ion-location'></i>
	  43 Hanna Ave, #618
	  Toronto, ONT M6K 1X1
	  Canada 
	</span>
	<span class='col-sm-6 col-xs-12'>
	  <i class='ion-ios-telephone'></i>
	  (647) 466-0742
	</span>
	<span class='col-sm-6 col-xs-12'>
	  <i class='ion-email'></i>
	  info@se7en-tech.com
	</span>
  </address>

  <form id='contact_form' class='row' method='post' action='mail/mailer.php'>
	<div class='col-sm-6 col-xs-12'>
	  <input type='name' name='name' class='input-name' placeholder='Name'>
	  <input type='email' name='email' class='input-email' placeholder='Email'>
	</div>

	<div class='col-sm-6 col-xs-12'>
	  <textarea name='message' class='input-message' placeholder='Message'></textarea>
	</div>

	<div class='col-sm-12 col-xs-12'>
	  <button class='submit'>Send</button>
	</div>

	<div id='form-messages' class='col-sm-12 col-xs-12'>
	  <span class='success col-sm-12 col-xs-12'></span>
	  <span class='error col-sm-12 col-xs-12'></span>
	</div>
  </form>
</div>
";

?>