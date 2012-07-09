      
var idUser;

var menuContent = false;
var approveContent = false;
var editContent = false;
	
var usersManage = false;
var manageHome = false;
	
var previous = 0;
var previousContainer;
	
var storeDivGeneral;
	
var storeDivContent;
var storeDivModifyInfo;
var storeDivMail;
var storeDivView;
	        
var storeDivIndex;
var storeDivUsers;
var storeDivSecondIndex;
	
var storemodify;	
var storemail;	
	
var storeTempIndex;
var storeTempMainIndex;
var storeTempSecondIndex;
	
var tempMail;
var tempName;
var tempRole;
var tempPassw;		
	
var linkSelected;
var linkSelectedSecond;
var contentSelected;

/**
 * Hide all div from the body of the page
 */	
function hideAll() {
	 
    storeDivContent = document.getElementById("storeDivContent").innerHTML;	  
    storeDivModifyInfo = document.getElementById("storeDivModifyInfo").innerHTML;
    storeDivMail = document.getElementById("storeDivMail").innerHTML;
    storemail = document.getElementById("sendMail").innerHTML;
    storeDivView = document.getElementById("storeDivView").innerHTML;
    storeDivUsers = document.getElementById("storeDivUsers").innerHTML;
    storeDivIndex = document.getElementById("storeDivIndex").innerHTML;
    storeDivSecondIndex = document.getElementById("storeDivSecondIndex").innerHTML;
    document.getElementById("divGeneral").innerHTML = "<div id=\"fakeDiv\"></div>";
    document.getElementById("divSide").innerHTML="";
    document.getElementById("divBottom").innerHTML="";
	  
}

/*
 * Show the second level menu
 * @param option: the name of the second level menu
 */
function show(option){
    document.getElementById("divGeneral").innerHTML = "<div id=\"fakeDiv\"></div>";
    document.getElementById("divBottom").innerHTML = "";
    switch (option) {	  
	      
	    
        case "users":
            menuContent = false;
            approveContent = false;
            editContent = false;		
            usersManage = true;
            manageHome = false;
            if(document.getElementById(linkSelected)){
                document.getElementById(linkSelected).removeAttribute("style");
            }
            linkSelected = document.getElementById("aUsers").getAttribute("id");
            document.getElementById(linkSelected).setAttribute("style","color:#07b; font-weight:bold");
            document.getElementById("divSide").innerHTML = storeDivUsers;
            document.getElementById("divGeneral").innerHTML = storeDivModifyInfo;
            document.getElementById("divBottom").innerHTML = storeDivMail;		
            document.getElementById("sendMail").innerHTML ="";		
            break;
	      
		
        case "contents":
            menuContent = true;
            approveContent = false;
            editContent = false;		
            usersManage = false;
            manageHome = false;	
            if(document.getElementById(linkSelected)){
                document.getElementById(linkSelected).removeAttribute("style");
            }
            linkSelected = document.getElementById("aContents").getAttribute("id");
            document.getElementById(linkSelected).setAttribute("style","color:#07b; font-weight:bold");		
            document.getElementById("divSide").innerHTML = storeDivSecondIndex;
            break;
		
		
        case "approval":
            menuContent = false;
            approveContent = true;
            editContent = false;		
            usersManage = false;
            manageHome = false;
            document.getElementById("divGeneral").innerHTML = storeDivView;		
            document.getElementById("divSide").innerHTML = storeDivSecondIndex + storeDivIndex;
            if(document.getElementById(linkSelectedSecond)){
                document.getElementById(linkSelectedSecond).removeAttribute("style");
            }
            linkSelectedSecond = document.getElementById("aApproval").getAttribute("id");
            document.getElementById(linkSelectedSecond).setAttribute("style","color:#07b; font-weight:bold");
            break;
		
		
        case "edit":
            menuContent = false;
            approveContent = false;
            editContent = true;		
            usersManage = false;
            manageHome = false;		
            document.getElementById("divGeneral").innerHTML = storeDivContent;
            document.getElementById("divSide").innerHTML = storeDivSecondIndex + storeDivIndex;
            if(document.getElementById(linkSelectedSecond)){
                document.getElementById(linkSelectedSecond).removeAttribute("style");
            }
            linkSelectedSecond = document.getElementById("aEdit").getAttribute("id");
            document.getElementById(linkSelectedSecond).setAttribute("style","color:#07b; font-weight:bold");    
            break;  
    }
	    
	    
}

