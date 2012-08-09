
//variable used only for the test, then replaced by PHP
var idUser;
        
var newContent = false;
var editContent = false;
var modifyInfo = false;
var viewPending = false;
var viewPublished = false;
	
var previous = 0;
var previousContainer;
	
var storeDivGeneral;
	
var storeDivContent;
var storeDivModifyInfo;
var storeDivView;        
var storeDivIndex;

var storeTempIndex;
var storeMainIndex;
	
var linkSelected;
var contentSelected;

	
function linkTo(ref){
    var infoContent = new Array();
	    
    var content = document.getElementById("contentView");	  
    content.innerHTML = "";
    var oXHR = zXmlHttp.createRequest();
    oXHR.open("get", "Control/GetContent.php?request=link&ref="+ref, true);
    oXHR.onreadystatechange = function () {
        if (oXHR.readyState == 4) {
            if (oXHR.status == 200 || oXHR.status == 304) {                         			
                content.innerHTML += oXHR.responseText;
                infoContent.push(oXHR.responseText);
                indexHistory++;
                aHistory.length = indexHistory+1;
                document.getElementById("imgRight").setAttribute("style","visibility:hidden");
                document.getElementById("imgLeft").removeAttribute("style");
            } 
        }            
    };
    oXHR.send(null);
	  
    var title = document.getElementById("title");
    var author = document.getElementById("author");
    var date = document.getElementById("date");
    var source = document.getElementById("source");

    title.innerHTML = "";
    author.innerHTML = "";
    date.innerHTML = "";
    source.innerHTML = "";
    var oXHR2 = zXmlHttp.createRequest();
    //oXHR2.open("get", "GetInfo.php?id="+id, true);
    oXHR2.open("get", "Control/GetInfo.php?request=link&ref="+ref, true);
    oXHR2.onreadystatechange = function () {
        if (oXHR2.readyState == 4) {
            if (oXHR2.status == 200 || oXHR2.status == 304) {
                var aTitle = selectNodes(oXHR2.responseXML, "//TITLE");
                title.innerHTML = getText(aTitle[0]);
                infoContent.push(getText(aTitle[0]));
                var aAuthor = selectNodes(oXHR2.responseXML, "//AUTHOR");
                var auth = "";
		for(var i = 0; i < aAuthor.length; i++){
		    auth +=getText(aAuthor[i])+", ";
		    if(i == aAuthor.length - 1){
			auth = auth.substring(0,auth.length-2);
		    }
		}
                author.innerHTML +="<i>Author: </i><b>"+auth+"</b>";
                infoContent.push(auth);
		var aSource = selectNodes(oXHR2.responseXML, "//SOURCE");
		if(getText(aSource[0]) != "Unknown"){
		    source.innerHTML +="<i>Source: </i>"+getText(aSource[0]);
		    infoContent.push(getText(aSource[0]));
		}
                var aDate = selectNodes(oXHR2.responseXML, "//DATE");
                date.innerHTML +="<i>Date: </i>"+getText(aDate[0]);
                infoContent.push(getText(aDate[0]));
                aHistory[indexHistory] = infoContent;
            } 
        }            
    };
    oXHR2.send(null);
}

/**
 * Hide all div from the body of the page
 */
function hideAll(id) {
	  
    storeDivContent = document.getElementById("storeDivContent").innerHTML;	  
    storeDivModifyInfo = document.getElementById("storeDivModifyInfo").innerHTML;
    storeDivView = document.getElementById("storeDivView").innerHTML;
    storeDivIndex = document.getElementById("divIndex").innerHTML;
    document.getElementById("divGeneral").innerHTML = "<div id=\"fakeDiv\"></div>";
    document.getElementById("divIndex").innerHTML = "";
    document.getElementById("divIndex").setAttribute("style","visibility:hidden");
    idUser = id;
}

/*
 * Show the second level menu
 * @param option: the name of the second level menu
 */
