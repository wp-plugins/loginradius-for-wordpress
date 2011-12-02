<?php
/*Plugin Name:LoginRadius for Wordpress  
Plugin URI: http://www.LoginRadius.com
Description: Imaging, users need not to go through long registration process but log in via their existing IDs. Now, it is possible through LoginRadius which enables social login such as Facebook, Twitter, Google and over 15 more on a Wordpress website. Users can directly log in through these IDs. It also fetches User Profile Data and store them in your database. An advance social analytics is also available in your LoginRadius. To understand the concept of LoginRadius, please watch our <a href='http://www.youtube.com/LoginRadius' target='_blank'>2 min video</a> and browse <a href='http://www.loginradius.com/developers/gettingstarted' target='_blank'>developers section</a> for technical assistance.
Version: 1.0
Author: LoginRadius Team
Author URI: http://www.LoginRadius.com
License: GPL2+
*/
include('function.php');
include('header.php');
include('LoginRadiusSDK.php');
define('LOGINRADIUS_PATH_ROOT', dirname(__FILE__));
define('LOGINRADIUS_FILES_URL', plugins_url('LoginRadius/js/', LOGINRADIUS_PATH_ROOT));
@ini_set('display_errors',0);
$LoginRadiuspluginpath = WP_CONTENT_URL.'/plugins/'.plugin_basename(dirname(__FILE__)).'/';

