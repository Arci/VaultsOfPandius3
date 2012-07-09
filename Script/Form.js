/*
 * verify if the user insert a valid name in the form
 */
function validateName(){
      
  var name = document.getElementById("name");
  if (name.value == ""){
    document.getElementById("nameImg").innerHTML = "<img style=\"height: 24px\" src=\"images/icon_error.png\"/>";
    document.getElementById("nameMex").innerHTML = "name not inserted";
    return false;
  }
  
  var oXHR2 = zXmlHttp.createRequest();
  oXHR2.open("get", "Control/UserControl.php?request=verify&"+encodeNameAndValue("name", name.value), false);            
  oXHR2.send(null);
  if (oXHR2.status == 200 || oXHR2.status == 304) {		    	   
    //   alert(serializeXML(oXHR2.responseXML));
	var aUser = selectNodes(oXHR2.responseXML, "//USER");
	if (aUser.length > 0){
	    if (getText(aUser[0]) != idUser ){
		document.getElementById("nameImg").innerHTML = "<img style=\"height: 24px\" src=\"images/icon_error2.png\"/>";
		document.getElementById("nameMex").innerHTML = "name not available";
		return false;
	    }
	}
	document.getElementById("nameImg").innerHTML = "<img style=\"height: 24px\" src=\"images/icon_ok.png\"/>";
	document.getElementById("nameMex").innerHTML = "";
	return true;
  }
  
}

/*
 * verify if the user insert a valid mail in the form
 */
function validateMail() {
  
  if ( !document.getElementById("mail").value ) {
    document.getElementById("mailImg").innerHTML = "<img style=\"height: 24px\" src=\"images/icon_error.png\"/>";
    document.getElementById("mailMex").innerHTML = "mail address not inserted";
    return false;
  } 
  //var expression = new RegExp('^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+[\.]([a-z0-9-]+)*([a-z]{2,3})$');
  var expression = new RegExp("[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?");
  var string = document.getElementById("mail").value;
  if (!expression.test(string)) {
    document.getElementById("mailImg").innerHTML = "<img style=\"height: 24px\" src=\"images/icon_error2.png\"/>";
    document.getElementById("mailMex").innerHTML = "invalid address";
    return false;
  }
  
  var oXHR3 = zXmlHttp.createRequest();
  oXHR3.open("get", "Control/UserControl.php?request=verify&"+encodeNameAndValue("mail", string), false);            
  oXHR3.send(null);
  if (oXHR3.status == 200 || oXHR3.status == 304) {		    	   
    //   alert(serializeXML(oXHR3.responseXML));
	var aUser = selectNodes(oXHR3.responseXML, "//USER");
	if (aUser.length > 0){
	    if (getText(aUser[0]) != idUser ){
		document.getElementById("mailImg").innerHTML = "<img style=\"height: 24px\" src=\"images/icon_error2.png\"/>";
		document.getElementById("mailMex").innerHTML = "address already existing";
		return false;
	    }
	}
	document.getElementById("mailImg").innerHTML = "<img style=\"height: 24px\" src=\"images/icon_ok.png\"/>";
	document.getElementById("mailMex").innerHTML = "";
	return true;
  }
	
}

/*
 * verify if the user insert a valid password in the form
 */
function validatePassword() {
  
  var pwd1 = document.getElementById("passw");
  if(pwd1.value.length < 6) {
      document.getElementById("passwImg").innerHTML = "<img style=\"height: 24px\" src=\"images/icon_error.png\"/>";
      document.getElementById("passwMex").innerHTML = "password must be at least six chars";
      return false;
  } 
  document.getElementById("passwImg").innerHTML = "<img style=\"height: 24px\" src=\"images/icon_ok.png\"/>";
  document.getElementById("passwMex").innerHTML = "";
  return true;
}


/*
 * checks if the password and password verification coincide
 */
function comparePassword(){
  
  var pwd1 = document.getElementById("passw");
  var pwd2 = document.getElementById("rpassw");
  if(pwd1.value != "" && pwd2.value == "") {
      document.getElementById("rpasswImg").innerHTML = "<img style=\"height: 24px\" src=\"images/icon_error.png\"/>";
      document.getElementById("rpasswMex").innerHTML = "password must be retype";
      return false;
  }
  if (pwd1.value != pwd2.value){
      document.getElementById("rpasswImg").innerHTML = "<img style=\"height: 24px\" src=\"images/icon_error2.png\"/>";
      document.getElementById("rpasswMex").innerHTML = "passwords are different";
      return false;
  }
  document.getElementById("rpasswImg").innerHTML = "<img style=\"height: 24px\" src=\"images/icon_ok.png\"/>";
  document.getElementById("rpasswMex").innerHTML = "";
  return true;
}