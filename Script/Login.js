/**
 * The method sends a login request to the server
 */
function log_in(){
      
      document.getElementById("wait").setAttribute("src","images/wait.gif");
      document.getElementById("wait").removeAttribute("style");
      var aInput = document.getElementsByTagName("input");
      for (var i=0; i<aInput.length; i++){
	aInput[i].setAttribute("disabled","true");
      }
      var oForm = document.getElementById("loginForm");
      var sBody = getRequestBody(oForm);
      var oXHR = zXmlHttp.createRequest();
      oXHR.open("post", oForm.action, true);
      oXHR.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
      
      oXHR.onreadystatechange = function () {	  
	  if (oXHR.readyState == 4) {
	      if (oXHR.status == 200 || oXHR.status == 304) {
		  var aUser = selectNodes(oXHR.responseXML, "//LOGIN");
		  if (aUser.length > 0){
		      hideLogin();
		      var aName = selectNodes(oXHR.responseXML, "//NAME");
		      var aLevel = selectNodes(oXHR.responseXML, "//ACCESS_LEVEL");
		      if (getText(aLevel[0])==3){
			  document.getElementById("logreg").innerHTML = '<ul>'+ 
									    '<li><span>Welcome '+getText(aName[0])+'!<span></li>'+
									    '<li id="liMyPage"><a href="UserPageCMS.php">MyPage</a></li>'+
									    '<li id="liAdminPage"><a href="AdminPageCMS.php">AdminPage</a></li>'+
									    '<li><a id="aLogout" href="#" onclick="logout()">Logout</a></li>'+
									'</ul>';
		      } else {
			  document.getElementById("logreg").innerHTML = '<ul>'+ 
									    '<li><span>Welcome '+getText(aName[0])+'!<span></li>'+
									    '<li id="liMyPage"><a href="UserPageCMS.php">MyPage</a></li>'+    
									    '<li><a id="aLogout" href="#" onclick="logout()">Logout</a></li>'+
									'</ul>';
		      }      
		  } else {
		      //document.getElementById("wait").setAttribute("style","visibility:hidden");
		      document.getElementById("wait").setAttribute("src","images/icon_error2.png");
		      var aInput = document.getElementsByTagName("input");
		      for (var i=0; i<aInput.length; i++){
			aInput[i].removeAttribute("disabled");
		      }
		  }
	      } 
	  }            
      };
      oXHR.send(sBody);

  
}

/*
 * The method sends a disconnect request to the server
 */
function logout(){

      document.getElementById("aLogout").setAttribute("style","color:white");
      var divLogout = document.createElement("div");
      divLogout.setAttribute("id","divLogout");
      divLogout.innerHTML = '<h4 align="center"><img id="wait" src="images/wait6.gif"/></h4>'; 
      document.getElementsByTagName("body")[0].appendChild(divLogout);
      
      var oXHR = zXmlHttp.createRequest();
      oXHR.open("get", "Control/UserControl.php?request=logout", true);
      oXHR.onreadystatechange = function () {
	  if (oXHR.readyState == 4) {
	      document.getElementById("divLogout").parentNode.removeChild(document.getElementById("divLogout"));
	      if (oXHR.status == 200 || oXHR.status == 304) {    
		  document.getElementById("logreg").innerHTML = '<ul>'+
								  '<li><a id="aLogin" href="#" onclick="showLogin()">Login</a></li>'+
								  '<li><a href="RegisterCMS.php">Registration</a></li>'+
								'</ul>';		  		  		
	      } 
	  }            
      };
      oXHR.send();
      
}
  
/**
 * Insert in the page the login form
 */    
function showLogin(){
    
      document.getElementById("aLogin").setAttribute("style","color:white");
      document.getElementById("aLogin").setAttribute("onclick","hideLogin()");

      var divLogin = document.createElement("div");
      divLogin.setAttribute("id","divLogin");
      divLogin.innerHTML = '<form id=\'loginForm\' method="post" action="Control/UserControl.php" onsubmit="log_in(); return false">'+    
				  '<h3 id="LoginMail">E-Mail</h3>'+
				  '<div class="hr"></div>'+
				  '<input type="text" id="mail" name="mail" size="20"/>'+
				  '<h3 id="LoginPassword">Password</h3>'+
				  '<div class="hr"></div>'+
				  '<input type="password" id="passw" name="passw" size="20"/><br/>'+ 
				  '<input id="login" type="submit" value="Login" style="padding:2px 6px" />'+
				  '<input type="hidden" name="request" value="login"/>'+ 
				  '<h4 style="margin-top:0px;" align="center"><img style="visibility:hidden" id="wait" src="null"/></h4>'+
			    '</form>';    


      document.getElementsByTagName("body")[0].appendChild(divLogin);  
}

/**
 * Hide the login form from the page
 */
function hideLogin(){
    
      document.getElementById("divLogin").parentNode.removeChild(document.getElementById("divLogin"));
      document.getElementById("aLogin").setAttribute("onclick","showLogin()");
      document.getElementById("aLogin").removeAttribute("style");
}

