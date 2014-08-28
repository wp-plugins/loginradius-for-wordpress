// get trim() working in IE
if ( typeof String.prototype.trim !== 'function' ) {
    String.prototype.trim = function() {
        return this.replace( /^\s+|\s+$/g, '' );
    };
}

var loginRadiusHorizontalSharingProviders;
var loginRadiusVerticalSharingProviders;

function loginRadiusCheckElement( arr, obj ) {
    for ( var i = 0; i < arr.length; i++ ) {
        if ( arr[i] == obj ) {
            return true;
        }
    }
    return false;
}

window.onload = function() {
    loginRadiusAdminUI2();
    loginRadiusHorizontalSharingProviders = jQuery('[name="LoginRadius_settings[horizontal_sharing_providers][]"]');
    loginRadiusVerticalSharingProviders = jQuery('[name="LoginRadius_settings[vertical_sharing_providers][]"]');
    loginRadiusAdminUI();
};

function making_theme_option_ckeckbox_selected( loginRadiusSharingTheme, type ) {
    for ( var key in loginRadiusSharingTheme ) {
        if ( loginRadiusSharingTheme[key].checked ) {
            if( type == "horizontal") {
                loginRadiusToggleHorizontalShareTheme(loginRadiusSharingTheme[key].value);
                break;
            } else {
                loginRadiusToggleVerticalShareTheme(loginRadiusSharingTheme[key].value);
            }
        }
    }

}

function set_default_rearrange_providers( loginRadiusSharingProviders, type ) {
     for ( var i = 0; i < loginRadiusSharingProviders.length; i++ ) {
            if ( loginRadiusSharingProviders[i].checked ) {
                loginRadiusRearrangeProviderList(loginRadiusSharingProviders[i], type);
            }
        }
}
function loginRadiusAdminUI() {
    var loginRadiusHorizontalSharingTheme = jQuery("input[type='radio'][name='LoginRadius_settings[horizontalSharing_theme]']:checked");
    var loginRadiusVerticalSharingTheme = jQuery("input[type='radio'][name='LoginRadius_settings[verticalSharing_theme]']:checked");

    making_theme_option_ckeckbox_selected( loginRadiusHorizontalSharingTheme, "horizontal" );
    making_theme_option_ckeckbox_selected( loginRadiusVerticalSharingTheme, "vertical" );
    // if rearrange horizontal sharing icons option is empty, show seleted icons to rearrange
    if ( jQuery('[name="LoginRadius_settings[horizontal_rearrange_providers][]"]').length == 0 ) {
        set_default_rearrange_providers( loginRadiusHorizontalSharingProviders, 'Horizontal' );
    }
    // if rearrange vertical sharing icons option is empty, show seleted icons to rearrange
    if ( jQuery('[name="LoginRadius_settings[vertical_rearrange_providers][]"]').length == 0 ) {
        set_default_rearrange_providers( loginRadiusVerticalSharingProviders, 'Vertical' );
     }
    // user activate/deactivate toggle
    var loginRadiusStatusOption = jQuery('[name="LoginRadius_settings[LoginRadius_enableUserActivation]"]');
    for ( var i = 0; i < loginRadiusStatusOption.length; i++ ) {
        if ( loginRadiusStatusOption[i].checked && loginRadiusStatusOption[i].value == '1' ) {
            jQuery('#loginRadiusDefaultStatus').css({
                "display": "table-row"
            });
        } else if ( loginRadiusStatusOption[i].checked && loginRadiusStatusOption[i].value == '0' ) {
            jQuery('#loginRadiusDefaultStatus').hide();
        }
    }
    // email required
    var loginRadiusEmailRequired = jQuery('[name="LoginRadius_settings[LoginRadius_dummyemail]"]');
    for ( var i = 0; i < loginRadiusEmailRequired.length; i++ ) {
        if ( loginRadiusEmailRequired[i].checked && loginRadiusEmailRequired[i].value == 'notdummyemail' ) {
            jQuery('#loginRadiusPopupMessage').show();
            jQuery('#loginRadiusPopupErrorMessage').show();
        } else if ( loginRadiusEmailRequired[i].checked && loginRadiusEmailRequired[i].value == 'dummyemail' ) {
            jQuery('#loginRadiusPopupMessage').hide();
            jQuery('#loginRadiusPopupErrorMessage').hide();
        }
    }

    // Registration redirection
    var loginRadiusRegisterRedirection = jQuery('[name="LoginRadius_settings[LoginRadius_regRedirect]"]');
    for ( var i = 0; i < loginRadiusRegisterRedirection.length; i++ ) {
        if ( loginRadiusRegisterRedirection[i].checked ) {
            jQuery('#loginRadiusCustomRegistrationUrl').hide();
            if ( loginRadiusRegisterRedirection[i].value == "custom" ) {
                jQuery('#loginRadiusCustomRegistrationUrl').show();
            }

        }
    }
    // Hiding social Login position for registration page, if not enabled
    var registrationFormOption = jQuery('#showonregistrationpageyes');
    if ( registrationFormOption ) {
        if ( registrationFormOption.checked ) {
            jQuery('#registration_interface').show();
        } else {
            jQuery('#registration_interface').hide();
        }
    }
    // login redirection
    var loginRadiusLoginRedirection = jQuery('[name="LoginRadius_settings[LoginRadius_redirect]"]');
    for ( var i = 0; i < loginRadiusLoginRedirection.length; i++ ) {
        if ( loginRadiusLoginRedirection[i].checked ) {
            jQuery('#loginRadiusCustomLoginUrl').hide();
            if ( loginRadiusLoginRedirection[i].value == "custom" ) {
                jQuery('#loginRadiusCustomLoginUrl').show();
            }

        }
    }
    // logout redirection
    var loginRadiusLogoutRedirection = jQuery('[name="LoginRadius_settings[LoginRadius_loutRedirect]"]');
    for ( var i = 0; i < loginRadiusLogoutRedirection.length; i++ ) {
        if ( loginRadiusLogoutRedirection[i].checked ) {
            if ( loginRadiusLogoutRedirection[i].value == "homepage" ) {
                jQuery('#loginRadiusCustomLogoutUrl').hide();
            } else if ( loginRadiusLogoutRedirection[i].value == "custom" ) {
                jQuery('#loginRadiusCustomLogoutUrl').show();
            }
        }
    }
}