function show(option){
    switch (option) {	  
        case "new":
            newContent = true;
            editContent = false;
            modifyInfo = false;
            viewPending = false;
            viewPublished = false;
            contentSelected = null;
            if(document.getElementById(linkSelected)){
                document.getElementById(linkSelected).removeAttribute("style");
            }
            linkSelected = document.getElementById("aNew").getAttribute("id");
            document.getElementById(linkSelected).setAttribute("style","color:#07b; font-weight:bold");
		
            document.getElementById("divGeneral").innerHTML = "";
            document.getElementById("divGeneral").innerHTML = storeDivContent;		
            document.getElementById("divIndex").removeAttribute("style");
            document.getElementById("divIndex").innerHTML = storeDivIndex;
            document.getElementById("h2index").innerHTML = "Location";
				
            var divTextArea = document.createElement("textarea");
            divTextArea.setAttribute("id","textHtml");		
            divTextArea.setAttribute("name","textHtml");
            divTextArea.setAttribute("class","widgEditor nothing");
            document.getElementById("divHidden").appendChild(divTextArea);
            widgInit();	      
            break;
	      
        case "edit":
            newContent = false;
            editContent = true;
            modifyInfo = false;
            viewPending = false;
            viewPublished = false;
            contentSelected = null;
            if(document.getElementById(linkSelected)){
                document.getElementById(linkSelected).removeAttribute("style");
            }
            linkSelected = document.getElementById("aEdit").getAttribute("id");
            document.getElementById(linkSelected).setAttribute("style","color:#07b; font-weight:bold");
            document.getElementById("divGeneral").innerHTML = "";
            document.getElementById("divGeneral").innerHTML = storeDivContent;
            document.getElementById("divIndex").removeAttribute("style");
            document.getElementById("divIndex").innerHTML = storeDivIndex;
            document.getElementById("h2index").innerHTML = "Selection";      
            break;
	      
        case "modify":
            newContent = false;
            editContent = false;
            modifyInfo = true;
            viewPending = false;
            viewPublished = false;
            contentSelected = null;
            if(document.getElementById(linkSelected)){
                document.getElementById(linkSelected).removeAttribute("style");
            }
            linkSelected = document.getElementById("aModify").getAttribute("id");
            document.getElementById(linkSelected).setAttribute("style","color:#07b; font-weight:bold");
            document.getElementById("divGeneral").innerHTML = "";		
            document.getElementById("divGeneral").innerHTML = storeDivModifyInfo;
            document.getElementById("divIndex").setAttribute("style","visibility:hidden; width:20%");		
            document.getElementById("divIndex").innerHTML = "";
            fillInfo();		
            break;
	      
        case "pending":
            newContent = false;
            editContent = false;
            modifyInfo = false;
            viewPending = true;
            viewPublished = false;
            contentSelected = null;
            if(document.getElementById(linkSelected)){
                document.getElementById(linkSelected).removeAttribute("style");
            }
            linkSelected = document.getElementById("aPending").getAttribute("id");
            document.getElementById(linkSelected).setAttribute("style","color:#07b; font-weight:bold");
            document.getElementById("divGeneral").innerHTML = "";
            document.getElementById("divGeneral").innerHTML = storeDivView;
            document.getElementById("divIndex").removeAttribute("style");
            document.getElementById("divIndex").innerHTML = storeDivIndex;
            document.getElementById("h2index").innerHTML = "Pending";
            break;
		
        case "published":
            newContent = false;
            editContent = false;
            modifyInfo = false;
            viewPending = false;
            viewPublished = true;
            contentSelected = null;
            if(document.getElementById(linkSelected)){
                document.getElementById(linkSelected).removeAttribute("style");
            }
            linkSelected = document.getElementById("aPublished").getAttribute("id");
            document.getElementById(linkSelected).setAttribute("style","color:#07b; font-weight:bold");
            document.getElementById("divGeneral").innerHTML = "";
            document.getElementById("divGeneral").innerHTML = storeDivView;
            document.getElementById("divIndex").removeAttribute("style");
            document.getElementById("divIndex").innerHTML = storeDivIndex;
            document.getElementById("h2index").innerHTML = "Published";
            break;
		
    }
}
	
function fillInfo() {
    var oXHR = zXmlHttp.createRequest();
    oXHR.open("get", "Control/UserControl.php?request=getuser&id="+idUser, true);            
    oXHR.onreadystatechange = function () {
        if (oXHR.readyState == 4) {
            if (oXHR.status == 200 || oXHR.status == 304) {
                var aName = selectNodes(oXHR.responseXML, "//NAME");
                document.getElementById("name").value = getText(aName[0]);
                var aMail = selectNodes(oXHR.responseXML, "//MAIL");
                document.getElementById("mail").value = getText(aMail[0]);		      
            } 
        }            
    };
    oXHR.send(null);
}

/*
 * Verify the input and submit a form
 */