/*
 * Verify the input and submit a form
 */
function sendRequest(){	
    if (validateName() && validateMail()) {
        document.getElementById("spanModify").innerHTML = '<img class="imgWait" src="images/wait.gif"/>';
		
        document.getElementById("nameImg").innerHTML = '';
        document.getElementById("mailImg").innerHTML = '';
			
        document.getElementById("name").setAttribute("disabled","true");
        document.getElementById("mail").setAttribute("disabled","true");
        document.getElementById("level").setAttribute("disabled","true");
        document.getElementById("passw").setAttribute("disabled","true");
        document.getElementById("submitModify").setAttribute("disabled","true");
	      
        var oForm = document.getElementById("modifyForm");
        var sBody = getRequestBody(oForm);
        var oXHR = zXmlHttp.createRequest();
        oXHR.open("post", oForm.action, true);
        oXHR.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		
        oXHR.onreadystatechange = function () {
            if (oXHR.readyState == 4) {
                document.getElementById("spanModify").innerHTML = '';
                if (oXHR.status == 200) { 
                    document.getElementById("name").removeAttribute("disabled");
                    document.getElementById("mail").removeAttribute("disabled");
                    document.getElementById("level").removeAttribute("disabled");
                    document.getElementById("passw").removeAttribute("disabled");
                    document.getElementById("submitModify").removeAttribute("disabled");
                }
            }            
        };
        oXHR.send(sBody); 
    }
}

/*
 * The method requires the details of a user and includes them in the form for editing
 * @param id: the id of the user
 */
function selectUser(id){	   
	    
    if(document.getElementById(contentSelected)){
        document.getElementById(contentSelected).firstChild.nextSibling.removeAttribute("style");
    }
    contentSelected = "u"+id;
    document.getElementById(contentSelected).firstChild.nextSibling.setAttribute("style","color:#333; font-weight:bold");
	    	    	    
    var oXHR = zXmlHttp.createRequest();
    oXHR.open("get", "Control/UserControl.php?request=getuser&id="+id, true);            
    oXHR.onreadystatechange = function () {
        if (oXHR.readyState == 4) {
            if (oXHR.status == 200 || oXHR.status == 304) {
                document.getElementById("imgMail").setAttribute("onclick","showMail()");
                document.getElementById("imgMail").setAttribute("src","images/zoom1.jpeg");
                document.getElementById("imgMail").setAttribute("style","height:40%");
                document.getElementById("sendMail").innerHTML = "";
                document.getElementById("divSendMail").setAttribute("style","visibility:hidden");
                document.getElementById("central").appendChild(document.getElementById("divBottom"));
                document.getElementById("divModify").removeAttribute("style");
                if(storemodify != null){
                    document.getElementById("modify").innerHTML = storemodify;
                }			
                document.getElementById("name").removeAttribute("disabled");
                document.getElementById("mail").removeAttribute("disabled");
                document.getElementById("level").removeAttribute("disabled");
                document.getElementById("passw").removeAttribute("disabled");
                document.getElementById("submitModify").removeAttribute("disabled");
		      
                var aName = selectNodes(oXHR.responseXML, "//NAME");
                document.getElementById("name").value = getText(aName[0]);
                var aMail = selectNodes(oXHR.responseXML, "//MAIL");
                document.getElementById("mail").value = getText(aMail[0]);		      
                var aId = selectNodes(oXHR.responseXML, "//ID");		      
                document.getElementById("hiddenId").value = getText(aId[0]);
                idUser = getText(aId[0]);
                var aLevel = selectNodes(oXHR.responseXML, "//ACCESS_LEVEL");		      
                document.getElementById("option"+getText(aLevel[0])).setAttribute("selected","");		      
            } 
        }            
    };
    oXHR.send(null);	  
}	
	
