/*
 * create the encoded get string of an form field
 * @param sName: the name of the filed
 * @param sValue: the field content
 * @return: the encoded get string
 */
function encodeNameAndValue(sName, sValue) {
	var sParam = encodeURIComponent(sName);
	sParam += "=";
	sParam += encodeURIComponent(sValue);
	return sParam;				
}

/*
 * convert in a get string the content of the form
 * @param oForm: the form to convert
 * @return: the get string
 */
function getRequestBody(oForm) {
  
      //array to hold the params
      var aParams = new Array();
      
      //get your reference to the form
      //var oForm = document.forms[0];
      
      //iterate over each element in the form
      for (var i=0 ; i < oForm.elements.length; i++) {
      
	  //get reference to the field
	  var oField = oForm.elements[i];
	  
	  //different behavior based on the type of field
	  switch (oField.type) {
	  
	      //buttons - we don't care
	      case "button":
	      case "submit":
	      case "reset":
			break;
	      
	      //checkboxes/radio buttons - only return the value if the control is checked.
	      case "checkbox":
	      case "radio":
		  if (!oField.checked) {
		      break;
		  } //End: if
	      
	      //text/hidden/password all return the value
	      case "text":
	      case "hidden":
	      case "password":
//		  alert(oField.name+" = "+oField.value);
		  aParams.push(encodeNameAndValue(oField.name, oField.value));			
		  break;
	      
	      //everything else
	      default:
	      
		  switch(oField.tagName.toLowerCase()) {
		      case "select":
			  aParams.push(encodeNameAndValue(oField.name, oField.options[oField.selectedIndex].value));
			  break;
		      default:	
			  aParams.push(encodeNameAndValue(oField.name, oField.value));
		  }
	  }							
      
      }
  
      return aParams.join("&");
}
  