function sendRequest(){
	  
    if (validateName() && validateMail() && validatePassword() && comparePassword()) {
		
        document.getElementById("spanModify").innerHTML = '<img class="imgWait" src="images/wait.gif"/>';
	    
        document.getElementById("nameImg").innerHTML = '';
        document.getElementById("mailImg").innerHTML = '';
        document.getElementById("passwImg").innerHTML = '';
        document.getElementById("rpasswImg").innerHTML = '';
		
        document.getElementById("name").setAttribute("disabled","true");
        document.getElementById("mail").setAttribute("disabled","true");
        document.getElementById("passw").setAttribute("disabled","true");
        document.getElementById("rpassw").setAttribute("disabled","true");
        document.getElementById("submitModify").setAttribute("disabled","true");
		
        document.getElementById("AuthorId").value = idUser;
        var oForm = document.getElementById("requestForm");
		
        var sBody = getRequestBody(oForm);

        var oXHR = zXmlHttp.createRequest();
        oXHR.open("post", oForm.action, true);
        oXHR.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		
        oXHR.onreadystatechange = function () {
            if (oXHR.readyState == 4) {
                document.getElementById("spanModify").innerHTML = '';
                if (oXHR.status == 200) {
                    document.getElementById("spanModify").innerHTML = "<img style=\"height: 24px\" src=\"images/icon_ok.png\"/>";
                } else {
                    document.getElementById("spanModify").innerHTML = "<img style=\"height: 24px\" src=\"images/icon_error2.png\"/>";
                    document.getElementById("name").removeAttribute("disabled");
                    document.getElementById("mail").removeAttribute("disabled");
                    document.getElementById("passw").removeAttribute("disabled");
                    document.getElementById("rpassw").removeAttribute("disabled");
                    document.getElementById("submitModify").removeAttribute("disabled");
                }
            }            
        };
        oXHR.send(sBody); 
    }
}
	
/*
 * The method sends the modified content
 */
function submitContent() {
	    
    if (newContent){
				
        document.getElementById("request").value = "create";
        document.getElementById("linkId").value = document.getElementById("idLinkInput").value.substring(1);
        document.getElementById("linkName").value = document.getElementById("titleIndex").value;
		
        var oForm = document.getElementById("contentForm");
        var sBody = getRequestBody(oForm);
		
        document.getElementById("titleIndex").setAttribute("disabled","true");
        document.getElementById("submitId").setAttribute("disabled","true");
        document.getElementById("title").setAttribute("disabled","true");
        document.getElementById("span").innerHTML = '<img class="imgWait" src="images/wait.gif"/>';

		
        var oXHR = zXmlHttp.createRequest();
        oXHR.open("post", oForm.action, true);
        oXHR.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		
        oXHR.onreadystatechange = function () {
            if (oXHR.readyState == 4) {
                if (oXHR.status == 200) {
                    show("pending");
                }
            }            
        };
		
        oXHR.send(sBody);
	      
    } else if (editContent){
		
        document.getElementById("request").value = "modify";
		
        var oForm = document.getElementById("contentForm");
        var sBody = getRequestBody(oForm);

        var oXHR = zXmlHttp.createRequest();
        oXHR.open("post", oForm.action, true);
        oXHR.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		
        oXHR.onreadystatechange = function () {
            if (oXHR.readyState == 4) {
                if (oXHR.status == 200) {
                    show("edit");

                }
            }            
        };
		
        oXHR.send(sBody);
    }
	  
	  
}

/*
 * The method sends the information content and shows it
 * @param id: the id of the content 
 */