// prepare rearrange provider list
function loginRadiusRearrangeProviderList( elem, sharingType ) {
        var ul = jQuery('#loginRadius' + sharingType + 'Sortable');
        if ( elem.checked ) {

            var listItem = jQuery('<li />')
                .addClass('lrshare_iconsprite32 lrshare_' + elem.value.toLowerCase())
                .attr({
                    id: 'loginRadius' + sharingType + "LI" + elem.value,
                    title: elem.value
                });
            // append hidden field
            var provider = jQuery('<input>')
                .attr({
                    type: 'hidden',
                    name: 'LoginRadius_settings[' + sharingType.toLowerCase() + '_rearrange_providers][]',
                    value: elem.value
                });
            listItem.append(provider);
            ul.append(listItem);

        } else {
            if ( jQuery('#loginRadius' + sharingType + 'LI' + elem.value)) {
                jQuery('#loginRadius' + sharingType + 'LI' + elem.value).remove();
            }
        }
    }
    // limit maximum number of providers selected in horizontal sharing

function loginRadiusSharingLimit( elem, key ) {
    var sharingProviders = loginRadiusHorizontalSharingProviders;
    var errorDiv = jQuery('#loginRadiusHorizontalSharingLimit');
    if ( key == 'vertical' ) {
        sharingProviders = loginRadiusVerticalSharingProviders;
        var errorDiv = jQuery('#loginRadiusVerticalSharingLimit');
    }

    var checkCount = 0;
    for ( var i = 0; i < sharingProviders.length; i++ ) {
        if ( sharingProviders[i].checked ) {
            // count checked providers
            checkCount++;
            if ( checkCount >= 10 ) {
                elem.checked = false;
                errorDiv.show();
                setTimeout(function() {
                    errorDiv.hide();
                }, 2000);
                return;
            }
        }
    }
}

// show/hide options according to the selected horizontal sharing theme
function loginRadiusToggleHorizontalShareTheme( theme ) {

    jQuery('#login_radius_horizontal_sharing_providers_container').hide();
    jQuery('#login_radius_horizontal_rearrange_container').hide();
    jQuery('#login_radius_horizontal_counter_providers_container').hide();
    jQuery('#login_radius_horizontal_providers_container').hide();
    var displayArray = [];

    switch ( theme ) {
        case '32' || '16':
            displayArray[0] = 'login_radius_horizontal_rearrange_container';
            displayArray[1] = 'login_radius_horizontal_sharing_providers_container';
            displayArray[2] = 'login_radius_horizontal_providers_container';
            break;

        case 'counter_vertical' || 'counter_horizontal':
            displayArray[0] = 'login_radius_horizontal_counter_providers_container';
            displayArray[1] = 'login_radius_horizontal_providers_container';
            break;

        default:
            break;
    }
    for ( i = 0; i < displayArray.length; i++ ) {
        jQuery('#' + displayArray[i]).show();
    }
}

// display options according to the selected counter theme
function loginRadiusToggleVerticalShareTheme( theme ) {

    jQuery('#login_radius_vertical_rearrange_container').hide();
    jQuery('#login_radius_vertical_sharing_providers_container').hide();
    jQuery('#login_radius_vertical_counter_providers_container').hide();

    var displayVerticalArray = [];
    switch ( theme ) {
        case '32' || '16':
            displayVerticalArray[0] = 'login_radius_vertical_rearrange_container';
            displayVerticalArray[1] = 'login_radius_vertical_sharing_providers_container';
            break;

        case 'counter_vertical' || 'counter_horizontal':
            displayVerticalArray[0] = 'login_radius_vertical_counter_providers_container';
            break;

    }
    for ( i = 0; i < displayVerticalArray.length; i++ ) {
        jQuery('#' + displayVerticalArray[i]).show();
    }
}

