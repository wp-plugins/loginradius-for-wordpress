<?php 
class LoginRadius{
public $IsAuthenticated,$JsonResponse,$UserProfile; 
public function construct($ApiSecrete){
$IsAuthenticated = false;
if(isset($_REQUEST['token'])){
$ValidateUrl = "http://hub.loginradius.com/userprofile.ashx?token=".$_REQUEST['token']."&apisecrete=".$ApiSecrete."";
$curl_handle=curl_init();
curl_setopt($curl_handle,CURLOPT_URL, $ValidateUrl);
curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
curl_setopt($curl_handle, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, false);
$JsonResponse = curl_exec($curl_handle);
curl_close($curl_handle);
$UserProfile = json_decode($JsonResponse);
if(!isset( $UserProfile->ID) && $UserProfile->ID == '') {
$JsonResponse = file_get_contents($ValidateUrl);
$UserProfile = json_decode($JsonResponse);
}
if(isset( $UserProfile->ID) && $UserProfile->ID!=''){ 
$this->IsAuthenticated = true;
return $UserProfile;
}}}}
class LoginRadius_auth{
public $IsAuth,$JsonResponse,$UserAuth; 
public function auth($ApiKey, $ApiSecrete){
$IsAuth = false;
if(isset($ApiKey)){
$ApiKey=trim($ApiKey);
$ApiSecrete=trim($ApiSecrete);
$ValidateUrl = "https://hub.loginradius.com/getappinfo/$ApiKey/$ApiSecrete";
$curl_handle=curl_init();
curl_setopt($curl_handle,CURLOPT_URL, $ValidateUrl);
curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
curl_setopt($curl_handle, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, false);
$JsonResponse = curl_exec($curl_handle);
curl_close($curl_handle);
$UserAuth = json_decode($JsonResponse);
if(!isset( $UserAuth->IsValid)) {
$JsonResponse = file_get_contents($ValidateUrl);
$UserAuth = json_decode($JsonResponse);
}
if(isset( $UserAuth->IsValid)){ 
$this->IsAuth = true;
return $UserAuth;
}}}}
?>