function fillContent(id) {
	    
    if(document.getElementById(contentSelected)){
        document.getElementById(contentSelected).firstChild.nextSibling.removeAttribute("style");
    }
    contentSelected = "c"+id;
    document.getElementById(contentSelected).firstChild.nextSibling.setAttribute("style","color:#333; font-weight:bold");
	    
    if (editContent){
		
        document.getElementById("inputId").value = id;
		
        var oXHR = zXmlHttp.createRequest();
        oXHR.open("get", "Control/GetContent.php?request=content&id="+id, true);            
        oXHR.onreadystatechange = function () {
            if (oXHR.readyState == 4) {
                if (oXHR.status == 200 || oXHR.status == 304) {
                    if(document.getElementById("textHtml")){
                        document.getElementById("divHidden").removeChild(document.getElementById("divHidden").childNodes[1]);
                    }
                    var divTextArea = document.createElement("textarea");
                    divTextArea.setAttribute("id","textHtml");
                    divTextArea.value = oXHR.responseText;
                    divTextArea.setAttribute("name","textHtml");
                    divTextArea.setAttribute("class","widgEditor nothing");
                    document.getElementById("divHidden").appendChild(divTextArea);
                    widgInit();
                } 
            }            
        };				
        oXHR.send(null);
		
        var title = document.getElementById("title");

        var oXHR2 = zXmlHttp.createRequest();
        oXHR2.open("get", "GetInfo.php?request=content&id="+id, true);
        oXHR2.onreadystatechange = function () {
            if (oXHR2.readyState == 4) {
                if (oXHR2.status == 200 || oXHR2.status == 304) {                         			
                    var aTitle = selectNodes(oXHR2.responseXML, "//TITLE");
                    title.value = getText(aTitle[0]);			      	      
                } 
            }            
        };
        oXHR2.send(null);
		
    } else if (viewPending || viewPublished){	
        document.getElementById("divView").removeAttribute("style");	  
        var content = document.getElementById("contentView");	  
        content.innerHTML = "";
        var oXHR = zXmlHttp.createRequest();
        oXHR.open("get", "Control/GetContent.php?request=content&id="+id, true);
        oXHR.onreadystatechange = function () {
            if (oXHR.readyState == 4) {
                if (oXHR.status == 200 || oXHR.status == 304) {                         			
                    content.innerHTML += oXHR.responseText;
                } 
            }            
        };
        oXHR.send(null);
		
        var title = document.getElementById("titleView");
        var date = document.getElementById("dateView");

        title.innerHTML = "";		
        date.innerHTML = "";
        var oXHR2 = zXmlHttp.createRequest();
        oXHR2.open("get", "GetInfo.php?request=content&id="+id, true);
        oXHR2.onreadystatechange = function () {
            if (oXHR2.readyState == 4) {
                if (oXHR2.status == 200 || oXHR2.status == 304) {                         			
                    var aTitle = selectNodes(oXHR2.responseXML, "//TITLE");
                    title.innerHTML = getText(aTitle[0]);			      
                    var aDate = selectNodes(oXHR2.responseXML, "//DATE");
                    if (viewPending){
                        date.innerHTML +="<i>Submit Date:</i> "+getText(aDate[0]);
                    } else if (viewPublished) {
                        date.innerHTML +="<i>Publication Date:</i> "+getText(aDate[0]);
                    }
			      
			      
                } 
            }            
        };
        oXHR2.send(null);	      
    }
	    
}
	
	
function insert(id){	  
    if (previous != 0){
        liToRestore = document.getElementById(previous);
        liToRestore.setAttribute("style","list-style-image: url(images/icon-plus.png);");
        liToRestore.innerHTML = previousContainer;
    }
    liPlus = document.getElementById("liAddHere"+id.getAttribute("id"));	  	  
    liPlus.removeAttribute("style");
    previous = "liAddHere"+id.getAttribute("id");
    previousContainer = liPlus.innerHTML;
    liPlus.innerHTML = "<img src='images/doc1.gif' />    <input type=\"text\" id=\"titleIndex\" size=30 /><input type=\"hidden\" id=\"idLinkInput\" value=\""+id.getAttribute("id")+"\" />";
}
	
/*
 * Expand a submenu
 * @param id: the submenu names
 */	
function expand(id){
    var liTarget = document.getElementById("i"+id);	  
    liTarget.firstChild.removeAttribute("src");
    liTarget.firstChild.setAttribute("src","images/folder_64.png");
    liTarget.firstChild.nextSibling.removeAttribute("onclick");
    liTarget.firstChild.nextSibling.setAttribute("onclick","contract("+id+")");
    liTarget.firstChild.nextSibling.setAttribute("style","color: #07b; font-weight:bold;");

    var oXHR = zXmlHttp.createRequest();
    if (newContent){
        author="all";
        queryString = "Control/IndexControl.php?id="+id+"&author=all";
    } else if (editContent) {
        author = idUser;
        queryString = "Control/IndexControl.php?id="+id+"&author="+idUser;
    } else if (viewPending) {
        author = idUser;
        queryString = "Control/IndexControl.php?id="+id+"&author="+idUser+"&pending=true";
    } else if (viewPublished) {
        author = idUser;
        queryString = "Control/IndexControl.php?id="+id+"&author="+idUser;
    }
    oXHR.open("get", queryString, true);
    oXHR.onreadystatechange = function () {
        if (oXHR.readyState == 4) {
            if (oXHR.status == 200 || oXHR.status == 304) {
                buildIndexList(oXHR.responseXML, liTarget);			
            } 
        }            
    };
    oXHR.send(null);
}

/*
 * Contract a submenu
 * @param id: the submenu names
 */
function contract(id){
    var liTarget = document.getElementById("i"+id);
    liTarget.firstChild.removeAttribute("src");
    liTarget.firstChild.setAttribute("src","images/folder_grey.png");
    liTarget.firstChild.nextSibling.removeAttribute("onclick");
    liTarget.firstChild.nextSibling.setAttribute("onclick","expand("+id+")");
    liTarget.firstChild.nextSibling.removeAttribute("style");

    for (var i=2; i<liTarget.childNodes.length; i++){
        liTarget.removeChild(liTarget.childNodes[i]);
    }	  
    if (previous != 0){
        if (!document.getElementById(previous)){
            previous = 0;
        }
    }
}

