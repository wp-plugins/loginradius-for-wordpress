<?php

/**
 * populate email asking popup
 */
function login_radius_email_popup() {
    global $lr_social_profile_data_settings;
    if ( isset( $_GET['key'] ) && $_GET['key'] != '' ) {
        global $wpdb;
        $loginRadiusTempUserId = $wpdb->get_var( $wpdb->prepare( 'SELECT user_id FROM ' . $wpdb->usermeta . ' WHERE meta_key=\'tmpsession\' AND meta_value = %s', $_GET['key'] ) );
        $provider = get_user_meta( $loginRadiusTempUserId, 'tmpProvider', true );

        $profileData = Login_Helper::fetch_temp_data_from_usermeta( $loginRadiusTempUserId );
        
        // Custom Popup
        if( isset($lr_social_profile_data_settings['enable_custom_popup'] ) && $lr_social_profile_data_settings['enable_custom_popup'] == '1' ) {
            
            //TODO Format Dates
            // Format date for input field.
            if( ! empty( $profileData['BirthDate'] ) ) { 
                
                $datebool = strpos($profileData['BirthDate'],'-');

                // Date format matches mm/dd/YYYY
                if( ! $datebool ) {
                    $date  = explode ( '/' , $profileData['BirthDate'] );
                    $month = $date[0] -1;
                    $day  = $date[1];
                    $year   = $date[2];

                    // Add 0 to all months less than 10
                    if($date[0] < 10) {
                        $date[0] = '0' . $date[0];
                    }

                    // Add 0 to all days less than 10
                    if($date[1] < 10) {
                        $date[1] = '0' . $date[1];
                    }

                    // Format YYYY-mm-dd 
                    $profileData['BirthDate'] = $date[2] . '-' . $date[0] . '-' . $date[1];

                }else {
                    $date  = explode ( '-' , $profileData['BirthDate'] );
                    
                    $year   = $date[0];
                    $month = $date[1];
                    $day  = $date[2];
                    

                    // // Add 0 to all months less than 10
                    // if($month < 10) {
                    //     $month = '0' . $month;
                    // }

                    // // Add 0 to all days less than 10
                    // if($day < 10) {
                    //     $day = '0' . $day;
                    // }

                    // Format YYYY-mm-dd
                    $profileData['BirthDate'] = $year . '-' . $month . '-' . $day;
                }  
            }
            
            ?>
                <script>
                    jQuery(document).ready(function() {
                        jQuery('#birthdate').datepicker({
                            changeMonth: true,
                            changeYear: true,
                            dateFormat: 'yy-mm-dd',
                            yearRange: "-100:+0",
                            <?php if( ! empty( $profileData['BirthDate'] )) { ?>
                                defaultDate: new Date(<?php echo $year . ',' . $month . ',' . $day; ?>)
                            <?php } ?>
                        });
                    });
                </script>
                <div class="LoginRadius_overlay" id="fade">
                    <div id="popupouter">
                        <div id="popupinner">
                            <form method="post" action='' onsubmit='return loginRadiusValidateEmail()' style = "height: 50px;">
                                
                                <?php if( isset($lr_social_profile_data_settings['show_email'] ) && $lr_social_profile_data_settings['show_email'] == '1' ) { ?>
                                    <div>
                                        <div class="emailtext" id="innerp">Enter your email:</div>
                                        <input type="text" name="email" id="loginRadiusEmail" class="inputtxt" style = "padding-top: 0px;" value="<?php echo $profileData['Email']; ?>"/>
                                    </div>
                                <?php } ?>

                                <?php if( isset($lr_social_profile_data_settings['show_gender'] ) && $lr_social_profile_data_settings['show_gender'] == '1' ) { ?>
                                    <div>
                                        <div id="innerp">Select your Gender:</div>
                                        <input type="radio" name="gender" value="male" <?php echo (isset($profileData['Gender']) && $profileData['Gender'] == 'M' || $profileData['Gender'] == 'male' ) ? 'checked' : ''; ?> />Male
                                        <input type="radio" name="gender" value="female" <?php echo (isset($profileData['Gender']) && $profileData['Gender'] == 'F' || $profileData['Gender'] == 'female' ) ? 'checked' : ''; ?> />Female
                                    </div>
                                <?php } ?>

                                <?php if( isset($lr_social_profile_data_settings['show_birthdate'] ) && $lr_social_profile_data_settings['show_birthdate'] == '1' ) { ?>
                                    <div>
                                        <div id="innerp">Select your BirthDate:</div>
                                        <input type="text" id="birthdate" name="birthdate" value="<?php echo $profileData['BirthDate']; ?>"/>
                                    </div>
                                <?php } ?>

                                <?php if( isset($lr_social_profile_data_settings['show_phonenumber'] ) && $lr_social_profile_data_settings['show_phonenumber'] == '1' ) { ?>
                                    <div>
                                        <div id="innerp">Enter your Phone Number:</div>
                                        <input type="text" name="phonenumber" value="<?php echo $profileData['PhoneNumber']; ?>"/>
                                    </div>
                                <?php } ?>
                                
                                <?php if( isset($lr_social_profile_data_settings['show_relationshipstatus'] ) && $lr_social_profile_data_settings['show_relationshipstatus'] == '1' ) { ?>
                                    <div>
                                        <div id="innerp">Enter your Relationship Status:</div>
                                        <input type="text" name="relationshipstatus" value="<?php echo $profileData['RelationshipStatus']; ?>"/>
                                    </div>
                                <?php } ?>
                                
                                <?php if( isset($lr_social_profile_data_settings['show_city'] ) && $lr_social_profile_data_settings['show_city'] == '1' ) { ?>
                                    <div>
                                        <div id="innerp">Enter your City:</div>
                                        <input type="text" name="city" value="<?php echo $profileData['City']; ?>"/>
                                    </div>
                                <?php } ?>

                                <?php if( isset($lr_social_profile_data_settings['show_postalcode'] ) && $lr_social_profile_data_settings['show_postalcode'] == '1' ) { ?>
                                    <div>
                                        <div id="innerp">Enter your Postal Code:</div>
                                        <input type="text" name="postalcode" value="<?php echo $profileData['PostalCode']; ?>"/>
                                    </div>
                                <?php } ?>

                                <div class="footerbox">
                                    <input type="submit" id="LoginRadius_popupSubmit" name="LoginRadius_popupSubmit" value="Submit" class="inputbutton">
                                    <input type="hidden" value="<?php echo $_GET['key'] ?>" name = "session"/>
                                
                            </form>
                            <form method = "post">
                                    <input type="submit" name="LoginRadius_popupSubmit" value="Cancel" class="inputbutton" />
                                    <input type="hidden" value="<?php echo $_GET['key'] ?>" name = "session"/>
                            </form>
                                </div><!-- END FOOTERBOX -->
                        </div>
                    </div>
                </div>
                
            <?php
        } 
        // Normal Flow
        else {
            ?>
                <div class="LoginRadius_overlay" id="fade">
                    <div id="popupouter">
                        <div class="lr-popupheading"> You are trying to connect with <?php echo $provider; ?></div>
                        <div id="popupinner">
                            <div id="loginRadiusError" style = "display: none;"></div>
                                <?php
                                if( $_GET['isError'] == 'yes' ) {
                                    echo '<div id="textmatter" class="lr-noerror" style = "background-color: rgb(255, 235, 232);border: 1px solid rgb(204, 0, 0);">';
                                } else {
                                    echo '<div id="textmatter" class="lr-noerror">';
                                }
                                if ( isset( $_GET['message'] ) && $_GET['message'] != '' ) {
                                    echo str_replace(  '@provider', $provider, $_GET['message'] );
                                }
                                ?>
                            </div>

                            
                            <div class="emailtext" id="innerp">Enter your email:</div>
                            <form method="post" action='' onsubmit='return loginRadiusValidateEmail()' style = "height: 50px;">
                                <div>
                                    <input type="text" name="email" id="loginRadiusEmail" class="inputtxt" style = "padding-top: 0px;"/>
                                </div>
                                <div class="footerbox">
                                    <input type="submit" id="LoginRadius_popupSubmit" name="LoginRadius_popupSubmit" value="Submit" class="inputbutton">
                                    <input type="hidden" value="<?php echo $_GET['key'] ?>" name = "session"/>
                                
                            </form>
                            <form method = "post">
                                    <input type="submit" name="LoginRadius_popupSubmit" value="Cancel" class="inputbutton" />
                                    <input type="hidden" value="<?php echo $_GET['key'] ?>" name = "session"/>
                            </form>
                                </div><!-- END FOOTERBOX -->
                        </div>
                    </div>
                </div>
            <?php
        }
        ?>
        
        <?php
    }
    die;
}