class Login_Radius_Connect {
public static function init() {
			add_action( 'parse_request', array(get_class(), 'connect') );
		    //add_action( 'wp_enqueue_scripts', array(get_class(), 'enqueue' ) );
			add_filter( 'LR_logout_url' , array(get_class(), 'log_out_url'), 20, 2);
					}
public static function log_out_url() {
				$redirect= get_permalink();
			    $link = '<a href="' . wp_logout_url($redirect) . '" title="'.e__('Logout').'">'.e__('Logout').'</a>';
			             echo apply_filters('Login_Radius_log_out_url',$link);
		                 }
public static function connect() {
                            $LoginRadius_secret=get_option('LoginRadius_secret');
							$dummyemail=get_option('dummyemail');
							$obj = new LoginRadius();
                            $userprofile = $obj->construct($LoginRadius_secret);
              if($obj->IsAuthenticated == true && !is_user_logged_in() && !is_admin()) 
                  {
			         if(!empty($userprofile->Email[0]->Value) || $dummyemail==true)
						{
										 $Email=$userprofile->Email[0]->Value;
										 $FullName=$userprofile->FullName;
										 $ProfileName=$userprofile->ProfileName;
										 $Fname=$userprofile->FirstName; 
										 $Lname=$userprofile->LastName;
										 $id=$userprofile->ID;
										 $Provider=$userprofile->Provider;
										 $user_pass=wp_generate_password();	
						}
                     if (empty($userprofile->Email[0]->Value) && $dummyemail==false)
						{?>
											<script type="text/javascript" src='<?php echo LOGINRADIUS_FILES_URL;?>jquery.js'></script>
											<script  type="text/javascript" src='<?php echo LOGINRADIUS_FILES_URL;?>jquery.bpopup-0.5.1.min.js'></script>
											<script type="text/javascript">
											$(document).ready(function () {
											$('body').append('<style>#LoginRadiusRedSlider{display:none;}</style>');
											$('#LoginRadiusRedSlider').bPopup();
											});
											</script>
							<div id="LoginRadiusRedSlider" style="padding:20px;height:120px; width:300px; background:#FFFFFF; border:2px solid #00CCFF;">
							<p><b>Please enter your email address to proceed further</b></p><br />
							<form id="wp_login_form"  method="post" enctype="multipart/form-data" action="">
							<input type="text" name="email" id="email" />
							<input type="submit" id="LoginRadiusRedSliderClick" name="LoginRadiusRedSliderClick" value="Submit">
							<input type="hidden" name="provider" id="provider" value="<?php echo $userprofile->Provider;?>" />
							<input type="hidden" name="fname" id="fname" value="<?php echo $userprofile->FirstName;?>" />
							<input type="hidden" name="lname" id="lname" value="<?php echo $userprofile->LastName;?>" />
							<input type="hidden" name="profileName" id="profileName" value="<?php echo $userprofile->ProfileName;?>" />
							<input type="hidden" name="fullName" id="fullName" value="<?php echo $userprofile->FullName;?>" />
							<input type="hidden" name="Id" id="Id" value="<?php echo $userprofile->ID;?>" />
							</form></div>
                        <?php } 
                             self::add_user($Email,$FullName,$ProfileName,$Fname,$Lname,$id,$Provider,$user_pass);
                    }//autantication ends
if($_POST['Id'] && !is_user_logged_in() && !is_admin())
				  {
					 $id=$_POST['Id'];  
					 $Email=urldecode($_POST['email']);
					 $Fname=$_POST['fname'];
					 $Lname=$_POST['lname'];
					 $ProfileName=$_POST['profileName'];
					 $FullName=$_POST['fullName'];
					 $Provider=$_POST['provider'];
					 $user_pass=wp_generate_password();
                     self::add_user($Email,$FullName,$ProfileName,$Fname,$Lname,$id,$Provider,$user_pass);
                  }
}//connect ends
private static function add_user($Email,$FullName,$ProfileName,$Fname,$Lname,$id,$Provider,$user_pass)
{
//if anything not found correctly 
$Email_id=substr($id,7);
$Email_id2=str_replace("/","_",$Email_id);
switch( $Provider ){
		case 'facebook':
					 $username=$Fname.$Lname;
					 $fname=$Fname;
					 $lname=$Lname;
					 $email=$Email;
                     break;
        case 'twitter':
				$username=$ProfileName;
				$fname=$ProfileName;
				$lname=$ProfileName;
					if ($dummyemail==false){
					$email=$Email;}
					else{
					$email=$id.'@'.$Provider.'user.com';}
					break;
        case 'google':
					$username=$Fname.$Lname;
					$fname=$Fname;
					$lname=$Lname;
					$email=$Email;
					break;
        case 'yahoo':
					$username=$Fname.$Lname;
					$fname=$Fname;
					$lname=$Lname;
					$email=$Email;
					break;
        case 'linkedin':
					$username=$Fname.$Lname;
					$fname=$Fname;
					$lname=$Lname;
					if ($dummyemail==false){
					$email=$Email;}
					else{
					$email=$id.'@'.$Provider.'.com';}
					break;
		case 'aol':
					$user_name=explode('@',$Email);
					$username=$user_name[0];
					$Name=explode('@',$username);
					$fname=str_replace("_"," ",$Name[0]);
					$lname=str_replace("_"," ",$Name[0]);
					$email=$Email;
		            break;
		case 'hyves':
					$username=$Fname.$Lname;
					$fname=$Fname;
					$lname=$Lname;
					$email=$Email;
					break;
		default:
				if($Fname=='' && $Lname=='' && $FullName!='')
				{ $Fname=$FullName;}
				if($Fname=='' && $Lname=='' && $FullName=='' && $ProfileName!='')
				   {$Fname=$ProfileName;}
				$Email_id=substr($id,7);
				$Email_id2=str_replace("/","_",$Email_id);
				if($Fname=='' && $Lname=='' && $Email=='' && $id!='')
				{
				$username=$id;
				$fname=$id;
				$lname=$id;
				$email=str_replace(".","_",$Email_id2).'@'.$Provider.'.com';
				}
					else if($Fname!='' && $Lname!='' && $Email=='' && $id!=''){
					$username=$Fname.$Lname;
					$fname=$Fname;
					$lname=$Lname;
					$email=str_replace(" ","_",$username).'@'.$Provider.'.com';
					}
					else if($Fname=='' && $Lname=='' && $Email!=''){
							$user_name=explode('@',$Email);
							$username=$user_name[0];
							$Name=explode('@',$username);
							$fname=str_replace("_"," ",$Name[0]);
							$lname=str_replace("_"," ",$Name[0]);
							$email=$Email;
							}
							else if($Lname=='' && $Fname!='' && $Email!=''){
							$username=$Fname;
							$fname=$Fname;
							$lname=$Fname;
							$email=$Email;
							}
								else {
								$username=$Fname.$Lname;
								$fname=$Fname;
								$lname=$Lname;
								$email=$Email;
								}
               break;
              }
global $wpdb;
$dummyemail=get_option('dummyemail');
//look for user with username match	
 						  $nameexists = true;
						  $index = 0;
						  $userName = $username;
						  $first_name=$fname;
						  while ($nameexists == true) {
							if (username_exists($userName) != 0) {
							  $index++;
							  $userName = $username.$index;
							  $first_name=$fname.$index;
							} else {
							  $nameexists = false;
							}
						  }
						  $username=$userName;
						 
                    $userdata = array( 
                               'user_login' => $username,
							   'user_nicename' => $fname,
							   'user_email' => $email, 
							   'display_name' => $fname,
                               'nickname' => $fname,
                               'first_name' => $fname,
							   'user_url' => home_url(),
							   'role' => 'Subscriber'
							   );
							   
// look for users with the id match
$wp_user_id = $wpdb->get_var($wpdb->prepare("SELECT user_id FROM $wpdb->usermeta WHERE meta_key='id' AND meta_value = %s",$id));
				if ( empty($wp_user_id) ) {
					// Look for a user with the same email
					$wp_user_obj = get_user_by('email', $email);
                   // get the userid from the  email if the query failed
					$wp_user_id = $wp_user_obj->ID;
					}
				    if ( !empty($wp_user_id) ) {
					// set cookies manually since wp_signon requires the username/password combo.
					self::set_cookies($wp_user_id);
					$redirect=home_url();
					wp_redirect($redirect);
					}
					else {  
					if (!empty($email)) {
					  $user_id = wp_create_user( $username,$user_pass, $email );
					  }
                      if (! is_wp_error($user_id) ) 
					  {
					  if (!empty($email)) {
					   $user = wp_signon(
											array(
												'user_login' =>$username,
												'user_password' =>$user_pass,
												'remember' => true
											), false );
                        do_action( 'LR_registration',$user,$username,$email,$user_pass,$userdata);
						}
if( is_wp_error( $user ))
{}
else
{ //wp_redirect($redirect);
}
if (!empty($email)) {
update_user_meta($user_id,'email',$email);
}
if (!empty($id)) {
update_user_meta($user_id,'id',$id );
}
						  wp_clear_auth_cookie();
						  wp_set_auth_cookie($user_id);
			              wp_set_current_user($user_id);
						  $redirect=home_url();
						  wp_redirect($redirect);
						  } 
else {
wp_redirect($redirect);
}}
}
private static function set_cookies( $user_id = 0, $remember = true ) 
			{   
			   if ( !function_exists( 'wp_set_auth_cookie' ) )
				return false;
			   if (!$user_id)
				return false;
			   if ( !$user = get_userdata( $user_id ) )
				return false;
				wp_clear_auth_cookie();
				wp_set_auth_cookie( $user_id, $remember );
				wp_set_current_user( $user_id );
				return true;
			}
			
}//class end
add_action( 'init', array( 'Login_Radius_Connect', 'init' ));
			if (! function_exists('esc_attr')) {
				function esc_attr( $text ) {
					return attribute_escape( $text );
				}
             }
