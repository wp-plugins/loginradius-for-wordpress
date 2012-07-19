<?php 
class LoginRadius {
  public $IsAuthenticated, $JsonResponse, $UserProfile; 
  public function construct($ApiSecrete) {
    $IsAuthenticated = false;
	$LoginRadius_settings = get_option('LoginRadius_settings');
	$useapi = $LoginRadius_settings['LoginRadius_useapi'];
    if (isset($_REQUEST['token'])) {
      $ValidateUrl = "https://hub.loginradius.com/userprofile.ashx?token=".$_REQUEST['token']."&apisecrete=".$ApiSecrete."";
      if ($useapi == 'curl' ) {
        $curl_handle = curl_init();
        curl_setopt($curl_handle, CURLOPT_URL, $ValidateUrl);
        curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 3);
        curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, false);
        if (ini_get('open_basedir') == '' && (ini_get('safe_mode') == 'Off' or !ini_get('safe_mode'))) {
          curl_setopt($curl_handle, CURLOPT_FOLLOWLOCATION, 1);
          curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
          $JsonResponse = curl_exec($curl_handle);
        }
        else {
          curl_setopt($curl_handle, CURLOPT_HEADER, 1);
          $url = curl_getinfo($curl_handle, CURLINFO_EFFECTIVE_URL);
          curl_close($curl_handle);
          $ch = curl_init();
          $url = str_replace('?','/?',$url);
          curl_setopt($ch, CURLOPT_URL, $url);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          $JsonResponse = curl_exec($ch);
          curl_close($ch);
        }
        $UserProfile = json_decode($JsonResponse);
      }
      else {
        $JsonResponse = file_get_contents($ValidateUrl);
        $UserProfile = json_decode($JsonResponse);
      }
      if (isset($UserProfile->ID) && $UserProfile->ID != ''){ 
        $this->IsAuthenticated = true;
        return $UserProfile;
      }
    }
  }
}

class LoginRadiusAuth {
  public $IsAuth, $JsonResponse, $UserAuth; 
  public function auth($ApiKey, $ApiSecrete, $LRSocialShare = false ){
  	if(empty($ApiKey) || empty($ApiSecrete) || !preg_match('/^\{?[A-Z0-9]{8}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{12}\}?$/i', $ApiSecrete) || !preg_match('/^\{?[A-Z0-9]{8}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{12}\}?$/i', $ApiKey))
	return false;

    $IsAuth = false;
	$LoginRadius_settings = get_option('LoginRadius_settings');
	$useapi = $LoginRadius_settings['LoginRadius_useapi'];
    if (isset($ApiKey)) {
      $ApiKey = trim($ApiKey);
      $ApiSecrete = trim($ApiSecrete);
	  
	  if($LRSocialShare) // social share
	  {
      	$ValidateUrl = "http://share.loginradius.com/Sharesetting/$ApiKey";
	  }
	  else
      	$ValidateUrl = "https://hub.loginradius.com/getappinfo/$ApiKey/$ApiSecrete";
      
	  if ($useapi == 'curl') {
        $curl_handle = curl_init();
        curl_setopt($curl_handle, CURLOPT_URL, $ValidateUrl);
        curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 3);
        curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, false);
        if (ini_get('open_basedir') == '' && (ini_get('safe_mode') == 'Off' or !ini_get('safe_mode'))) {
          curl_setopt($curl_handle, CURLOPT_FOLLOWLOCATION, 1);
          curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
          $JsonResponse = curl_exec($curl_handle);
        }
        else {
          curl_setopt($curl_handle, CURLOPT_HEADER, 1);
          $url = curl_getinfo($curl_handle, CURLINFO_EFFECTIVE_URL);
          curl_close($curl_handle);
          $ch = curl_init();
          $url = str_replace('?','/?',$url);
          curl_setopt($ch, CURLOPT_URL, $url);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          $JsonResponse = curl_exec($ch);
          curl_close($ch);
        }
        $UserAuth = json_decode($JsonResponse);
      }
      else {
        $JsonResponse = file_get_contents($ValidateUrl);
        $UserAuth = json_decode($JsonResponse);
      }
	  
	  if(!LRSocialShare) 
	  {
		  if (isset( $UserAuth->IsValid)){ 
			$this->IsAuth = true;
			return $UserAuth;
		  }
	  }
	  
	  return $JsonResponse; // social share
    }
  }
}

?>