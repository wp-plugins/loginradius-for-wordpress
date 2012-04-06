<?php
/*Plugin Name:Social Login for wordpress  
Plugin URI: http://www.LoginRadius.com
Description: LoginRadius plugin enables social login on a wordpress website letting users log in through their existing IDs such as Facebook, Twitter, Google, Yahoo and over 15 more! This eliminates long registration process i.e. filling up a long registration form, verifying email ID, remembering another username and password so your users are just one click away from logging in to your website. Other than social login, LoginRadius plugin also include User Profile Data and Social Analytics.
Version: 2.3
Author: LoginRadius Team
Author URI: http://www.LoginRadius.com
License: GPL2+
*/
include('LoginRadius_function.php');
include('LoginRadius_header.php');
include('LoginRadius_admin.php');
include('LoginRadiusSDK.php');
//@ini_set('display_errors',0);
$LoginRadiuspluginpath = WP_CONTENT_URL.'/plugins/'.plugin_basename(dirname(__FILE__)).'/';
class Login_Radius_Connect {
public static function init() {
  add_action('parse_request', array(get_class(), 'connect'));
  add_action('wp_enqueue_scripts', array(get_class(), 'LoginRadius_front_css_custom_page'));
  add_filter('LR_logout_url' , array(get_class(), 'log_out_url'), 20, 2);
}
public static function LoginRadius_front_css_custom_page() {
  wp_register_style('LoginRadius-plugin-frontpage-css', plugins_url('lrstyle.css', __FILE__), array(), '1.0.0', 'all');
  wp_enqueue_style('LoginRadius-plugin-frontpage-css');
}			
public static function log_out_url() {
$redirect = get_permalink();
$link = '<a href="' . wp_logout_url($redirect) . '" title="'.e__('Logout', 'LoginRadius').'">'.e__('Logout', 'LoginRadius').'</a>';
echo apply_filters('Login_Radius_log_out_url',$link);
}
public static function connect() {
  $LoginRadius_secret = get_option('LoginRadius_secret');
  $dummyemail = get_option('dummyemail');
  $obj = new LoginRadius();
  $userprofile = $obj->construct($LoginRadius_secret);
  if ($obj->IsAuthenticated == true && !is_user_logged_in() && !is_admin()) {
      $id=$userprofile->ID;
	  $Email = $userprofile->Email[0]->Value;
      $FullName = $userprofile->FullName;
      $ProfileName = $userprofile->ProfileName;
      $Fname = $userprofile->FirstName; 
      $Lname = $userprofile->LastName;
      $id = $userprofile->ID;
      $Provider = $userprofile->Provider;
	  $thumbnail = trim ($userprofile->ImageUrl);
	  if(empty($thumbnail)) {
	   $thumbnail = "http://graph.facebook.com/".$id."/picture";
	  }
	  $aboutme = $userprofile->About;
	  $website = $userprofile->ProfileUrl;
      $user_pass = wp_generate_password();
	if (!empty($userprofile->Email[0]->Value) || $dummyemail == true) {
      self::add_user($Email, $FullName, $ProfileName, $Fname, $Lname, $id, $Provider, $user_pass, $aboutme, $website, $thumbnail);
    }
    if (empty($userprofile->Email[0]->Value) && $dummyemail == false) { 
      global $wpdb;
      $msg = "<p>" . trim(strip_tags(get_option('msg_email'))) . "</p>";
      // look for users with the id match
  $wp_user_id = $wpdb->get_var($wpdb->prepare("SELECT user_id FROM $wpdb->usermeta WHERE meta_key='id' AND meta_value = %s",$id));
      if (!empty($wp_user_id)) {
        // set cookies manually since 
        self::set_cookies($wp_user_id);
        $redirect = LoginRadius_redirect();
        wp_redirect($redirect);
      }
      else { 
        self::popup($FullName, $ProfileName, $Fname, $Lname, $id, $Provider, $aboutme, $website, $thumbnail, $msg); 
      } 
    } //check email ends
  }//autantication ends
  if (isset($_POST['LoginRadiusRedSliderClick'])) {
    $user_email = $_POST['email'];
    if (!is_email($user_email) OR email_exists($user_email)) {
	    $msg = "<p style='color:red;'>" . trim(strip_tags(get_option('msg_existemail'))) ."</p>";
        $id = $_POST['Id'];  
        $Fname = $_POST['fname'];
        $Lname = $_POST['lname'];
        $ProfileName = $_POST['profileName'];
        $FullName = $_POST['fullName'];
        $Provider = $_POST['provider'];
	    $aboutme = $_POST['aboutme'];
        $website = $_POST['website'];
        $thumbnail = $_POST['thumbnail'];
	    self::popup($FullName, $ProfileName, $Fname, $Lname, $id, $Provider, $aboutme, $website, $thumbnail, $msg);
	  }
    else {
      $id = $_POST['Id'];  
      $Email = $_POST['email'];
      $Fname = $_POST['fname'];
      $Lname = $_POST['lname'];
      $ProfileName = $_POST['profileName'];
      $FullName = $_POST['fullName'];
      $Provider = $_POST['provider'];
	  $aboutme = $_POST['aboutme'];
      $website = $_POST['website'];
      $thumbnail = $_POST['thumbnail'];
      $user_pass = wp_generate_password();
      self::add_user($Email, $FullName, $ProfileName, $Fname, $Lname, $id, $Provider, $user_pass, $aboutme, $website, $thumbnail);
    }
  }
}//connect ends
private static function add_user($Email, $FullName, $ProfileName, $Fname, $Lname, $id, $Provider, $user_pass, $aboutme, $website, $thumbnail) {
  //if anything not found correctly
  $dummyemail = get_option('dummyemail'); 
  $Email_id = substr($id,7);
  $Email_id2 = str_replace("/","_",$Email_id);
  switch ($Provider) {
    case 'facebook':
      $username = $Fname.''.$Lname;
      $fname = $Fname;
      $lname = $Lname;
      $email = $Email;
    break;
    case 'twitter':
      $username = $ProfileName;
      $fname = $ProfileName;
      //$lname = $ProfileName;
      if ($dummyemail == false) {
        $email = $Email;
      }
      else {
        $email = $id.'@'.$Provider.'.com';
      }
	break;
    case 'google':
      $username = $Fname.''.$Lname;
      $fname = $Fname;
      $lname = $Lname;
      $email = $Email;
    break;
    case 'yahoo':
      $username = $Fname.''.$Lname;
      $fname = $Fname;
      $lname = $Lname;
      $email = $Email;
    break;
    case 'linkedin':
      $username = $Fname.''.$Lname;
      $fname = $Fname;
      $lname = $Lname;
      if ($dummyemail == false) {
        $email = $Email;
      }
      else {
        $email = $id.'@'.$Provider.'.com';
      }
    break;
    case 'aol':
      $user_name = explode('@',$Email);
      $username = $user_name[0];
      $Name = explode('@',$username);
      $fname = str_replace("_"," ",$Name[0]);
      //$lname = str_replace("_"," ",$Name[0]);
      $email = $Email;
    break;
    case 'hyves':
      $username = $FullName;
      $fname = $FullName;
      //$lname = $FullName;
      $email = $Email;
    break;
    default:
      if ($Fname == '' && $Lname == '' && $FullName != '') { 
        $Fname = $FullName;
      }
      if ($Fname == '' && $Lname == '' && $FullName == '' && $ProfileName != '') {
        $Fname = $ProfileName;
      }
      $Email_id = substr($id,7);
      $Email_id2 = str_replace("/","_",$Email_id);
      if ($Fname == '' && $Lname == '' && $Email == '' && $id != '') {
        $username = $id;
        $fname = $id;
        //$lname = $id;
        $email = str_replace(".","_",$Email_id2).'@'.$Provider.'.com';
      }
      else if ($Fname != '' && $Lname != '' && $Email == '' && $id != '') {
        $username = $Fname.''.$Lname;
        $fname = $Fname;
        $lname = $Lname;
        $email = str_replace(" ","_",$username).'@'.$Provider.'.com';
      }
      else if ($Fname == '' && $Lname == '' && $Email != '') {
        $user_name = explode('@',$Email);
        $username = $user_name[0];
        $Name = explode('@',$username);
        $fname = str_replace("_"," ",$Name[0]);
        //$lname = str_replace("_"," ",$Name[0]);
        $email = $Email;
      }
      else if ($Lname == '' && $Fname != '' && $Email != '') {
        $username = $Fname;
        $fname = $Fname;
        //$lname = $Fname;
        $email = $Email;
      }
      else {
        $username = $Fname.''.$Lname;
        $fname = $Fname;
        $lname = $Lname;
        $email = $Email;
      }
    break;
  }
  global $wpdb;
  $dummyemail = get_option('dummyemail');
  $role = get_option('default_role');
  $sendemail = get_option('sendemail'); 
  //look for user with username match	
  $nameexists = true;
  $index = 0;
  $userName = $username;
  $first_name = $fname;
  while ($nameexists == true) {
    if (username_exists($userName) != 0) {
      $index++;
      $userName = $username.$index;
      $first_name = $fname.$index;
    } 
    else {
      $nameexists = false;
    }
  }
  $username = $userName;
  $userdata = array( 
    'user_login' => $username,
    'user_nicename' => $fname,
    'user_email' => $email, 
    'display_name' => $fname,
    'nickname' => $fname,
    'first_name' => $fname,
    'last_name' => $lname,
	'description' => $aboutme,
    'user_url' => $website,
	'role' => $role
  );
 // look for users with the id match
  $wp_user_id = $wpdb->get_var($wpdb->prepare("SELECT user_id FROM $wpdb->usermeta WHERE meta_key='id' AND meta_value = %s",$id));
  if (empty($wp_user_id)) {
    // Look for a user with the same email
    $wp_user_obj = get_user_by('email', $email);
    // get the userid from the  email if the query failed
    $wp_user_id = $wp_user_obj->ID;
  }
  if (!empty($wp_user_id)) {
    // set cookies manually since wp_signon requires the username/password combo.
    self::set_cookies($wp_user_id);
    $redirect = LoginRadius_redirect();
    wp_redirect($redirect);
  }
  else {  
    if (!empty($email)) {
      //$user_id = wp_create_user( $username,$user_pass,$email );
      $user_id = wp_insert_user($userdata);
      if ($sendemail == false) {
        wp_new_user_notification($username, $user_pass);
      }
    }
    if (!is_wp_error($user_id)) {
      if (!empty($email)) {
        $user = wp_signon(
                         array(
								'user_login' =>$username,
								'user_password' =>$user_pass,
								'remember' => true
							  ), false );
        do_action( 'LR_registration',$user,$username,$email,$user_pass,$userdata);
      }
      if (is_wp_error($user)){}else{}
      if (!empty($email)) {
        update_user_meta($user_id, 'email', $email);
      }
      if (!empty($id)) {
        update_user_meta($user_id, 'id', $id );
      }
	  if (!empty($thumbnail)) {
        update_user_meta($user_id, 'thumbnail', $thumbnail);
      }
      wp_clear_auth_cookie();
      wp_set_auth_cookie($user_id);
      wp_set_current_user($user_id);
	  $redirect=LoginRadius_redirect();
      wp_redirect($redirect);
    } 
    else {
	  wp_redirect($redirect);
    }
  }
}
private static function popup($FullName, $ProfileName, $Fname, $Lname, $id, $Provider, $aboutme, $website, $thumbnail, $msg) {?>
  <div id="popupouter">
  <div id="popupinner">
  <div id="textmatter"><?php if($msg){ echo "<b>".$msg."</b>";}?></div> 
  <form id="wp_login_form"  method="post"  action="">
  <div><input type="text" name="email" id="email" class="inputtxt" /></div>
  <div>
  <input type="submit" id="LoginRadiusRedSliderClick" name="LoginRadiusRedSliderClick" value="Submit" class="inputbutton">
  <input type="submit" value="Cancel" class="inputbutton" onClick="history.back()" />
  <input type="hidden" name="provider" id="provider" value="<?php echo $Provider;?>" />
  <input type="hidden" name="fname" id="fname" value="<?php echo $Fname;?>" />
  <input type="hidden" name="lname" id="lname" value="<?php echo $Lname;?>" />
  <input type="hidden" name="profileName" id="profileName" value="<?php echo $ProfileName;?>" />
  <input type="hidden" name="fullName" id="fullName" value="<?php echo $FullName;?>" />
  <input type="hidden" name="Id" id="Id" value="<?php echo $id;?>" />
  <input type="hidden" name="aboutme" id="aboutme" value="<?php echo $aboutme;?>" />
  <input type="hidden" name="website" id="website" value="<?php echo $website;?>" />
  <input type="hidden" name="thumbnail" id="thumbnail" value="<?php echo $thumbnail;?>" />
  </div>
  </form>
  </div>
  </div><?php }
private static function set_cookies($user_id = 0, $remember = true) {   
  if (!function_exists('wp_set_auth_cookie'))
    return false;
  if (!$user_id)
    return false;
  if (!$user = get_userdata($user_id))
    return false;
    wp_clear_auth_cookie();
    wp_set_auth_cookie($user_id, $remember );
    wp_set_current_user($user_id);
    return true;
}
}//class end
add_action( 'init', array( 'Login_Radius_Connect', 'init' ));

