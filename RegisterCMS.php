<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
http://www.w3.org/TR/html4/loose.dtd>
<html>
  <head>
    <title>REGISTER CMS</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" href="Style/base.css"/>
    <style>
	

	  #logreg li#liRegister a{
		  color:white;
	  }
	  	  
	  
	  #divContent {   
	      float:left;
	      width:70%;
	  }
	  
	  #divStatus {   
	      float:right;
	      width:20%;
	      height:50%;
	  }
	  
	  #divConfirm {   
	      float:right;
	      width:20%;
	      height:30%;
	  }
	  
	  #content {
	    margin-left: 10px;
	    margin-right:10px;
	  }
	  
	   #content input {
	    margin-left: 7%;
	    margin-top: 2%;
	    margin-bottom: 2%;
	    padding:4px;
	  }
	  
	  #content span {
	    margin-left: 1%;

	  }
	  
	  #content img {	    
	    margin-bottom:-8px;
	  }
	  
	  #submitRegister {	    
	    padding:4px 20px;
	  }
	  
	  #submitConfirm {	    
	    padding:4px 20px;
	  }
	  
	  #status {
	    margin-left: 10px;
	    margin-right:10px;
	  }
	  
	  #confirm {
	    margin-left: 10px;
	    margin-right:10px;
	  }
	  
	  #divInput {
	    margin-left: 10px;
	    margin-right:10px;
	  }
	  
	   #divStatus input {
	    margin-left: 7%;
	    margin-top: 2%;
	    margin-bottom: 2%;
	    padding:4px;
	  }
	  
	  #status p {
	    margin-left: 7%;
	    margin-right: 7%;	    
	  }
	  
	  #confirm p {
	    margin-left: 7%;
	    margin-right: 7%;	    
	  }
	  
	  .img {
	    height:16px;
	  }
	  
	  .imgWait {
	    height:25px;	    
	  }

    </style>
    <script type="text/javascript" src="Script/zxml.js"></script>
    <script type="text/javascript" src="Script/Xml.js"></script>
    <script type="text/javascript" src="Script/Login.js"></script>
    <script type="text/javascript" src="Script/Post.js"></script>
    <script type="text/javascript" src="Script/Form.js"></script>
    <script type="text/javascript">
    
    idUser=null;
    
    function sendRequest(){
      
	if (validateName() && validateMail() && validatePassword() && comparePassword()) {
	    
	    document.getElementById("spanRegister").innerHTML = '<img class="imgWait" src="images/wait.gif"/>';
	    
	    document.getElementById("nameImg").innerHTML = '';
	    document.getElementById("mailImg").innerHTML = '';
	    document.getElementById("passwImg").innerHTML = '';
	    document.getElementById("rpasswImg").innerHTML = '';
	    
	    document.getElementById("name").setAttribute("disabled","true");
	    document.getElementById("mail").setAttribute("disabled","true");
	    document.getElementById("passw").setAttribute("disabled","true");
	    document.getElementById("rpassw").setAttribute("disabled","true");
	    document.getElementById("submitRegister").setAttribute("disabled","true");
	  
	    var oForm = document.getElementById("requestForm");
	    var sBody = getRequestBody(oForm);

	    var oXHR = zXmlHttp.createRequest();
	    oXHR.open("post", oForm.action, true);
	    oXHR.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	    
	    oXHR.onreadystatechange = function () {
		if (oXHR.readyState == 4) {
		    document.getElementById("spanRegister").innerHTML = '';
		    if (oXHR.status == 200) {
			var aStatus = selectNodes(oXHR.responseXML, "//STATUS");			
			saveResult("Success", serializeXML(aStatus[0]));			
		    } else {
			saveResult("Error", "error "+oXHR.status+": "+oXHR.statusText);
			document.getElementById("name").removeAttribute("disabled");
			document.getElementById("mail").removeAttribute("disabled");
			document.getElementById("passw").removeAttribute("disabled");
			document.getElementById("rpassw").removeAttribute("disabled");
			document.getElementById("submitRegister").removeAttribute("disabled");
		    }
		}            
	    };
	    oXHR.send(sBody); 
	}
    }
    
    
    function sendConfirm(){
	
	document.getElementById("spanConfirm").innerHTML = '<img class="imgWait" src="images/wait.gif"/>';
      
	document.getElementById("code").setAttribute("disabled","true");
	document.getElementById("submitConfirm").setAttribute("disabled","true");
            
	var oForm = document.getElementById("confirmForm");
	var sBody = getRequestBody(oForm);
	
	var oXHR = zXmlHttp.createRequest();
	oXHR.open("post", oForm.action, true);
	oXHR.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	oXHR.onreadystatechange = function () {
	    if (oXHR.readyState == 4) {
		document.getElementById("spanConfirm").innerHTML = '';
		if (oXHR.status == 200) {
		    var aStatus = selectNodes(oXHR.responseXML, "//STATUS");
		    var aName = selectNodes(oXHR.responseXML, "//NAME");
		    saveConfirm("Success", serializeXML(aStatus[0]), getText(aName[0]));		    		    
		    document.getElementById("logreg").innerHTML = '<ul>'+ 
								      '<li><span>Welcome '+getText(aName[0])+'!<span></li>'+
								      '<li id="liMyPage"><a href="UserPageCMS.php">MyPage</a></li>'+
								      '<li id="liUpload"><a href="uploadFile.php">Upload File</a></li>'+
								      '<li><a id="aLogout" href="#" onclick="logout()">Logout</a></li>'+
								  '</ul>';
		} else {
		    saveConfirm("Error", "error "+oXHR.status+": "+oXHR.statusText);
		    document.getElementById("code").removeAttribute("disabled");
		    document.getElementById("submitConfirm").removeAttribute("disabled");
		}
	    }            
	};
	oXHR.send(sBody); 	    	
    }
    
    function saveResult(sResult, sMessage) {	      
	document.getElementById("divStatus").removeAttribute("style");
	document.getElementById("divContent").style.display="none";
	document.getElementById("status").innerHTML = sMessage;            
    }
    
    function saveConfirm(sResult, sMessage, sName) {
	document.getElementById("divConfirm").removeAttribute("style");
	document.getElementById("divStatus").style.display="none";
	document.getElementById("h2confirm").innerHTML = sResult;
	document.getElementById("confirm").innerHTML = sMessage + '<p>You are now logged as '+
					      '<span style=\"color:#08c; font-weight:bold\">'+sName+'</span></p>';
    }
           
  </script>
  </head>
  <body>
