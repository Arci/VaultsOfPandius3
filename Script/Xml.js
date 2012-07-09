/***************************************************************************************************************************************************************/
/******************************************************************* XML MANAGE FUNCTIONS **********************************************************************/ 
/***************************************************************************************************************************************************************/


function selectNodes(oXmlDom, sXPath){
  
      var oResult = oXmlDom.evaluate(sXPath, oXmlDom, null, XPathResult.ORDERED_NODE_ITERATOR_TYPE, null);
      var aNodes = new Array;
	  if (oResult != null) {
	      var oElement;
	      while ((oElement = oResult.iterateNext())) {
		  aNodes.push(oElement);
	      }
	  }
      return aNodes;
}


function getText(oNode){
      
      var sText;
      if (oNode.hasChildNodes()){
	sText = oNode.firstChild.nodeValue;
	
      } else {
	sText = "link form image";
      }
      return sText;      
}


function serializeXML(oNode){
  
      var oSerializer = new XMLSerializer();
      return oSerializer.serializeToString(oNode);
}