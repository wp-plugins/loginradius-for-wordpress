<?php
/**
 *
 * @param type $loginRadiusSettingsfunction for rendering Social Share tab on plugin settings page
 */
function login_radius_render_social_sharing_options( $loginRadiusSettings ) {
    ?>
    <div class="menu_containt_div" id="tabs-3">
        <div class="stuffbox">
            <h3><label><?php _e( 'Social Sharing Interface Selection', 'LoginRadius' ); ?></label></h3>
            <div class="inside">
                <table class="form-table editcomment menu_content_table">
                    <tr>
                        <td>
                            <div class="loginRadiusQuestion"><?php _e( 'Please select the social sharing interface, horizontal and vertical interfaces can be enabled simultaneously.', 'LoginRadius' ); ?></div>
                            <br />
                            <a href="javascript:void( 0 ) " style="text-decoration:none" id = "show_horizontal_theme_content" ><?php _e( 'Horizontal', 'LoginRadius' ); ?></a> | <a href="javascript:void( 0 ) " id = "show_vertical_theme_content" style="text-decoration:none"><?php _e( 'Vertical', 'LoginRadius' ); ?></a>
                        </td>
                    </tr>
                    <tr id="login_radius_horizontal">
                        <td>
                            <span class="lrsharing_spanwhite"></span>
                            <span class="lrsharing_spangrey"></span>
                            <div style="border:1px solid #ccc; padding:10px; border-radius:5px">
                                <div class="loginRadiusQuestion"><?php _e( 'Do you want to enable horizontal social sharing for your website?', 'LoginRadius' ); ?></div>
                                <div class="loginRadiusYesRadio">
                                    <input type="radio" name="LoginRadius_settings[horizontal_shareEnable]" value='1' <?php echo!isset( $loginRadiusSettings['horizontal_shareEnable'] ) || $loginRadiusSettings['horizontal_shareEnable'] == '1' ? 'checked="checked"' : '' ?> /> <?php _e( 'Yes', 'LoginRadius' ) ?>
                                </div>
                                <input type="radio" name="LoginRadius_settings[horizontal_shareEnable]" value="0" <?php echo isset( $loginRadiusSettings['horizontal_shareEnable'] ) && $loginRadiusSettings['horizontal_shareEnable'] == '0' ? 'checked="checked"' : '' ?> /> <?php _e( 'No', 'LoginRadius' ) ?>
                                <div class="loginRadiusBorder2"></div>

                                <div class="loginRadiusQuestion" style="margin-top:10px">
                                    <?php _e( 'Select your Social Sharing Interface:', 'LoginRadius' ); ?>
                                </div>

                                <div class="login_radius_select_row">
                                    <span class="radio" style="margin-top: 7px">
                                        <input class="horizontalSharingThemesTop" <?php echo ( isset( $loginRadiusSettings['horizontalSharing_theme'] ) && $loginRadiusSettings['horizontalSharing_theme'] == '32' ) || !isset( $loginRadiusSettings['horizontalSharing_theme'] ) ? 'checked="checked"' : '' ?> type="radio" id="login_radius_sharing_top_32" name="LoginRadius_settings[horizontalSharing_theme]" value='32' />
                                    </span>
                                    <label for="login_radius_sharing_top_32">
                                        <img src="<?php echo LOGINRADIUS_PLUGIN_URL . 'assets/images/sharing/horizonSharing32.png'; ?>" align="left" />
                                    </label>
                                    <div class="clear"></div>
                                </div>

                                <div class="login_radius_select_row">
                                    <span class="radio" style="margin-top: 1px;">
                                        <input class="horizontalSharingThemesTop" <?php echo isset( $loginRadiusSettings['horizontalSharing_theme'] ) && $loginRadiusSettings['horizontalSharing_theme'] == '16' ? 'checked="checked"' : '' ?> type="radio" name="LoginRadius_settings[horizontalSharing_theme]" id="login_radius_sharing_top_16" value='16' />
                                    </span>
                                    <label for="login_radius_sharing_top_16">
                                        <img src="<?php echo LOGINRADIUS_PLUGIN_URL . 'assets/images/sharing/horizonSharing16.png'; ?>" />
                                    </label>
                                    <div class="clear"></div>
                                </div>

                                <div class="login_radius_select_row">
                                    <span class="radio" style="margin-top: 3px;">
                                        <input class="horizontalSharingSingle" <?php echo isset( $loginRadiusSettings['horizontalSharing_theme'] ) && $loginRadiusSettings['horizontalSharing_theme'] == 'single_large' ? 'checked="checked"' : '' ?> type="radio" name="LoginRadius_settings[horizontalSharing_theme]" value='single_large' id="login_radius_sharing_top_slarge" />
                                    </span>
                                    <label for="login_radius_sharing_top_slarge">
                                        <img src="<?php echo LOGINRADIUS_PLUGIN_URL . 'assets/images/sharing/single-image-theme-large.png'; ?>" />
                                    </label>
                                    <div class="clear"></div>
                                </div>

                                <div class="login_radius_select_row">
                                    <span class="radio" style="margin-top: 1px;">
                                        <input class="horizontalSharingSingle" <?php echo isset( $loginRadiusSettings['horizontalSharing_theme'] ) && $loginRadiusSettings['horizontalSharing_theme'] == 'single_small' ? 'checked="checked"' : '' ?> type="radio" name="LoginRadius_settings[horizontalSharing_theme]" id="login_radius_sharing_top_ssmall" value='single_small' />
                                    </span>
                                    <label for="login_radius_sharing_top_ssmall">
                                        <img src="<?php echo LOGINRADIUS_PLUGIN_URL . 'assets/images/sharing/single-image-theme-small.png'; ?>" />
                                    </label>
                                    <div class="clear"></div>
                                </div>

                                <div class="login_radius_select_row">
                                    <span class="radio" style="margin-top: 20px;">
                                        <input class="horizontalCounters" <?php echo isset( $loginRadiusSettings['horizontalSharing_theme'] ) && $loginRadiusSettings['horizontalSharing_theme'] == 'counter_vertical' ? 'checked="checked"' : '' ?> type="radio" name="LoginRadius_settings[horizontalSharing_theme]" id="login_radius_counter_top_vertical" value='counter_vertical' />
                                    </span>
                                    <label for="login_radius_counter_top_vertical">
                                        <img src="<?php echo LOGINRADIUS_PLUGIN_URL . 'assets/images/counter/hybrid-horizontal-vertical.png'; ?>" />
                                    </label>
                                    <div class="clear"></div>
                                </div>

                                <div class="login_radius_select_row">
                                    <span class="radio" style="margin-top: 6px;">
                                        <input class="horizontalCounters" <?php echo isset( $loginRadiusSettings['horizontalSharing_theme'] ) && $loginRadiusSettings['horizontalSharing_theme'] == 'counter_horizontal' ? 'checked="checked"' : '' ?> type="radio" name="LoginRadius_settings[horizontalSharing_theme]" id="login_radius_counter_top_horizontal" value='counter_horizontal' />
                                    </span>
                                    <label for="login_radius_counter_top_horizontal">
                                        <img src="<?php echo LOGINRADIUS_PLUGIN_URL . 'assets/images/counter/hybrid-horizontal-horizontal.png'; ?>" />
                                    </label>
                                    <div class="clear"></div>
                                </div>

                                <div class="loginRadiusBorder2"></div>

                                <div id="login_radius_horizontal_providers_container">
                                    <div class="loginRadiusQuestion" style="margin-top:10px">
                                        <?php _e( 'What sharing networks do you want to show in the sharing interface? ( All other sharing networks will be shown as part of LoginRadius sharing icon ) ', 'LoginRadius' ) ?>
                                    </div>
                                    <div id="loginRadiusHorizontalSharingLimit"><?php _e( 'You can select only nine providers', 'LoginRadius' ) ?>.</div>
                                    <div style="width:420px" id="login_radius_horizontal_sharing_providers_container"></div>
                                    <div style="width:600px; display: none;" id="login_radius_horizontal_counter_providers_container"></div>
                                </div>

                                <div id="login_radius_horizontal_rearrange_container">
                                    <div class="loginRadiusBorder2"></div>

                                    <div class="loginRadiusQuestion" style="margin-top:10px">
                                        <?php _e( 'What sharing network order do you prefer for your sharing interface? Drag the icons around to set the order', 'LoginRadius' ) ?>
                                    </div>
                                    <ul id="loginRadiusHorizontalSortable">
                                        <?php
                                        if ( isset( $loginRadiusSettings['horizontal_rearrange_providers'] ) && count( $loginRadiusSettings['horizontal_rearrange_providers'] ) > 0 ) {
                                            foreach ( $loginRadiusSettings['horizontal_rearrange_providers'] as $provider ) {
                                                ?>
                                                <li title="<?php echo $provider ?>" id="loginRadiusHorizontalLI<?php echo $provider ?>" class="lrshare_iconsprite32 lrshare_<?php echo strtolower( $provider ) ?>">
                                                    <input type="hidden" name="LoginRadius_settings[horizontal_rearrange_providers][]" value="<?php echo $provider ?>" />
                                                </li>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </ul>
                                </div>
                                <div class="loginRadiusBorder2"></div>
                                <div class="loginRadiusQuestion" style="margin-top:10px">
                                    <?php _e( 'Select the position of the social sharing interface', 'LoginRadius' ); ?>
                                </div>
                                <input type="checkbox" name="LoginRadius_settings[horizontal_shareTop]" value='1' <?php echo isset( $loginRadiusSettings['horizontal_shareTop'] ) && $loginRadiusSettings['horizontal_shareTop'] == 1 ? 'checked' : '' ?>/> <?php _e( 'Show at the top of content', 'LoginRadius' ); ?> <br />
                                <input type="checkbox" name="LoginRadius_settings[horizontal_shareBottom]" value='1' <?php echo isset( $loginRadiusSettings['horizontal_shareBottom'] ) && $loginRadiusSettings['horizontal_shareBottom'] == 1 ? 'checked' : '' ?>/> <?php _e( 'Show at the bottom of content', 'LoginRadius' ); ?>                         <div class="loginRadiusBorder2"></div>

                                <div class="loginRadiusQuestion" style="margin-top:10px">
                                    <?php _e( 'What location do you want to show the social sharing interface?', 'LoginRadius' ); ?>
                                </div>
                                <input type="checkbox" name="LoginRadius_settings[horizontal_sharehome]" value='1' <?php echo isset( $loginRadiusSettings['horizontal_sharehome'] ) && $loginRadiusSettings['horizontal_sharehome'] == 1 ? 'checked' : '' ?>/> <?php _e( 'Show on \'Home Page\'', 'LoginRadius' ); ?> <br />
                                <input type="checkbox" name="LoginRadius_settings[horizontal_sharepost]" value='1' <?php echo isset( $loginRadiusSettings['horizontal_sharepost'] ) && $loginRadiusSettings['horizontal_sharepost'] == 1 ? 'checked' : '' ?>/> <?php _e( 'Show on \'Posts\'', 'LoginRadius' ); ?>
                                <br />
                                <input type="checkbox" name="LoginRadius_settings[horizontal_sharepage]" value='1' <?php echo isset( $loginRadiusSettings['horizontal_sharepage'] ) && $loginRadiusSettings['horizontal_sharepage'] == 1 ? 'checked' : '' ?>/> <?php _e( 'Show on \'Pages\'', 'LoginRadius' ); ?> <br />
                                <input type="checkbox" name="LoginRadius_settings[horizontal_shareexcerpt]" value='1' <?php echo isset( $loginRadiusSettings['horizontal_shareexcerpt'] ) && $loginRadiusSettings['horizontal_shareexcerpt'] == 1 ? 'checked' : '' ?>/> <?php _e( 'Show on \'Post Excerpts\' ', 'LoginRadius' ); ?> <br />
                            </div>
                        </td>
                    </tr>
                    <tr id="login_radius_vertical" style="display:none">
                        <td>
                            <span class="lrsharing_spanwhite" style="margin-left:80px"></span>
                            <span class="lrsharing_spangrey" style="margin-left:80px"></span>
                            <div style="border:1px solid #ccc; padding:10px; border-radius:5px">
                                <div class="loginRadiusQuestion">
                                    <?php _e( 'Do you want to enable vertical social sharing for your website?', 'LoginRadius' ); ?>
                                </div>
                                <div class="loginRadiusYesRadio">
                                    <input type="radio" name="LoginRadius_settings[vertical_shareEnable]" value='1' <?php echo!isset( $loginRadiusSettings['vertical_shareEnable'] ) || $loginRadiusSettings['vertical_shareEnable'] == '1' ? 'checked="checked"' : '' ?> /> <?php _e( 'Yes', 'LoginRadius' ) ?>
                                </div>
                                <input type="radio" name="LoginRadius_settings[vertical_shareEnable]" value="0" <?php echo isset( $loginRadiusSettings['vertical_shareEnable'] ) && $loginRadiusSettings['vertical_shareEnable'] == '0' ? 'checked="checked"' : '' ?> /> <?php _e( 'No', 'LoginRadius' ) ?>
                                <div class="loginRadiusBorder2"></div>

                                <div class="loginRadiusQuestion" style="margin-top:10px">
                                    <?php _e( 'Select your Social Sharing Interface:', 'LoginRadius' ); ?>
                                </div>
                                <div class = "images-wrapper">
                                    <input class="verticalSharingThemesTop" <?php echo ( isset( $loginRadiusSettings['verticalSharing_theme'] ) && $loginRadiusSettings['verticalSharing_theme'] == '32' ) || !isset( $loginRadiusSettings['verticalSharing_theme'] ) ? 'checked="checked"' : '' ?> type="radio" id="login_radius_sharing_vertical_32" name="LoginRadius_settings[verticalSharing_theme]" value='32' />
                                    <label for="login_radius_sharing_vertical_32">
                                        <img src="<?php echo LOGINRADIUS_PLUGIN_URL . 'assets/images/sharing/vertical/32VerticlewithBox.png'; ?>" align="left" />
                                    </label>
                                    <div class="clear"></div>
                                </div>
                                <div class = "images-wrapper">
                                    <input class="verticalSharingThemesTop" <?php echo isset( $loginRadiusSettings['verticalSharing_theme'] ) && $loginRadiusSettings['verticalSharing_theme'] == '16' ? 'checked="checked"' : '' ?> style="float:left" type="radio" name="LoginRadius_settings[verticalSharing_theme]" id="login_radius_sharing_vertical_16" value='16' />
                                    <label for="login_radius_sharing_vertical_16">
                                        <img style = "margin-left: 10px;" src="<?php echo LOGINRADIUS_PLUGIN_URL . 'assets/images/sharing/vertical/16VerticlewithBox.png'; ?>" />
                                    </label>
                                    <div class="clear"></div>
                                </div>

                                <div class = "images-wrapper">
                                    <input class="verticalCounters" style = "margin-left: 20px !important;" <?php echo isset( $loginRadiusSettings['verticalSharing_theme'] ) && $loginRadiusSettings['verticalSharing_theme'] == 'counter_vertical' ? 'checked="checked"' : '' ?> style="float:left" type="radio" name="LoginRadius_settings[verticalSharing_theme]" id="login_radius_counter_vertical_vertical" value='counter_vertical' />
                                    <label for="login_radius_counter_vertical_vertical">
                                        <img style = "margin-left: 10px;" src="<?php echo LOGINRADIUS_PLUGIN_URL . 'assets/images/counter/hybrid-verticle-vertical.png'; ?>" />
                                    </label>
                                    <div class="clear"></div>
                                </div>

                                <div class = "images-wrapper">
                                    <input class="verticalCounters" style = "margin-left: 20px!important; float:left;" <?php echo isset( $loginRadiusSettings['verticalSharing_theme'] ) && $loginRadiusSettings['verticalSharing_theme'] == 'counter_horizontal' ? 'checked="checked"' : '' ?> type="radio" name="LoginRadius_settings[verticalSharing_theme]" id="login_radius_counter_vertical_horizontal" value='counter_horizontal' />
                                    <label for="login_radius_counter_vertical_horizontal">
                                        <img style ="margin-left: 10px;" src="<?php echo LOGINRADIUS_PLUGIN_URL . 'assets/images/counter/hybrid-verticle-horizontal.png'; ?>" />
                                    </label>
                                    <div class="clear"></div>
                                </div>
                                <div class="clear"></div>
                                <div id="login_radius_vertical_providers_container">
                                    <div class="loginRadiusBorder2"></div>
                                    <div class="loginRadiusQuestion" style="margin-top:10px">
                                        <?php _e( 'What sharing networks do you want to show in the sharing interface? ( All other sharing networks will be shown as part of LoginRadius sharing icon ) ', 'LoginRadius' ) ?>
                                    </div>
                                    <div id="loginRadiusVerticalSharingLimit" style="color:red; display:none; margin-bottom: 5px;"><?php _e( 'You can select only nine providers', 'LoginRadius' ) ?>.</div>
                                    <div id="login_radius_vertical_sharing_providers_container"></div>
                                    <div id="login_radius_vertical_counter_providers_container"></div>
                                </div>

                                <div id="login_radius_vertical_rearrange_container">
                                    <div class="loginRadiusBorder2"></div>

                                    <div class="loginRadiusQuestion" style="margin-top:10px">
                                        <?php _e( 'What sharing network order do you prefer for your sharing interface? Drag the icons around to set the order', 'LoginRadius' ) ?>
                                    </div>
                                    <ul id="loginRadiusVerticalSortable">
                                        <?php
                                        if ( isset( $loginRadiusSettings['vertical_rearrange_providers'] ) && count( $loginRadiusSettings['vertical_rearrange_providers'] ) > 0 ) {
                                            foreach ( $loginRadiusSettings['vertical_rearrange_providers'] as $provider ) {
                                                ?>
                                                <li title="<?php echo $provider ?>" id="loginRadiusVerticalLI<?php echo $provider ?>" class="lrshare_iconsprite32 lrshare_<?php echo strtolower( $provider ) ?>">
                                                    <input type="hidden" name="LoginRadius_settings[vertical_rearrange_providers][]" value="<?php echo $provider ?>" />
                                                </li>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </ul>
                                </div>
                                <div class="loginRadiusBorder2"></div>
                                <div class="loginRadiusQuestion" style="margin-top:10px">
                                    <?php _e( 'Select the position of the social sharing interface', 'LoginRadius' ); ?>
                                </div>
                                <div class="loginRadiusProviders">
                                    <input <?php echo ( isset( $loginRadiusSettings['sharing_verticalPosition'] ) && $loginRadiusSettings['sharing_verticalPosition'] == 'top_left' ) || !isset( $loginRadiusSettings['sharing_verticalPosition'] ) ? 'checked="checked"' : '' ?> type="radio" name="LoginRadius_settings[sharing_verticalPosition]" value="top_left" /> <label><?php _e( "Top left", 'LoginRadius' ); ?></label>
                                </div>
                                <div class="loginRadiusProviders">
                                    <input  <?php echo ( isset( $loginRadiusSettings['sharing_verticalPosition'] ) && $loginRadiusSettings['sharing_verticalPosition'] == 'top_right' ) ? 'checked="checked"' : '' ?> type="radio" name="LoginRadius_settings[sharing_verticalPosition]" value="top_right" /> <label><?php _e( "Top Right", 'LoginRadius' ); ?></label>
                                </div>
                                <div class="loginRadiusProviders">
                                    <input <?php echo ( isset( $loginRadiusSettings['sharing_verticalPosition'] ) && $loginRadiusSettings['sharing_verticalPosition'] == 'bottom_left' ) ? 'checked="checked"' : '' ?> type="radio" name="LoginRadius_settings[sharing_verticalPosition]" value="bottom_left" /> <label><?php _e( "Bottom Left", 'LoginRadius' ); ?></label>
                                </div>
                                <div class="loginRadiusProviders">
                                    <input <?php echo ( isset( $loginRadiusSettings['sharing_verticalPosition'] ) && $loginRadiusSettings['sharing_verticalPosition'] == 'bottom_right' ) ? 'checked="checked"' : '' ?> type="radio" name="LoginRadius_settings[sharing_verticalPosition]" value="bottom_right" /> <label><?php _e( "Bottom Right", 'LoginRadius' ); ?></label>
                                </div>
                                <div class="loginRadiusBorder2"></div>
                                <div style="clear:both"></div>
                                <div class="loginRadiusQuestion" style="margin-top:10px">
                                    <?php _e( 'What location do you want to show the social sharing interface?', 'LoginRadius' ); ?>
                                </div>
                                <input type="checkbox" name="LoginRadius_settings[vertical_sharehome]" value='1' <?php echo isset( $loginRadiusSettings['vertical_sharehome'] ) && $loginRadiusSettings['vertical_sharehome'] == 1 ? 'checked' : '' ?>/> <?php _e( 'Show on \'Home Page\'', 'LoginRadius' ); ?> <br />
                                <input type="checkbox" name="LoginRadius_settings[vertical_sharepost]" value='1' <?php echo isset( $loginRadiusSettings['vertical_sharepost'] ) && $loginRadiusSettings['vertical_sharepost'] == 1 ? 'checked' : '' ?>/> <?php _e( 'Show on \'Posts\'', 'LoginRadius' ); ?>
                                <br />
                                <input type="checkbox" name="LoginRadius_settings[vertical_sharepage]" value='1' <?php echo isset( $loginRadiusSettings['vertical_sharepage'] ) && $loginRadiusSettings['vertical_sharepage'] == 1 ? 'checked' : '' ?>/> <?php _e( 'Show on \'Pages\'', 'LoginRadius' ); ?> <br />
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <?php
}
