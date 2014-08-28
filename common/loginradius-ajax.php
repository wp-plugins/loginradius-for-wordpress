<?php

/**
 * populate email asking popup
 */
function login_radius_email_popup() {
    if ( isset( $_GET['key'] ) && $_GET['key'] != '' ) {
        global $wpdb;
        $loginRadiusTempUserId = $wpdb->get_var( $wpdb->prepare( 'SELECT user_id FROM ' . $wpdb->usermeta . ' WHERE meta_key=\'tmpsession\' AND meta_value = %s', mysql_real_escape_string( $_GET['key'] ) ) );
        $provider = get_user_meta( $loginRadiusTempUserId, 'tmpProvider', true );
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
                        <div><input type="text" name="email" id="loginRadiusEmail" class="inputtxt" style = "padding-top: 0px;"/></div>
                        <div class="footerbox">
                            <input type="submit" id="LoginRadius_popupSubmit" name="LoginRadius_popupSubmit" value="Submit" class="inputbutton">
                            <input type="hidden" value="<?php echo $_GET['key'] ?>" name = "session"/>
                        </div>
                    </form>
                    <form method = "post">
                            <input type="submit" name="LoginRadius_popupSubmit" value="Cancel" class="inputbutton" />
                            <input type="hidden" value="<?php echo $_GET['key'] ?>" name = "session"/>
                    </form>
                </div>
            </div>
        </div>
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
            Login_Radius_Common::login_radius_send_verification_email( $user->user_email, '', '', 'activation', $username );
        } catch ( Exception $e ) {
            die( 'error' );
        }
        die( 'done' );
    }
}

add_action( 'wp_ajax_login_radius_change_user_status', 'login_radius_change_user_status' );
