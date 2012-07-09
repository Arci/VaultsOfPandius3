/**
 * The method sends a disconnect request to the server
 * Used in the User and Admin page
 */
function logout(){
      var oXHR = zXmlHttp.createRequest();
      oXHR.open("get", "Control/UserControl.php?request=logoutredirect", true);
      oXHR.onreadystatechange = function () {
	  if (oXHR.readyState == 4) {
	      if (oXHR.status == 200 || oXHR.status == 304) {   
		 location.href='indexPage.php';
	      } 
	  }            
      };
      oXHR.send();
      
}