/*
 * The method sends the approval of a content
 */	
function sendApprove() {
	    	    
    var oForm = document.getElementById("approveForm");
	    
    var sBody = getRequestBody(oForm);

    var oXHR = zXmlHttp.createRequest();
    oXHR.open("post", oForm.action, true);
    oXHR.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	    
    oXHR.onreadystatechange = function () {
        if (oXHR.readyState == 4) {
            if (oXHR.status == 200) {
                show("approval");

            }
        }            
    };
	    
    oXHR.send(sBody);
}
/*
 * The method sends the delete request of a content
 */	
function sendDelete(){
	  
    var oForm = document.getElementById("deleteForm");
	    
    var sBody = getRequestBody(oForm);

    var oXHR = zXmlHttp.createRequest();
    oXHR.open("post", oForm.action, true);
    oXHR.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	    
    oXHR.onreadystatechange = function () {
        if (oXHR.readyState == 4) {
            if (oXHR.status == 200) {
                show("approval");

            }
        }            
    };
	    
    oXHR.send(sBody);
	  
}

/*
 * The method sends the modified content
 */
function submitContent() {
	  	    
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
		    
    } else if (approveContent){
		
        document.getElementById("approveId").value = id;
        document.getElementById("deleteId").value = id;

	    
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
        var author = document.getElementById("authorView");

        title.innerHTML = "";		
        date.innerHTML = "";
        var oXHR2 = zXmlHttp.createRequest();
        oXHR2.open("get", "GetInfo.php?request=content&id="+id, true);
        oXHR2.onreadystatechange = function () {
            if (oXHR2.readyState == 4) {
                if (oXHR2.status == 200 || oXHR2.status == 304) {                         			
                    var aTitle = selectNodes(oXHR2.responseXML, "//TITLE");
                    title.innerHTML = getText(aTitle[0]);			
                    var aAuthor = selectNodes(oXHR2.responseXML, "//AUTHOR");
                    author.innerHTML ="<i>Author:</i> <b>"+getText(aAuthor[0])+"</b>";
                    var aDate = selectNodes(oXHR2.responseXML, "//DATE");
                    date.innerHTML ="<i>Submit Date:</i> "+getText(aDate[0]);		      
                } 
            }            
        };
        oXHR2.send(null);	      
    }
	    
}
	
function insert(id){	  
    if (previous != 0){
        liToRestore = document.getElementById(previous);
        liToRestore.innerHTML = previousContainer;
    }
	  
    liPlus = document.getElementById("liAddHere"+id);
    liPlus.removeAttribute("style");
    previous = "liAddHere"+id;
    previousContainer = liPlus.innerHTML;
    liPlus.innerHTML = "<img src='images/doc1.gif' />    <input type=\"text\" id=\"titleIndex\" size=30 />";
}

/*
 * Show the user list
 * @param level: the user level
 */