// assign update code function onchange event of elements
function loginRadiusAttachFunction( elems ) {
    for ( var i = 0; i < elems.length; i++ ) {
        elems[i].onchange = loginRadiusToggleTheme;
    }
}

function loginRadiusGetChecked( elems ) {
    var checked = [];
    // loop over all
    for ( var i = 0; i < elems.length; i++ ) {
        if ( elems[i].checked ) {
            checked.push( elems[i].value );
        }
    }
    return checked;
}
jQuery(document).ready(function() {
    jQuery("#loginRadiusHorizontalSortable, #loginRadiusVerticalSortable").sortable({
        revert: true
    });

    function hideAndShowCustomUrlBox(element, inputBoxName) {
        if (element.is(':checked') && element.val() == "custom") {
            jQuery('#' + inputBoxName).show();
        } else {
            jQuery('#' + inputBoxName).hide();
        }
    }

    function display_element(elem, elementToShow) {
        if (elem.is(":checked")) {
            jQuery('#' + elementToShow).show();
        }
    };

    function hide_element(elem, elementToHide) {
        if (elem.is(":checked")) {
            jQuery('#' + elementToHide).hide();
        }
    }

    jQuery(".horizontalCounters").click(function() {
        jQuery("#login_radius_horizontal_counter_providers_container,#login_radius_horizontal_providers_container").show();
        jQuery("#login_radius_horizontal_rearrange_container,#login_radius_horizontal_sharing_providers_container").hide();
    });

    jQuery('.horizontalSharingThemesTop').click(function() {
        jQuery("#login_radius_horizontal_rearrange_container,#login_radius_horizontal_sharing_providers_container,#login_radius_horizontal_providers_container").show();
        jQuery("#login_radius_horizontal_counter_providers_container").hide();

    });

    jQuery(".horizontalSharingSingle").click(function() {
        jQuery("#login_radius_horizontal_providers_container,#login_radius_horizontal_rearrange_container").hide();
    });



    jQuery('.verticalSharingThemesTop').click(function() {
        jQuery("#login_radius_vertical_rearrange_container,#login_radius_vertical_sharing_providers_container").show();
        jQuery("#login_radius_vertical_counter_providers_container").hide();
    });

    jQuery("#login_radius_sharing_vertical_16").click(function() {
        jQuery("#login_radius_vertical_rearrange_container,#login_radius_vertical_sharing_providers_container").show();
        jQuery("#login_radius_vertical_counter_providers_container").hide();
    });

    jQuery(".verticalCounters").click(function() {
        jQuery("#login_radius_vertical_counter_providers_container").show();
        jQuery("#login_radius_vertical_rearrange_container,#login_radius_vertical_sharing_providers_container").hide();
    });


    jQuery("#show_horizontal_theme_content").click(function() {
        jQuery("#login_radius_horizontal").show();
        jQuery("#login_radius_vertical").hide();
    });

    jQuery("#show_vertical_theme_content").click(function() {
        jQuery("#login_radius_horizontal").hide();
        jQuery("#login_radius_vertical").show();
    });


    jQuery('#showonregistrationpageyes').click(function() {
        display_element(jQuery(this), 'registration_interface');

    });
    jQuery('#showonregistrationpageno').click(function() {
        hide_element(jQuery(this), 'registration_interface');

    });
    jQuery('#controlActivationYes').click(function() {
        display_element(jQuery(this), 'loginRadiusDefaultStatus');

    });
    jQuery('#controlActivationNo').click(function() {
        hide_element(jQuery(this), 'loginRadiusDefaultStatus');

    });
    jQuery('#dummyMailYes').click(function() {
        jQuery('#loginRadiusPopupMessage').show();
        jQuery('#loginRadiusPopupErrorMessage').show();

    });
    jQuery('#dummyMailNo').click(function() {
        jQuery('#loginRadiusPopupMessage').hide();
        jQuery('#loginRadiusPopupErrorMessage').hide();

    });


    jQuery('.loginRedirectionRadio').click(function() {
        hideAndShowCustomUrlBox(jQuery(this), 'loginRadiusCustomLoginUrl');

    });

    jQuery('.registerRedirectionRadio').click(function() {
        hideAndShowCustomUrlBox(jQuery(this), 'loginRadiusCustomRegistrationUrl');

    });

    jQuery('.logoutRedirectionRadio').click(function() {
        hideAndShowCustomUrlBox(jQuery(this), 'loginRadiusCustomLogoutUrl');

    });

});