add_action( 'wp_ajax_nopriv_login_radius_email_popup', 'login_radius_email_popup' );

/**
 * Function that displaying notification.
 */
function login_radius_notification_popup() {
    ?>
    <script>
        jQuery('#TB_title').hide();
        jQuery().css({'width' : '0'});
    </script>
    <div class="LoginRadius_overlay" id="fade">
        <div id="popupouter">
            <div id="popupinner">
                <div id="textmatter">
                    <?php
                    if ( isset( $_GET['message'] ) && $_GET['message'] != '' ) {

                        echo $_GET['message'];
                    }
                    ?>
                </div>
                <?php
                if ( isset( $_GET['redirection'] ) && $_GET['redirection'] != '' ) {
                    ?>
                    <form method="post" action=''>
                        <div>
                            <input type="button" value="OK" class="inputbutton" onclick="location.href = '<?php echo $_GET['redirection'] ?>'">
                        </div>
                    </form>

                    <?php
                } else {
                    ?>
                    <form method="post" action="<?php echo site_url() ?>">
                        <div>
                            <input type="submit" value="OK" class="inputbutton">
                        </div>
                    </form>
                <?php } ?>
            </div>
        </div>
    </div>
    <?php
    die;
}

add_action( 'wp_ajax_nopriv_login_radius_notification_popup', 'login_radius_notification_popup' );

// change user status
function login_radius_change_user_status() {
    $currentStatus = $_POST['current_status'];
    $userId = $_POST['user_id'];
    if ( $currentStatus == '1' ) {
        update_user_meta( $userId, 'loginradius_status', '0' );
        die( 'done' );
    } elseif ( $currentStatus == '0' ) {
        update_user_meta( $userId, 'loginradius_status', '1' );
        $user = get_userdata( $userId );
        $userName = $user->display_name != '' ? $user->display_name : $user->user_nicename;
        $username = $userName != '' ? ucfirst( $userName ) : ucfirst( $user->user_login );
        try {
            LR_Common::login_radius_send_verification_email( $user->user_email, '', '', 'activation', $username );
        } catch ( Exception $e ) {
            die( 'error' );
        }
        die( 'done' );
    }
}

add_action( 'wp_ajax_login_radius_change_user_status', 'login_radius_change_user_status' );
