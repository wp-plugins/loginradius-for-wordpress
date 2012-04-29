<?php 
function Login_Radius_Connect_button() {
  $LoginRadius_apikey = get_option('LoginRadius_apikey');
  $LoginRadius_secret = get_option('LoginRadius_secret');
  $title = get_option('title');
  if (!is_user_logged_in()) { ?>
      <div style="margin-bottom: 3px;"><label><?php _e( $title, 'LoginRadius' );?>:</label></div>
	 <?php 
	if ($LoginRadius_apikey != "") :
        require_once('LoginRadiusSDK.php');
		$obj_auth = new LoginRadiusAuth();
        $UserAuth = $obj_auth->auth($LoginRadius_apikey, $LoginRadius_secret);
	    $IsHttps = $UserAuth->IsHttps;
		$iframeHeight = $UserAuth->height;
		if (!$iframeHeight) {
		$iframeHeight = 50;
		}
		$iframeWidth = $UserAuth->width;
		if (!$iframeWidth) {
		$iframeWidth = 169;
		}
		if($IsHttps == 1) {
		    $loc = urlencode("https://".$_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
		  }
		  else {
            $loc=urlencode("http://".$_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
		  }
		if(urldecode($loc) == wp_login_url() OR urldecode($loc) == site_url().'/wp-login.php?action=register' OR urldecode($loc) ==site_url().'/wp-login.php?loggedout=true') {
		    $loc = site_url().'/';
        }
		elseif (urldecode($_GET['redirect_to']) == admin_url()) {
		  $loc = site_url().'/';
        }
		elseif (isset($_GET['redirect_to'])) {
		  $loc = $_GET['redirect_to'];
		}
	    else {
		  if($IsHttps == 1) {
		    $loc = urlencode("https://".$_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
		  }
		  else {
            $loc = urlencode("http://".$_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
		  }
		}
?>
<?php if ($IsHttps == 1) {?>
<iframe src="https://hub.loginradius.com/Control/PluginSlider.aspx?apikey=<?php echo $LoginRadius_apikey;?>&callback=<?php echo $loc;?>" width="<?php echo $iframeWidth;?>" height="<?php echo $iframeHeight;?>" frameborder="0" scrolling="no" ></iframe>
	<?php } 
        else {?>
<iframe src="http://hub.loginradius.com/Control/PluginSlider.aspx?apikey=<?php echo $LoginRadius_apikey;?>&callback=<?php echo $loc;?>" width="<?php echo $iframeWidth;?>" height="<?php echo $iframeHeight;?>" frameborder="0" scrolling="no" ></iframe>
<?php } endif; ?>
<?php }
// On user Login show user details.
  if (is_user_logged_in() && !is_admin()) {
	global $user_ID; 
	$user = get_userdata( $user_ID );
	_e("Welcome! "."".$user->user_login, 'LoginRadius');
	$redirect = get_permalink();?>
	<br />
    <a href="<?php echo wp_logout_url($redirect);?>"><?php _e('Log Out', 'LoginRadius');?></a>
	<?php 
  }
}
// Show interface according to user choice.
  if(get_option('LoginRadius_loginform') == 1) {
    add_action( 'login_form','Login_Radius_Connect_button');
  }
  if(get_option('LoginRadius_regform') == 1) {
    add_action( 'register_form', 'Login_Radius_Connect_button');
    add_action( 'after_signup_form','Login_Radius_Connect_button');
  }
  if(get_option('LoginRadius_commentform') == 1) {
    if ( get_option('comment_registration') && !$user_ID ) {
      add_action( 'comment_form_must_log_in_after','Login_Radius_Connect_button');
    }
    else {
      add_action( 'comment_form_top','Login_Radius_Connect_button');
    }
  }
  // Redirection after user login.
function LoginRadius_redirect() {
  $LoginRadius_redirect = get_option('LoginRadius_redirect');
  $LoginRadius_redirect_custom_redirect = get_option('LoginRadius_redirect_custom_redirect');
  $redirect_to = site_url();
  $redirect_to_safe = false;
  if (! empty ($_GET['redirect_to'])) {
    $redirect_to = $_GET['redirect_to'];
    $redirect_to_safe = true;
  }
  else {
    if (isset($LoginRadius_redirect)) {
      switch (strtolower($LoginRadius_redirect)) {
        case 'homepage':
          $redirect_to = site_url().'/';
		break;
		case 'dashboard':
		  $redirect_to = admin_url();
		break;
		case 'custom':
		  if (isset ($LoginRadius_redirect) && strlen(trim($LoginRadius_redirect_custom_redirect)) > 0) {
            $redirect_to = trim($LoginRadius_redirect_custom_redirect);
          }
		break;
		default:
		case 'samepage':
		  $redirect_to = $_GET['callback'];
		break;
	  }
    }
  }
  if ($redirect_to_safe) {
    wp_redirect($redirect_to);
  }
  else {
    wp_safe_redirect($redirect_to);
  }
}?>