// Avatar showing on comment
function loginradius_custom_avatar ($avatar, $avuser, $size, $default, $alt = '') {
$socialavatar = get_option('socialavatar');
  if ($socialavatar == false) {
    $user_id = null;
  if (is_numeric($avuser)) {
    if ($avuser > 0) {
	  $user_id = $avuser;
    }
  }
  else if(is_object($avuser)) {
	if (property_exists ($avuser, 'user_id') AND is_numeric ($avuser->user_id)) {
	   $user_id = $avuser->user_id;
	}
  }
  if (!empty ($user_id)) {
	if (($user_thumbnail = get_user_meta ($user_id, 'thumbnail', true)) !== false) {
      if (strlen (trim ($user_thumbnail)) > 0) {
        return '<img alt="'. esc_attr($alt) .'" src="'.$user_thumbnail.'" class="avatar avatar-'.$size.' " height="'.$size.'" width="'.$size.'" />';
      }
    }
  }
 }
  return $avatar;
}
add_filter('get_avatar', 'loginradius_custom_avatar', 10, 5);

/**
 * Set the Admin settings on activation on the plugin.
 */
if (! function_exists('esc_attr')) {
  function esc_attr( $text ) {
    return attribute_escape( $text );
  }
}
/**
 * This function makes sure is able to load the different language files from
 * the i18n subfolder 
 **/
