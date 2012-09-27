			function createCookie(name,value,seconds) {
				if (seconds) {
					var date = new Date();
					date.setTime(date.getTime()+(seconds*1000));
					var expires = "; expires="+date.toGMTString();
				}
				else var expires = "";
				document.cookie = name+"="+value+expires+"; path=/";
			}
			
			function readCookie(name) {
				var nameEQ = name + "=";
				var ca = document.cookie.split(";");
				for(var i=0;i < ca.length;i++) {
					var c = ca[i];
					while (c.charAt(0)==" ") c = c.substring(1,c.length);
					if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
				}
				return null;
			}
			
			function eraseCookie(name) {
				//createCookie(name,"",-1);
				var date = new Date();
				date.setTime(date.getTime()+((-1)*1000));
				var expires = "; expires="+date.toGMTString();
			
				document.cookie = name + "= ; expires="+expires+"; path=/";
			}
			
			function loginRadiusAjaxJs(loginRadiusCommentTextarea, loginRadiusAction){
				if(loginRadiusAction == "set"){
					createCookie("loginRadiusCommentCk2", loginRadiusCommentTextarea.value.trim(), 30);
				}else{
					loginRadiusCommentTextarea.value = readCookie("loginRadiusCommentCk2");
				}
			}
			 
			 function loginRadiusComments(){
				var loginRadiusCommentTextarea = document.getElementById("comment");
			 	loginRadiusAjaxJs(loginRadiusCommentTextarea, "get");
				var loginRadiusMainWrapper = document.getElementById("loginRadiusMainWrapper");
				var loginRadiusWrapper = document.getElementById("loginRadiusWrapper");
				if(document.getElementById("loginRadiusInterface") != null){
					var loginRadiusInterface = document.getElementById("loginRadiusInterface");
					loginRadiusInterface.style.width = "38%";
					loginRadiusInterface.style.float = "left";
				}else{
					loginRadiusMainWrapper.style.display = "block";
					loginRadiusMainWrapper.style.width = (window.getComputedStyle) ? window.getComputedStyle(loginRadiusCommentTextarea, null).width : loginRadiusCommentTextarea.currentStyle.width;
					
					loginRadiusWrapper.style.width = "";
					loginRadiusWrapper.style.marginTop = "5px";
					loginRadiusWrapper.style.cssFloat = "left";
					loginRadiusWrapper.style.paddingLeft = "0px";
					loginRadiusWrapper.style.borderLeft = "none";
					loginRadiusWrapper.style.marginLeft = "0px";
					
					var loginRadiusMainWrapper = document.getElementById("loginRadiusMainWrapper");
					loginRadiusMainWrapper.style.backgroundColor = "transparent";
					loginRadiusMainWrapper.style.padding = "0";
					
				}
				var loginRadiusElems = document.getElementsByTagName("*"), i;
				for (i in loginRadiusElems){
					if((" " + loginRadiusElems[i].className + " ").indexOf(" " + "form-submit" + " ") > -1){
						loginRadiusElems[i].className = "";
						loginRadiusElems[i].style.clear = "both";
					}
				}
				loginRadiusCommentTextarea.onclick = function(){
														loginRadiusMainWrapper.style.display = "block";
														if(document.getElementById("loginRadiusInterface") != null){
															document.getElementById("loginRadiusLoginMessage").style.display = "block";
														}
														loginRadiusMainWrapper.style.width = (window.getComputedStyle) ? window.getComputedStyle(loginRadiusCommentTextarea, null).width : loginRadiusCommentTextarea.currentStyle.width;
		
													}
				loginRadiusCommentTextarea.onblur = function(){ loginRadiusAjaxJs(loginRadiusCommentTextarea, "set"); }
			}
			
			
			window.onload = loginRadiusComments;
