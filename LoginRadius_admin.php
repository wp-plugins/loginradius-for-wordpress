<?php 
/**
 * Add the LoginRadius menu to the Settings menu
 */
function LoginRadius_restore_config($force=false) {
if ( $force or !( get_option('LoginRadius_apikey')) ) {
		update_option('LoginRadius_apikey',false);
	}
if ( $force or !( get_option('LoginRadius_secret')) ) {
		update_option('LoginRadius_secret',false);
	}
if ( $force or !( get_option('sendemail')) ) {
		update_option('sendemail',false);
	}
if ( $force or !( get_option('useapi')) ) {
		update_option('useapi',false);
	}
if ( $force or !( get_option('socialavatar')) ) {
		update_option('socialavatar',false);
	}	
if ( $force or !( get_option('dummyemail')) ) {
		update_option('dummyemail',false);
	}	
if ( $force or !( get_option('LoginRadius_redirect')) ) {
		update_option('LoginRadius_redirect',false);
	}
if ( $force or !( get_option('title')) ) {
		update_option('title',false);
	}	
if ( $force or !( get_option('msg_email')) ) {
		update_option('msg_email',false);
	}	
if ( $force or !( get_option('msg_existemail')) ) {
		update_option('msg_existemail',false);
	}	
}
/**
 * Displays the LoginRadius admin menu, restores the settings.
 */
