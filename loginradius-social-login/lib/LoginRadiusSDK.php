<?php

// Define LoginRadius domain
define( 'LR_DOMAIN', 'api.loginradius.com' );

/**
 * Class for Social Authentication.
 *
 * This is the main class to communicate with LogiRadius Unified Social API. It contains functions for Social Authentication with User Profile Data (Basic and Extended).
 *
 * Copyright 2014 LoginRadius Inc. - www.LoginRadius.com
 *
 * This file is part of the LoginRadius SDK package.
 *
 */
class Login_Radius_SDK {

    /**
     * LoginRadius function - It validate against GUID format of keys.
     *
     * @param string $key data to validate.
     *
     * @return boolean. If valid - true, else - false.
     */
    public function loginradius_validate_key( $key ) {
        if ( empty( $key ) || !preg_match( '/^\{?[A-Z0-9]{8}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{12}\}?$/i', $key ) ) {
            return false;
        } else {
            return true;
        }
    }


	public function loginradius_fetch_access_token($token, $secret){

		$ValidateUrl = "https://".LR_DOMAIN."/api/v2/access_token?token=".$token."&secret=".$secret;
       	$Response = $this->loginradius_call_api($ValidateUrl);
		if(isset($Response -> access_token) && $Response -> access_token != ''){
			return $Response -> access_token;
		}else{
			die('Error in fetching access token.');
	}
}



    /**
     * LoginRadius function - To fetch social profile data from the user's social account after authentication. The social profile will be retrieved via oAuth and OpenID protocols. The data is normalized into LoginRadius' standard data format.
     *
     * @param string $accessToken LoginRadius access token
     * @param boolean $raw        If true, raw data is fetched
     *
     * @return object User profile data.
     */
    public function loginradius_get_user_profiledata( $accessToken, $raw = false ) {
        $ValidateUrl = 'https://' . LR_DOMAIN . '/api/v2/userprofile?access_token=' . $accessToken;
        if ( $raw ) {
            $ValidateUrl = 'https://' . LR_DOMAIN . '/api/v2/userprofile/raw?access_token=' . $accessToken;
            return $this -> loginradius_call_api( $ValidateUrl );
        }
        return $this -> loginradius_call_api( $ValidateUrl );
    }

    /**
     * LoginRadius function - To fetch data from the LoginRadius API URL.
     *
     * @param string $ValidateUrl - Target URL to fetch data from.
     *
     * @return string - data fetched from the LoginRadius API.
     */
    public function loginradius_call_api( $ValidateUrl ) {
        $args = array(
            'timeout' => 15,
            'sslverify' => 'false',
        );
        $loginRadiusResponse = wp_remote_get( $ValidateUrl, $args );
        if ( is_wp_error( $loginRadiusResponse ) ) {
            $currentErrorResponse = "Something went wrong: " . $loginRadiusResponse->get_error_message();
            return $currentErrorResponse;
        } else {
            $JsonResponse = $loginRadiusResponse['body'];
            return json_decode( $JsonResponse );
        }
    }
}