function LoginRadius_init_locale(){
	global $LoginRadiuspluginpath;
	load_plugin_textdomain('LoginRadius', false, basename( dirname( __FILE__ ) ) . '/i18n');
}
add_filter('init', 'LoginRadius_init_locale');
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
 */
function LoginRadius_admin_menu() {
  $page=add_options_page('LoginRadius','<b style="color:#0ccdfe;">Login</b><b style="color:#000;">Radius</b>', 8,'LoginRadius', 'LoginRadius_submenu');
  add_action ('admin_print_styles-'.$page, 'LoginRadius_admin_css_custom_page');
}
add_action('admin_menu', 'LoginRadius_admin_menu');
/**
 * Add Settings CSS
 **/
function LoginRadius_admin_css_custom_page() {
    wp_register_style('LoginRadius-plugin-page-css', plugins_url('lrstyle.css', __FILE__), array(), '1.0.0', 'all');
    wp_enqueue_style('LoginRadius-plugin-page-css');
 }
/**
 * Update message, used in the admin panel to show messages to users.
 */
function LoginRadius_message($message) {
	echo "<div id=\"message\" class=\"updated fade\"><p>$message</p></div>\n";
}
/**
 * Add a settings link to the Plugins page, so people can go straight from the plugin page to the
 * settings page.
*/
function LoginRadius_filter_plugin_actions( $links, $file ){
	static $this_plugin;
	if ( ! $this_plugin ) $this_plugin = plugin_basename(__FILE__);
	
	if ( $file == $this_plugin ){
		$settings_link = '<a href="options-general.php?page=LoginRadius">' . __('Settings') . '</a>';
		array_unshift( $links, $settings_link ); // before other links
	}
	return $links;
}
add_filter( 'plugin_action_links', 'LoginRadius_filter_plugin_actions', 10, 2 );?>