function expandLevel(level){
	    
    var liTarget = document.getElementById("level"+level);
    liTarget.firstChild.nextSibling.removeAttribute("onclick");
    liTarget.firstChild.nextSibling.setAttribute("onclick","contractLevel("+level+")");
	    
    var oXHR = zXmlHttp.createRequest();
    oXHR.open("get", "Control/UserControl.php?request=listusers&level="+level, true);            
    oXHR.onreadystatechange = function () {
        if (oXHR.readyState == 4) {
            if (oXHR.status == 200 || oXHR.status == 304) {
                ul = document.createElement("ul");
                var aUser = selectNodes(oXHR.responseXML, "//USER");
                for(var i=0; i<aUser.length; i++){
                    var li = document.createElement("li");
                    li.setAttribute("id","u"+aUser[i].getAttribute("id"));
                    var code ="<img class=\"userImg\" src=\"images/person4.png\" /><a onclick=\"selectUser("+aUser[i].getAttribute("id")+")\">"+getText(aUser[i])+"</a>";
                    console.log(code);
                    li.innerHTML = code;  
                    ul.appendChild(li);
                }
                liTarget.appendChild(ul);
            } 
        }            
    };
    oXHR.send(null);	    
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
    if (editContent){
        queryString = "Control/IndexControl.php?id="+id+"&author=all";
    } else if (approveContent){
        queryString = "Control/IndexControl.php?id="+id+"&author=all&pending=true";
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
 * Close the list of user
 * @param level: the user level
 */
function contractLevel(level) {
    var liTarget = document.getElementById("level"+level);
    liTarget.firstChild.nextSibling.removeAttribute("onclick");
    liTarget.firstChild.nextSibling.setAttribute("onclick","expandLevel("+level+")");
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
    var aLink = selectNodes(oXmlDom, "//LINK");
    for (var i=0; i<aLink.length; i++){
        var li = document.createElement("li");
        li.setAttribute("id","c"+aLink[i].getAttribute("id"));
        if (aLink[i].getAttribute("type")=="content"){		  		
            if (approveContent){
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
 *Show the mail form
 */
function showMail(){
    storemodify = document.getElementById("modify").innerHTML;
    tempMail = document.getElementById("mail").value;
    tempName = document.getElementById("name").value;
    tempRole = document.getElementById("level").value;
    tempPassw = document.getElementById("passw").value;
    document.getElementById("modify").innerHTML = "";
    document.getElementById("divModify").setAttribute("style","height:7.5%");
    document.getElementById("central").appendChild(document.getElementById("divSide"));
    document.getElementById("divSendMail").setAttribute("style","height:68%");
    document.getElementById("imgMail").setAttribute("style","height:4%");
    document.getElementById("sendMail").innerHTML = storemail;
    document.getElementById("imgMail").setAttribute("onclick","hideMail()");
    document.getElementById("imgMail").setAttribute("src","images/zoom2.jpeg");
    document.getElementById("address").value = tempMail;	  
}
	
/*
 * Hide the mail form
 */
function hideMail(){
    storemail = document.getElementById("sendMail").innerHTML;	  
    document.getElementById("sendMail").innerHTML = "";
    document.getElementById("imgMail").setAttribute("style","height:40%");
    document.getElementById("divSendMail").setAttribute("style","height:7.5%");
    document.getElementById("central").appendChild(document.getElementById("divBottom"));
    document.getElementById("divModify").setAttribute("style","height:68%");
    document.getElementById("modify").innerHTML = storemodify;
    document.getElementById("mail").value = tempMail;
    document.getElementById("name").value = tempName;
    document.getElementById("level").value = tempRole;
    document.getElementById("passw").value = tempPassw;
    document.getElementById("imgMail").setAttribute("onclick","showMail()");
    document.getElementById("imgMail").setAttribute("src","images/zoom1.jpeg");
	  
}
/*
 * Expand the width of the content div
 */                
function zoomInContent(){
	  	  
    storeTempIndex = document.getElementById("divIndex").innerHTML;
    storeTempMainIndex = document.getElementById("divMainIndex").innerHTML;
    storeTempSecondIndex = document.getElementById("divSecondIndex").innerHTML;
	  
    document.getElementById("divIndex").setAttribute("style","width: 1%");
    document.getElementById("divMainIndex").setAttribute("style", "width: 1%");
    document.getElementById("divSecondIndex").setAttribute("style", "width: 1%");
	    
    document.getElementById("divIndex").innerHTML = "";
    document.getElementById("divMainIndex").innerHTML = "";
    document.getElementById("divSecondIndex").innerHTML = "";
	    
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
    document.getElementById("divSecondIndex").removeAttribute("style");
	  	  
    document.getElementById("divIndex").innerHTML = storeTempIndex;
    document.getElementById("divMainIndex").innerHTML = storeTempMainIndex;
    document.getElementById("divSecondIndex").innerHTML = storeTempSecondIndex;
	  	 	  	  
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