function LoginRadius_submenu() {
	global $LoginRadius_known_sites, $LoginRadius_date, $LoginRadiuspluginpath;
    if (isset($_REQUEST['restore']) && $_REQUEST['restore']) {
		check_admin_referer('LoginRadius-config');
		LoginRadius_restore_config(true);
		LoginRadius_message(__("Restored all settings to defaults.", 'LoginRadius'));
	} 
	else if (isset($_REQUEST['save']) && $_REQUEST['save']) {
	
	if (isset($_POST['LoginRadius_apikey']) && $_POST['LoginRadius_apikey']!="") {
			update_option('LoginRadius_apikey',$_POST['LoginRadius_apikey']);
		} else {
			LoginRadius_message(__("You Must Need a Login Radius Api Key For Login Process.", 'LoginRadius'));
		}
		if (isset($_POST['LoginRadius_secret']) && $_POST['LoginRadius_secret']!="") {
			update_option('LoginRadius_secret',$_POST['LoginRadius_secret']);
		} else {
			LoginRadius_message(__("You Must Need a Login Radius Api Secret For Login Process.", 'LoginRadius'));
		}
		if (isset($_POST['title']) && $_POST['title']!="") {
			update_option('title',$_POST['title']);
		} else {
			update_option('title',$_POST['title']=='Please Login with');
		}
		if (isset($_POST['sendemail'])==true && $_POST['sendemail']!="") {
			update_option('sendemail',$_POST['sendemail']==true);
		} else {
			update_option('sendemail',$_POST['sendemail']==false);
		}
		if (isset($_POST['socialavatar'])==true && $_POST['socialavatar']!="") {
			update_option('socialavatar',$_POST['socialavatar']==true);
		} else {
			update_option('socialavatar',$_POST['socialavatar']==false);
		}
		
		if (isset($_POST['dummyemail'])==true && $_POST['dummyemail']!="") {
			update_option('dummyemail',$_POST['dummyemail']==true);
		} else {
			update_option('dummyemail',$_POST['dummyemail']==false);
		}
		if (isset($_POST['useapi']) == true && $_POST['useapi'] != "") {
			update_option('useapi',$_POST['useapi']==true);
		} else {
			update_option('useapi',$_POST['useapi']==false);
		}
		foreach ( array('LoginRadius_loginform', 'LoginRadius_regform', 'LoginRadius_commentform') as $val ) {
			if ( isset($_POST[$val]) && $_POST[$val] )
				update_option($val,true);
			else
				update_option($val,false);
		    }
		if (isset($_POST['msg_email']) && $_POST['msg_email']!="") {
			update_option('msg_email',$_POST['msg_email']);
		} else {
			update_option('msg_email',$_POST['msg_email']=='Please enter your email address to proceed.');
		}
		if (isset($_POST['msg_existemail']) && $_POST['msg_existemail']!="") {
			update_option('msg_existemail',$_POST['msg_existemail']);
		} else {
			update_option('msg_existemail',$_POST['msg_existemail']=='This email is already registered or invalid , please choose another one.');
		}
		$LoginRadius_redirect = $_POST['LoginRadius_redirect'];
		if ($LoginRadius_redirect=='samepage' && $LoginRadius_redirect!="") {
		$samepage = 'checked';
			update_option('LoginRadius_redirect',$LoginRadius_redirect);
		} 
		if ($LoginRadius_redirect=='homepage' && $LoginRadius_redirect!="") {
		$homepage = 'checked';
			update_option('LoginRadius_redirect',$LoginRadius_redirect);
		} 
		else if($LoginRadius_redirect=='dashboard'){
		$dashboard = 'checked';
			update_option('LoginRadius_redirect',$LoginRadius_redirect);
		}
		else if($LoginRadius_redirect=='custom'){
		$custom = 'checked';
			update_option('LoginRadius_redirect',$LoginRadius_redirect);
		}
		else{
		update_option('LoginRadius_redirect',$LoginRadius_redirect == 'samepage');
		}
		if($LoginRadius_redirect=='custom' && $custom == 'checked' && isset($_POST['LoginRadius_redirect_custom_redirect'])!="")
		{
		update_option('LoginRadius_redirect_custom_redirect',$_POST['LoginRadius_redirect_custom_redirect']);
		}
		if($LoginRadius_redirect=='custom' && $custom == 'checked' && $_POST['LoginRadius_redirect_custom_redirect']=="")
		{
			LoginRadius_message(__("You Need a Redirect url for Login Redirection.", 'LoginRadius'));
		}
		check_admin_referer('LoginRadius-config');
		LoginRadius_message(__("Saved changes.", 'LoginRadius'));
	}
	/**
	 * Display options.
	 */?>
<form action="<?php echo attribute_escape( $_SERVER['REQUEST_URI'] ); ?>" method="post">
<?php if ( function_exists('wp_nonce_field') )
		wp_nonce_field('LoginRadius-config');?>

<div class="wrap">
	<?php //screen_icon();?>
	<h2><b style='color:#00ccff;'>Login</b><b>Radius</b> <?php _e('Settings', 'LoginRadius');?></h2>
	<div class="LoginRadius_container_outer">
		<div class="LoginRadius_container">
			<h3><?php _e('Thank you for installing the LoginRadius plugin!', 'LoginRadius');?></h3><p>
<?php _e('You can customize the settings for your plugin on this page, though you will have to choose your desired ID providers and get your unique', 'LoginRadius');?> <strong> <?php _e('LoginRadius API Key & Secret', 'LoginRadius');?> </strong><?php _e('from', 'LoginRadius');?> <a href="http://www.LoginRadius.com" target="_blank">www.LoginRadius.com.</a> <?php _e('In order to make the login process secure, we require you to manage it from your LoginRadius account.', 'LoginRadius');?></p>
<p><strong>LoginRadius</strong> <?php _e('is a North America based technology company that offers social login through popular hosts such as Facebook, Twitter, Google and over 15 more! For tech support or if you have any questions, please contact us at', 'LoginRadius');?> <strong>hello@loginradius.com.</strong></p><h3><?php _e('We are available 24/7 to assist our clients!', 'LoginRadius');?></h3>
<p>
<a class="button-secondary" href="http://www.loginradius.com/" target="_blank"><strong><?php _e('Create your FREE account now!', 'LoginRadius');?></strong></a>
</p>
		</div>
		<div class="LoginRadius_container_inner">
		<h3 style="color:black;"><?php _e('Plugin Help', 'LoginRadius');?></h3>
		<p><ul class="LoginRadius_container_links">
		<li><a href="http://www.loginradius.com/loginradius/plugins.aspx" target="_blank"><?php _e('Documentation', 'LoginRadius');?></a></li>
		<li><a href="http://wordpress.org/extend/plugins/loginradius-for-wordpress/" target="_blank"><?php _e('Plugin webpage', 'LoginRadius');?></a></li>
		<li><a href="http://wordpressdemo.loginradius.com/" target="_blank"><?php _e('Live Demo site', 'LoginRadius');?></a></li>
		<li><a href="http://www.loginradius.com/loginradius/" target="_blank"><?php _e('About LoginRadius', 'LoginRadius');?></a></li>
		<li><a href="http://blog.loginradius.com/" target="_blank"><?php _e('LoginRadius Blog', 'LoginRadius');?></a></li>
		<li><a href="http://www.loginradius.com/loginradius/plugins.aspx" target="_blank"><?php _e('Other LoginRadius plugins', 'LoginRadius');?></a></li>
		
		<li><a href="http://www.loginradius.com/loginradius/writetous.aspx" target="_blank"><?php _e('Tech Support', 'LoginRadius');?></a></li>
		<br /><br />
		</ul>
        </p>
		</div>
	</div>
	<table class="form-table LoginRadius_table">
	<tr>
	<th class="head" colspan="2"><?php _e('LoginRadius API Settings', 'LoginRadius');?></small></th>
	</tr>
	<tr >
	<th scope="row">LoginRadius<br /><small>API Key</small></th>
	<td><?php _e("Paste LoginRadius API Key here. To get the API Key, log in to", 'LoginRadius'); ?> 
<a href='http://www.LoginRadius.com/' target='_blank'>LoginRadius.</a><br/>
<input size="60" type="text" name="LoginRadius_apikey" id="LoginRadius_apikey" value="<?php echo get_option('LoginRadius_apikey' ); ?>" /></td>
	</tr>
	<tr >
	<th scope="row">LoginRadius<br /><small><?php _e('API Secret', 'LoginRadius');?></small></th>
	<td><?php _e("Paste LoginRadius API Secret here. To get the API Secret, log in to ", 'LoginRadius'); ?><a href='http://www.LoginRadius.com/' target='_blank'>LoginRadius.</a><br/>
		<input size="60" type="text" name="LoginRadius_secret" id="LoginRadius_secret" value="<?php echo get_option('LoginRadius_secret'); ?>" /></td>
	</tr>
	<tr>
	<th scope="row"><?php _e("Select API Credential", 'LoginRadius'); ?><br /><small><?php _e("To Communicate with API", 'LoginRadius'); ?></small></th>
	<td>
<input name="useapi" type="radio"  value="0" <?php checked( '0', get_option( 'useapi' ) ); ?> checked /><?php _e("Use cURL (Require cURL support = enabled in your php.ini settings)", 'LoginRadius'); ?> <br />
<input name="useapi" type="radio" value="1" <?php checked( '1', get_option( 'useapi') ); ?>  /><?php _e("Use FSOCKOPEN (Require allow_url_fopen = On and safemode = off in your php.ini settings)", 'LoginRadius'); ?> 
</td>
	</tr>
	</table>
	</table>
<table class="form-table LoginRadius_table">
	<tr>
	<th class="head" colspan="2"><?php _e('LoginRadius Login & Redirection Settings', 'LoginRadius');?></small></th>
	</tr>
<tr >
<th scope="row"><?php _e("Show Social Icons On", 'LoginRadius'); ?><br /><small><?php _e("where to show social icons", 'LoginRadius'); ?></small></th>
	<td>
<input type="checkbox" name="LoginRadius_loginform" <?php checked( get_option('LoginRadius_loginform'), true ) ; ?> /> <?php _e ('Show on Login Form', 'LoginRadius'); ?> <br />

<input type="checkbox" name="LoginRadius_regform" <?php checked( get_option('LoginRadius_regform'), true ) ; ?> /> <?php _e ('Show on Register Form', 'LoginRadius'); ?> 
<br />
<input type="checkbox" name="LoginRadius_commentform" <?php checked( get_option('LoginRadius_commentform'), true ) ; ?> /> <?php _e ('Show on Comment Form', 'LoginRadius'); ?> 
</td>
</tr>
<tr class="row_white">
	<th scope="row"><?php _e("Show Social Avatar", 'LoginRadius'); ?><br /><small><?php _e("social provider avatar image on comment", 'LoginRadius'); ?></small></th>
	<td>
<input name="socialavatar" type="radio"  value="0" <?php checked( '0', get_option( 'socialavatar' ) ); ?> checked /><?php _e("Use Social provider avatar (if provide)", 'LoginRadius'); ?> <br />
<input name="socialavatar" type="radio" value="1" <?php checked( '1', get_option( 'socialavatar') ); ?>  /><?php _e("Use default avatar", 'LoginRadius'); ?> 
</td>
	</tr>
	<tr >
<th scope="row"><?php _e("Redirection Setting", 'LoginRadius'); ?><br /><small><?php _e("Redirect user After login", 'LoginRadius'); ?></small></th>
	<td>
<input type="radio" name="LoginRadius_redirect" value="samepage" <?php checked( 'samepage', get_option( 'LoginRadius_redirect' )); ?> checked /> <?php _e ('Redirect to Same Page of blog', 'LoginRadius'); ?> <strong>(<?php _e ('Default', 'LoginRadius') ?>)</strong><br />

<input type="radio" name="LoginRadius_redirect" value="homepage" <?php checked( 'homepage', get_option( 'LoginRadius_redirect' )); ?> /> <?php _e ('Redirect to homepage of blog', 'LoginRadius'); ?> 
<br />
<input type="radio" name="LoginRadius_redirect" value="dashboard" <?php checked( 'dashboard', get_option( 'LoginRadius_redirect' )); ?> /> <?php _e ('Redirect to account dashboard', 'LoginRadius'); ?>
<br />
<input type="radio" name="LoginRadius_redirect" value="custom" <?php checked( 'custom', get_option( 'LoginRadius_redirect' )); ?> /> <?php _e ('Redirect to the following url:', 'LoginRadius'); ?>
<br />
<input type="text"  name="LoginRadius_redirect_custom_redirect" size="60" value="<?php if($LoginRadius_redirect=='custom' && $custom == 'checked'){echo htmlspecialchars(get_option('LoginRadius_redirect_custom_redirect'));}else{} ?>" />
</td>
</tr>
</table>
<table class="form-table LoginRadius_table">
	<tr>
	<th class="head" colspan="2"><?php _e("LoginRadius Basic Settings", 'LoginRadius'); ?></small></th>
	</tr>
	<tr>
	<th scope="row"><?php _e("Title", 'LoginRadius'); ?></th>
	<td><?php _e("This text displyed above the Social login button.", 'LoginRadius'); ?>
	<br />
<input type="text"  name="title" size="60" value="<?php if(htmlspecialchars(get_option('title'))){echo htmlspecialchars(get_option('title'));}else{ _e('Please Login with', 'LoginRadius');} ?>" />
</td>
	</tr>
	<tr class="row_white">
	<th scope="row"><?php _e("Send Email", 'LoginRadius'); ?><br /><small><?php _e("After user register", 'LoginRadius'); ?></small></th>
	<td><?php _e("Select YES if you would to like send an email to user after register.", 'LoginRadius'); ?>
	<br />
<?php _e("Yes", 'LoginRadius'); ?> <input name="sendemail" type="radio"  value="0" <?php checked( '0', get_option( 'sendemail' ) ); ?> checked />&nbsp;&nbsp;&nbsp;&nbsp;
<?php _e("No", 'LoginRadius'); ?> <input name="sendemail" type="radio" value="1" <?php checked( '1', get_option( 'sendemail' ) ); ?>  />
</td>
	</tr>
	<tr>
	<th scope="row"><?php _e("Email Required", 'LoginRadius'); ?></th>
	<td><?php _e("A few ID providers do not provide user's Email ID. Select YES if you would like an email pop-up after login or select NO if you would like to auto-generate the email address.", 'LoginRadius'); ?>
	</td>
	</tr>
	<tr>
	<th></th>
	<td>
	<?php _e("Yes", 'LoginRadius'); ?> <input name="dummyemail" type="radio"  value="0" <?php checked( '0', get_option( 'dummyemail' ) ); ?> checked />&nbsp;&nbsp;&nbsp;&nbsp;
<?php _e("No", 'LoginRadius'); ?> <input name="dummyemail" type="radio" value="1" <?php checked( '1', get_option( 'dummyemail' ) ); ?>  />
	</td>
	</tr>
	<tr class="row_white">
	<th></th>
	<td> 
<?php if($_POST['dummyemail'] == '1') {}
else {
 _e("This text will be displyed above the popup box for entering email.", 'LoginRadius'); ?> <br/>
<input size="60" type="text" name="msg_email" id="msg_email" value="<?php if(htmlspecialchars(get_option('msg_email'))){echo htmlspecialchars(get_option('msg_email'));}else{ _e('Please enter your email address to proceed.', 'LoginRadius');} ?>" /><br />
<?php _e("This text will be displyed above the popup box if email already registered or invalid.", 'LoginRadius'); ?> <br/>
<input size="60" type="text" name="msg_existemail" id="msg_existemail" value="<?php if(htmlspecialchars(get_option('msg_existemail'))){echo htmlspecialchars(get_option('msg_existemail'));}else{ _e('This email is already registered or invalid , please choose another one.', 'LoginRadius');} ?>" />
<?php }?>
</td>
</tr>
<table>
<tr>
<td>&nbsp;</td>
<td>
<span class="submit"><input name="save" value="<?php _e("Save Changes", 'LoginRadius'); ?>" type="submit" class="button-primary"/></span>
<span class="submit"><input name="restore" value="<?php _e("Restore Defaults", 'LoginRadius'); ?>" type="submit" class="button-primary"/></span>
</td>
</tr>
</table>
</div>
</form>
<?php }?>