/*
 * Create a list from an xml element
 * @param oXmlDom: the xml element
 * @param element: the tag name
 */
function buildIndexList(oXmlDom, element) { 	    
    ul = document.createElement("ul");
    if (newContent){
        var liPlus = document.createElement("li");
        liPlus.setAttribute("id","liAddHere"+element.getAttribute("id"));
        liPlus.setAttribute("style","list-style-image: url(images/icon-plus.png);");      
        liPlus.innerHTML += "<a href=# onclick=\"insert("+element.getAttribute("id")+")\">    <b>add here</b></a>";
        ul.appendChild(liPlus);
    }
	    
    var aLink = selectNodes(oXmlDom, "//LINK");
    for (var i=0; i<aLink.length; i++){
        var li = document.createElement("li");
        li.setAttribute("id","c"+aLink[i].getAttribute("id"));
        if (aLink[i].getAttribute("type")=="content"){
            if (viewPending){    
                li.innerHTML += "<img src=\"images/doc1.gif\" /><a href=# onclick=\"fillContent("+aLink[i].getAttribute("id")+")\" type=\""+aLink[i].getAttribute("type")+"\">    <i>" +getText(aLink[i])+"</i></a>";
            } else {
                li.innerHTML += "<img src=\"images/doc1.gif\" /><a href=# onclick=\"fillContent("+aLink[i].getAttribute("id")+")\" type=\""+aLink[i].getAttribute("type")+"\">    " +getText(aLink[i])+"</a>";
		  
            }
        } else 
        if (aLink[i].getAttribute("type")=="index"){
            li.setAttribute("id","i"+aLink[i].getAttribute("id"));
            li.innerHTML += "<img class=\"img\" src=\"images/folder_grey.png\" /><a href=# onclick=\"expand("+aLink[i].getAttribute("id")+")\" type=\""+aLink[i].getAttribute("type")+"\">    " +getText(aLink[i])+"</a>";
        }	      
        ul.appendChild(li);
    }	    
    element.appendChild(ul);            	  
}

/*
 * Expand the width of the content div
 */ 
function zoomInContent(){
	  
    storeTempIndex = document.getElementById("divIndex").innerHTML;
    storeMainIndex = document.getElementById("divMainIndex").innerHTML;
	  
    document.getElementById("divIndex").setAttribute("style","width: 1%");	  
    document.getElementById("divMainIndex").setAttribute("style", "width: 1%");
	  	 	  
    document.getElementById("divIndex").innerHTML = ""
    document.getElementById("divMainIndex").innerHTML = ""	 
	  
    document.getElementById("divView").setAttribute("style","width:89%");
	  
    document.getElementById("img").setAttribute("onclick","zoomOutContent()");
    document.getElementById("img").setAttribute("src","images/zoom2.jpeg");
}

/*
 * Reduce the width of the content div
 */
function zoomOutContent(){
	  
    document.getElementById("divView").removeAttribute("style");
	  
    document.getElementById("divIndex").removeAttribute("style");
    document.getElementById("divMainIndex").removeAttribute("style");
	  
    document.getElementById("divIndex").innerHTML = storeTempIndex;
    document.getElementById("divMainIndex").innerHTML = storeMainIndex;
	  	 	  	  
    document.getElementById("img").setAttribute("onclick","zoomInContent()");
    document.getElementById("img").setAttribute("src","images/zoom1.jpeg");
	  
	  
}
/*
 * Save the content of the input form in aInput
 */                
function saveResult(sResult, sMessage) {
    var aInput = document.getElementsByTagName("input");
    for (var i=0; i<aInput.length; i++){
        aInput[i].setAttribute("disabled","true");
    }
	    
    var divStatus = document.createElement("div");
    divStatus.setAttribute("id","divStatus");
    divStatus.innerHTML = "<h2 id=h2status>"+sResult+"</h2><div id=\"status\">"+sMessage+"</div>";
    document.getElementById("divGeneral").appendChild(divStatus);	    
}
		
/*
 * Create the list Index submenu
 * @param area: the submenu name
 * @param i: the div
 */       
function GetIndexLevel1(area, i){
    var xmlhttp=new XMLHttpRequest();
    xmlhttp.open("get", "Control/GetMenuIndex.php?menu="+area+".html", false);
    xmlhttp.send();
    var xml=xmlhttp.responseXML;
    xml = selectNodes(xml, "//MENU");
    i.setAttribute("id", "i"+getText(xml[0]));
    expand(getText(xml[0]));
}       