<?php include 'Banner.php' ?>   
<div id="central">          
      <div id="divContent" class="shadowbox">	
	  <h2 id=h2content><span style="color:#08c">Step 1 -</span> Create Account</h2>
	  <div class="hr"></div>
	  <div id="content">
	      <form id="requestForm" method="post" action="Control/UserControl.php" onsubmit="sendRequest(); return false";>		  
		  <div id="divName">
		      <h3>Name</h3>
		      <div class="hr"></div>
		      <input type="text" id="name" name="name" onchange="validateName()" size=50/><span id="nameImg"></span><span id="nameMex"></span>
		  </div>
		  <div id="divMail">
		      <h3>E-Mail</h3>
		      <div class="hr"></div>
		      <input type="text" id="mail" name="mail" onchange="validateMail()" size=50/><span id="mailImg"></span><span id="mailMex"></span>
		  </div>
		  <div id="divPassword">
		      <h3>Password</h3>
		      <div class="hr"></div>
		      <input type="password" id="passw" name="passw" onchange="validatePassword()" size=50/><span id="passwImg"></span><span id="passwMex"></span>
		  </div>
		  <div id="divRPassword">
		      <h3>Password (again)</h3>
		      <div class="hr"></div>
		      <input type="password" id="rpassw" name="rpassw" onchange="comparePassword()" size=50/><span id="rpasswImg"></span><span id="rpasswMex"></span>
		  </div>
		  <input id="submitRegister" type="submit" value="Register" style="padding:4px 20px" /><span id="spanRegister"></span>	<!-- VA MESSO l'id DINAMICO -->
		  <input type="hidden" name="request" value="register"/>		      		  
	      </form>
	  </div>	  
      </div>
      <div id="divStatus" class="shadowbox" style="display:none">
	  <h2 id=h2status><span style="color:#08c">Step 2 -</span> Confirm</h2>
	  <div class="hr"></div>
	  <div id="status">		      	
	  </div>
	  <div id="divInput">
	      <form id="confirmForm" method="post" action="Control/UserControl.php" onsubmit="sendConfirm(); return false">
		  <input id="code" type="text" id="code" name="code" size="30" />	      
		  <input type="hidden" name="request" value="confirm"/>
		  <input id="submitConfirm" type="submit" value="Confirm" style="padding:4px 20px" /><span id="spanConfirm"></span>
	      </form>
	  </div>
      </div>
      <div id="divConfirm" class="shadowbox" style="display:none">
	  <h2 id=h2confirm>Esito</h2>
	  <div class="hr"></div>
	  <div id="confirm">
	  </div>
      </div>
</div>
<?php include 'Footer.php' ?>
  </body>
</html>