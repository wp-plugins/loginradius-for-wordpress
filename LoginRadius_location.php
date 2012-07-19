<?php
  function loginRadiusLoginInterface()
  {
  		global $LoginRadius_settings;
	 	//$html = Login_Radius_get_interface();
		$lrLogin = ($LoginRadius_settings['LoginRadius_loginform'] == 1) && ($LoginRadius_settings['LoginRadius_loginformPosition'] == "beside");
		$lrRegister = ($LoginRadius_settings['LoginRadius_regform'] == 1) && ($LoginRadius_settings['LoginRadius_regformPosition'] == "beside");
		$script = '<script type="text/javascript">
		$(document).ready(function(){ '.
					'var loginDiv = $("div#login");';
		
		if( $lrLogin && $lrRegister){
			 $script .= 'if($("#loginform").length || $("#registerform").length || $("#lostpasswordform").length)
						{
							$("#loginform").wrap("<div style=\'float:left; width:400px\'></div>").after($("#nav")).after($("#backtoblog"));
							$("#lostpasswordform").wrap("<div style=\'float:left; width:400px\'></div>").after($("#nav")).after($("#backtoblog"));
							$("#registerform").wrap("<div style=\'float:left; width:400px\'></div>").after($("#nav")).after($("#backtoblog"));
							$("div#login").css(\'width\', \'910px\');
							loginDiv.append("<div class=\"login-sep-text float-left\"><h3>OR</h3></div>");
							
							if( $("#registerform").length ) {
								loginDiv.append("<div class=\"login-panel-lr\" style=\"height:178px\" >'.Login_Radius_Connect_button(true).'</div>");
							}else if( $("#lostpasswordform").length ) {
								loginDiv.append("<div class=\"login-panel-lr\" style=\"height:178px\" >'.Login_Radius_Connect_button(true).'</div>");
								$("#lostpasswordform").css("height", "178px");
							}else if( $("#loginform").length ) {
								loginDiv.append("<div class=\"login-panel-lr\" style=\"height:178px\" >'.Login_Radius_Connect_button(true).'</div>");
								$("#loginform").css("height", "178px");
							}
						}';
		}elseif($lrLogin) {
			$script .= 'if($("#loginform").length || $("#lostpasswordform").length){
							$("#loginform").wrap("<div style=\'float:left; width:400px\'></div>").after($("#nav")).after($("#backtoblog"));
							$("#lostpasswordform").wrap("<div style=\'float:left; width:400px\'></div>").after($("#nav")).after($("#backtoblog"));
							$("div#login").css(\'width\', \'910px\');
							loginDiv.append("<div class=\"login-sep-text float-left\"><h3>OR</h3></div>");
							
							if( $("#lostpasswordform").length ) {
								loginDiv.append("<div class=\"login-panel-lr\" style=\"height:178px\" >'.Login_Radius_Connect_button(true).'</div>");
								$("#lostpasswordform").css("height", "178px");
							}else if( $("#loginform").length ) {
								loginDiv.append("<div class=\"login-panel-lr\" style=\"height:178px\" >'.Login_Radius_Connect_button(true).'</div>");
								$("#loginform").css("height", "178px");
							}
						}';
		}elseif($lrRegister){
				$script .= 'if( $("#registerform").length || $("#lostpasswordform").length ){
								$("#registerform").wrap("<div style=\'float:left; width:400px\'></div>").after($("#nav")).after($("#backtoblog"));
								$("#lostpasswordform").wrap("<div style=\'float:left; width:400px\'></div>").after($("#nav")).after($("#backtoblog"));
								$("div#login").css(\'width\', \'910px\');
								loginDiv.append("<div class=\"login-sep-text float-left\"><h3>OR</h3></div>");

								if( $("#lostpasswordform").length ) {
									loginDiv.append("<div class=\"login-panel-lr\" style=\"height:178px\" >'.Login_Radius_Connect_button(true).'</div>");
									$("#lostpasswordform").css("height", "178px");
								}else if( $("#registerform").length ) {
									loginDiv.append("<div class=\"login-panel-lr\" style=\"height:178px\" >'.Login_Radius_Connect_button(true).'</div>");
									$("#loginform").css("height", "178px");
								}
							}';
		}
		
		$script .= 	 '}'.
					 ');'.
					 '</script>';
		echo $script;
  }