/**
 * Set the default settings on activation on the plugin.
 */
function LoginRadius_activation_hook() {
	global $wpdb;
	$wpdb->query("DELETE FROM $wpdb->postmeta WHERE meta_key = 'LoginRadiusoff'");
	return LoginRadius_restore_config(false);
     }
register_activation_hook(__FILE__, 'LoginRadius_activation_hook');

/**
 * Add the LoginRadius menu to the Settings menu
 * @param boolean $force if set to true, force updates the settings.
 */
function LoginRadius_restore_config($force=false) {
if ( $force or !( get_option('LoginRadius_apikey')) ) {
		update_option('LoginRadius_apikey',false);
	}
	
if ( $force or !( get_option('LoginRadius_secret')) ) {
		update_option('LoginRadius_secret',false);
	}
if ( $force or !( get_option('dummyemail')) ) {
		update_option('dummyemail',false);
	}	
	}
/**
 * Add the LoginRadius menu to the Settings menu
 */
function LoginRadius_admin_menu() {
add_options_page('LoginRadius','<b style="color:#0ccdfe;">Login</b><b style="color:#000;">Radius</b>', 8,'LoginRadius', 'LoginRadius_submenu');
}
add_action('admin_menu', 'LoginRadius_admin_menu');
/**
 * Update message, used in the admin panel to show messages to users.
 */
function LoginRadius_message($message) {
	echo "<div id=\"message\" class=\"updated fade\"><p>$message</p></div>\n";
}
/**
 * Displays the LoginRadius admin menu, first section (re)stores the settings.
 */
function LoginRadius_submenu() {
	global $LoginRadius_known_sites, $LoginRadius_date, $LoginRadiuspluginpath;
    if (isset($_REQUEST['restore']) && $_REQUEST['restore']) {
		check_admin_referer('LoginRadius-config');
		LoginRadius_restore_config(true);
		LoginRadius_message(__("Restored all settings to defaults.", 'LoginRadius'));
	} else if (isset($_REQUEST['save']) && $_REQUEST['save']) {
	
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
		if (isset($_POST['dummyemail'])==true && $_POST['dummyemail']!="") {
			update_option('dummyemail',$_POST['dummyemail']==true);
		} else {
			update_option('dummyemail',$_POST['dummyemail']==false);
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
	<?php screen_icon();?>
	<h2><?php _e("Login Radius Options", 'LoginRadius'); ?></h2>
	<table class="form-table">
	<tr>
	<th>LoginRadius<br /><small>API Key</small></th>
	<td><?php _e("Paste LoginRadius API Key here. To get the API Key, log in to 
<a href='http://www.LoginRadius.com/' target='_blank'>LoginRadius.</a>", 'LoginRadius'); ?><br/>
<input size="80" type="text" name="LoginRadius_apikey" id="LoginRadius_apikey" value="<?php echo get_option('LoginRadius_apikey' ); ?>" /></td>
	</tr>
	<tr>
	<th>LoginRadius<br /><small>API Secret</small></th>
	<td><?php _e("Paste LoginRadius API Secret here. To get the API Secret, log in to <a href='http://www.LoginRadius.com/' target='_blank'>LoginRadius.</a>", 'LoginRadius'); ?><br/>
		<input size="80" type="text" name="LoginRadius_secret" id="LoginRadius_secret" value="<?php echo get_option('LoginRadius_secret' ); ?>" /></td>
	</tr>
	<tr>
	<th>Email Required</th>
	<td><?php _e("Few ID providers do not provide user's Email ID. Select YES if you want a email pop-up after login or select NO if you want to auto generate the email address.", 'LoginRadius'); ?><br/>
Yes <input name="dummyemail" type="radio"  value="0" <?php checked( '0', get_option( 'dummyemail' ) ); ?> checked /><br />
No&nbsp;&nbsp;<input name="dummyemail" type="radio" value="1" <?php checked( '1', get_option( 'dummyemail' ) ); ?>  />

</td>
	</tr>
<tr>
		<td>&nbsp;</td>
		<td>
			<span class="submit"><input name="save" value="<?php _e("Save Changes", 'LoginRadius'); ?>" type="submit" class="button-primary"/></span>
			<span class="submit"><input name="restore" value="<?php _e("Restore Defaults", 'LoginRadius'); ?>" type="submit" class="button-primary"/></span>
		</td>
	</tr>
</table></div>
</form>
<?php }
/**
 * Add a settings link to the Plugins page, so people can go straight from the plugin page to the
 * settings page.
*/
function LoginRadius_filter_plugin_actions( $links, $file ){
	// Static so we don't call plugin_basename on every plugin row.
	static $this_plugin;
	if ( ! $this_plugin ) $this_plugin = plugin_basename(__FILE__);
	
	if ( $file == $this_plugin ){
		$settings_link = '<a href="options-general.php?page=LoginRadius">' . __('Settings') . '</a>';
		array_unshift( $links, $settings_link ); // before other links
	}
	return $links;
}
add_filter( 'plugin_action_links', 'LoginRadius_filter_plugin_actions', 10, 2 );?>