var storeDivStories;
var storeMainIndex; 
	
var linkSelected;
var contentSelected;
	
var aHistory = new Array();
var indexHistory = -1;

/*
 * Show the previus content
 */	
function backPage(){
    indexHistory--; 
    document.getElementById("content").innerHTML = aHistory[indexHistory][0];
    document.getElementById("title").innerHTML = aHistory[indexHistory][1];
    document.getElementById("author").innerHTML = "<i>Author: </i><b>"+aHistory[indexHistory][2]+"</b>";
    document.getElementById("date").innerHTML = "<i>Date: </i>"+aHistory[indexHistory][3];
    if (indexHistory == 0){
        document.getElementById("imgLeft").setAttribute("style","visibility:hidden");
    }
    document.getElementById("imgRight").removeAttribute("style");

}
/*
 * show the next content in the history
 */	
function forwardPage(){
    indexHistory++; 
    document.getElementById("content").innerHTML = aHistory[indexHistory][0];
    document.getElementById("title").innerHTML = aHistory[indexHistory][1];
    document.getElementById("author").innerHTML = "<i>Author: </i><b>"+aHistory[indexHistory][2]+"</b>";
    document.getElementById("date").innerHTML = "<i>Date: </i>"+aHistory[indexHistory][3];
    if (indexHistory == aHistory.length-1){
        document.getElementById("imgRight").setAttribute("style","visibility:hidden");
    }
    document.getElementById("imgLeft").removeAttribute("style");

}

/*
 * remove the local links
 */
function linkTo(ref){
    var infoContent = new Array();
	    
    var content = document.getElementById("content");	  
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

    title.innerHTML = "";
    author.innerHTML = "";
    date.innerHTML = "";
    var oXHR2 = zXmlHttp.createRequest();
    oXHR2.open("get", "GetInfo.php?request=link&ref="+ref, true);
    oXHR2.onreadystatechange = function () {
        if (oXHR2.readyState == 4) {
            if (oXHR2.status == 200 || oXHR2.status == 304) {
                var aTitle = selectNodes(oXHR2.responseXML, "//TITLE");
                title.innerHTML = getText(aTitle[0]);
                infoContent.push(getText(aTitle[0]));
                var aAuthor = selectNodes(oXHR2.responseXML, "//AUTHOR");
                author.innerHTML +="<i>Author: </i><b>"+getText(aAuthor[0])+"</b>";
                infoContent.push(getText(aAuthor[0]));
                var aDate = selectNodes(oXHR2.responseXML, "//DATE");
                date.innerHTML +="<i>Date: </i>"+getText(aDate[0]);
                infoContent.push(getText(aDate[0]));
                aHistory[indexHistory] = infoContent;
            } 
        }            
    };
    oXHR2.send(null);
}
	
/*
 * Expand the width of the content div
 */  	
function zoomInContent(){
    storeDivStories = document.getElementById("divIndex").innerHTML;
    storeMainIndex = document.getElementById("divMainIndex").innerHTML;
    document.getElementById("divIndex").innerHTML = "";
    document.getElementById("divMainIndex").innerHTML = "";
    document.getElementById("divIndex").setAttribute("style","width: 1%");
    document.getElementById("divMainIndex").setAttribute("style", "width: 1%");
	  
    document.getElementById("divContent").setAttribute("style","width:89%; float:right;");
	  
    document.getElementById("img").setAttribute("onclick","zoomOutContent()");
    document.getElementById("img").setAttribute("src","images/zoom2.jpeg");
}

/*
 * Reduce the width of the content div
 */  
function zoomOutContent(){
	  
    document.getElementById("divIndex").removeAttribute("style");
    document.getElementById("divMainIndex").removeAttribute("style");
    document.getElementById("divIndex").innerHTML = storeDivStories;
    document.getElementById("divMainIndex").innerHTML = storeMainIndex;

	  
    document.getElementById("divContent").removeAttribute("style");
	  	  
    document.getElementById("img").setAttribute("onclick","zoomInContent()");
    document.getElementById("img").setAttribute("src","images/zoom1.jpeg");
	  
	  
}
	
/*
 * Create the index list
 * @param id: the div id
 */
function fillContentIndex(id){
	  
    aHistory.length = 0;
    indexHistory = -1;
    var infoContent = new Array();
	  
    if(document.getElementById(contentSelected)){
        document.getElementById(contentSelected).firstChild.nextSibling.removeAttribute("style");
    }
    contentSelected = "ci"+id;
    document.getElementById(contentSelected).firstChild.nextSibling.setAttribute("style","color:#333; font-weight:bold");
	  
    document.getElementById("divContent").removeAttribute("style");
	  
    var content = document.getElementById("content");	  
    content.innerHTML = "";
    var oXHR = zXmlHttp.createRequest();
    oXHR.open("get", "Control/GetContent.php?request=index&id="+id, true);
    oXHR.onreadystatechange = function () {
        if (oXHR.readyState == 4) {
            if (oXHR.status == 200 || oXHR.status == 304) {                         			
                content.innerHTML += oXHR.responseText;
                infoContent.push(oXHR.responseText);
                indexHistory++;
                document.getElementById("imgLeft").setAttribute("style","visibility:hidden");
                document.getElementById("imgRight").setAttribute("style","visibility:hidden");
            } 
        }            
    };
    oXHR.send(null);
	  
    var title = document.getElementById("title");
    var author = document.getElementById("author");
    var date = document.getElementById("date");

    title.innerHTML = "";
    author.innerHTML = "";
    date.innerHTML = "";
    var oXHR2 = zXmlHttp.createRequest();
    oXHR2.open("get", "GetInfo.php?request=index&id="+id, true);
    oXHR2.onreadystatechange = function () {
        if (oXHR2.readyState == 4) {
            if (oXHR2.status == 200 || oXHR2.status == 304) {
                var aTitle = selectNodes(oXHR2.responseXML, "//TITLE");
                title.innerHTML = getText(aTitle[0]);
                infoContent.push(getText(aTitle[0]));
                var aAuthor = selectNodes(oXHR2.responseXML, "//AUTHOR");
                author.innerHTML +="<i>Author: </i><b>"+getText(aAuthor[0])+"</b>";
                infoContent.push(getText(aAuthor[0]));
                infoContent.push(null);
                aHistory.push(infoContent); 
            } 
        }            
    };
    oXHR2.send(null);
	  
}

/*
 * Get the content and the its information and show them
 * @param id: the id of the content
 */
function fillContent(id){
	  
    aHistory.length = 0;
    indexHistory = -1;
    var infoContent = new Array();
	  
    if(document.getElementById(contentSelected)){
        document.getElementById(contentSelected).firstChild.nextSibling.removeAttribute("style");
    }
    contentSelected = "c"+id;
    document.getElementById(contentSelected).firstChild.nextSibling.setAttribute("style","color:#333; font-weight:bold");
	  
    document.getElementById("divContent").removeAttribute("style");
	  
    var content = document.getElementById("content");	  
    content.innerHTML = "";
    var oXHR = zXmlHttp.createRequest();
    oXHR.open("get", "Control/GetContent.php?request=content&id="+id, true);
    oXHR.onreadystatechange = function () {
        if (oXHR.readyState == 4) {
            if (oXHR.status == 200 || oXHR.status == 304) {                         			
                content.innerHTML += oXHR.responseText;
                infoContent.push(oXHR.responseText);
                indexHistory++;
                document.getElementById("imgLeft").setAttribute("style","visibility:hidden");
                document.getElementById("imgRight").setAttribute("style","visibility:hidden");
                link();
            } 
        }            
    };
    oXHR.send(null);
	  
    var title = document.getElementById("title");
    var author = document.getElementById("author");
    var date = document.getElementById("date");

    title.innerHTML = "";
    author.innerHTML = "";
    date.innerHTML = "";
    var oXHR2 = zXmlHttp.createRequest();
    oXHR2.open("get", "Control/GetInfo.php?request=content&id="+id, true);
    oXHR2.onreadystatechange = function () {
        if (oXHR2.readyState == 4) {
            if (oXHR2.status == 200 || oXHR2.status == 304) {
                var aTitle = selectNodes(oXHR2.responseXML, "//TITLE");
                title.innerHTML = getText(aTitle[0]);
                infoContent.push(getText(aTitle[0]));
                var aAuthor = selectNodes(oXHR2.responseXML, "//AUTHOR");
                author.innerHTML +="<i>Author: </i><b>"+getText(aAuthor[0])+"</b>";
                infoContent.push(getText(aAuthor[0]));
                var aDate = selectNodes(oXHR2.responseXML, "//DATE");
                date.innerHTML +="<i>Date: </i>"+getText(aDate[0]);
                infoContent.push(getText(aDate[0]));
                aHistory.push(infoContent);
                
            } 
        }            
    };
    oXHR2.send(null);  	
    
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
    oXHR.open("get", "Control/IndexControl.php?author=all&id="+id, true);
    oXHR.onreadystatechange = function () {
        if (oXHR.readyState == 4) {
            if (oXHR.status == 200 || oXHR.status == 304) {
                buildIndexList(oXHR.responseXML, liTarget, id, false);
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
}

/*
 * Get the submenu element and create the list
 * @param id: the div id
 * @param title: the name of the menu
 */
function getInitial(id,title) {
    document.getElementById("divIndex").removeAttribute("style");
                
	    
    var oXHR = zXmlHttp.createRequest();
    oXHR.open("get", "Control/IndexControl.php?author=all&id="+id, true);
    oXHR.onreadystatechange = function () {
        if (oXHR.readyState == 4) {
            if (oXHR.status == 200 || oXHR.status == 304) {
                if(document.getElementById(linkSelected)){
                    document.getElementById(linkSelected).firstChild.nextSibling.removeAttribute("style");
                    document.getElementById(linkSelected).firstChild.setAttribute("src","images/folder_grey.png");
                }
                linkSelected = "li"+id;
                document.getElementById(linkSelected).firstChild.nextSibling.setAttribute("style","color:#07b; font-weight:bold");			
                document.getElementById(linkSelected).firstChild.setAttribute("src","images/folder_64.png");
                var divStories = document.getElementById("divIndex");			
                divStories.innerHTML="<h3>Index "+title+"</h3><div class=\"hr\"></div>";		
                buildIndexList(oXHR.responseXML, divStories, id, true);
            } 
        }            
    };
    oXHR.send(null);
    
}

/*
 * Create the list from an xml
 * @param oXmlDom: the xml code
 * @param element: the tag name you want to list
 * @param idIndex: the index id
 * @param external: is it external
 */
function buildIndexList(oXmlDom, element, idIndex, external) {
    ul = document.createElement("ul");
    if (external){
        ul.setAttribute("id","ulExt");
    }
    if (!external){
        var liOfIndex = document.createElement("li");
        liOfIndex.setAttribute("id","ci"+idIndex);
        liOfIndex.innerHTML = "<img class=\"img\" src=\"images/arrow_up.png\" /><a href=\"#\" onclick=\"fillContentIndex("+idIndex+")\">    <i>view index</i></a>";
        ul.appendChild(liOfIndex);
    }	    
    var aLink = selectNodes(oXmlDom, "//LINK");
    for (var i=0; i<aLink.length; i++){
        var li = document.createElement("li");
	      
        if (aLink[i].getAttribute("type")=="content"){
            li.setAttribute("id","c"+aLink[i].getAttribute("id"));
            li.innerHTML += "<img src=\"images/doc1.gif\" /><a href=# onclick=\"fillContent("+aLink[i].getAttribute("id")+")\" type=\""+aLink[i].getAttribute("type")+"\">    " +getText(aLink[i])+"</a>";
					
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
 * Create the list from an xml for the second level menu
 * @param oXmlDom: the xml code
 * @param elementId: the tag id you want to list
 */
function CreateIndexLevel2(oXmlDom, elementId) {   
    var aLink = selectNodes(oXmlDom, "//ELEMENT");
    var ul = document.createElement("ul");
    for (var i=0; i<aLink.length; i++){
        var li = document.createElement("li");
        li.setAttribute("id","li"+aLink[i].getAttribute("id"));
        li.innerHTML += "<img class=\"img\" src=\"images/folder_grey.png\"><a href=# onclick=\"getInitial("+aLink[i].getAttribute("id")+",'"+getText(aLink[i])+"')\"> "+getText(aLink[i])+"</a>";		
        ul.appendChild(li);
    }
    var element=document.getElementById(elementId);
    element.appendChild(ul);            	  
}

/*
 * Create the list Index submenu
 * @param area: the submenu name
 * @param div: the div
 */
function GetIndexLevel1(area, div){
    var xmlhttp=new XMLHttpRequest();
    xmlhttp.open("get", "Control/PageIndexId.php?menu="+area+".html", false);
    xmlhttp.send();
    var xml=xmlhttp.responseXML;
    CreateIndexLevel2(xml,div);
}

/*
 * remove the local links
 */
function link(){
    var content = document.getElementById("content");	  
    var aLinks = content.getElementsByTagName("a"); 
    for (var i = 0; i<aLinks.length; i++){
        var href = aLinks[i].getAttribute("href");
        var t1=href.lastIndexOf(".html");
        var t2=href.lastIndexOf("http://", 0);
        if (t1!= -1 && t2==-1){
            aLinks[i].setAttribute("href", "#");
            aLinks[i].setAttribute("OnClick", "changeContent('"+href.toString()+"')");
        } else if (t2==-1){
            var file=href.substring (href.lastIndexOf("/", 0)+1);
            aLinks[i].setAttribute("href", "/data/"+file);
        }
        
    }
}
/*
 * load a new content from local link
 * @param link: the local link
 */
function changeContent(link){
    var oXHR = zXmlHttp.createRequest();
    oXHR.open("get", "Control/GetContentIndex.php?page="+link, true);
    oXHR.onreadystatechange = function () {
        if (oXHR.readyState == 4) {
            if (oXHR.status == 200 || oXHR.status == 304) {                         			
                var xml =oXHR.responseXML;
                xml = selectNodes(xml, "//ID");
                var id = getText(xml[0]);
                fillContent(id);
            } 
        }            
    };
    oXHR.send(null);
}

/*
 * Search in the content title
 * @param field: the value to search
 */
function search(field){
    var oXHR = zXmlHttp.createRequest();
    oXHR.open("get", "Control/SearchControl.php?request="+field, true);
    oXHR.onreadystatechange = function () {
        if (oXHR.readyState == 4) {
            if (oXHR.status == 200 || oXHR.status == 304) {                         			
                var xml = oXHR.responseXML;
                xml = selectNodes(xml, "//LINK");
                if (xml.length>0){
                    var html= "<center>Results of search:</center><table>"
                    for (var i=0; i<xml.length; i++){
                        html+="<tr><td>"+(i+1)+"</td><td><a href='#' onclick=(fillSearch("+xml[i].getAttribute('id')+"))>"+getText(xml[i])+"</a></td></tr>";
                    }
                    html+="</table>"

                } else {
                    var html="<center>Results of search:<br/>No Results</center>";
                    
                }
                result.innerHTML=html;
                result.removeAttribute("style");
                
            } 
        }            
    };
    oXHR.send(null);
}

/*
 * Show a result of the search
 * @param: the content id
 */
function fillSearch(id){
	  
    var content = document.getElementById("content");
    divContent.removeAttribute("style");
    content.innerHTML = "";
    var oXHR = zXmlHttp.createRequest();
    oXHR.open("get", "Control/GetContent.php?request=content&id="+id, true);
    oXHR.onreadystatechange = function () {
        if (oXHR.readyState == 4) {
            if (oXHR.status == 200 || oXHR.status == 304) {                         			
                content.innerHTML += oXHR.responseText;
                linkSearch();
            } 
        }            
    };
    oXHR.send(null);
	  
    var title = document.getElementById("title");
    var author = document.getElementById("author");
    var date = document.getElementById("date");

    title.innerHTML = "";
    author.innerHTML = "";
    date.innerHTML = "";
    var oXHR2 = zXmlHttp.createRequest();
    oXHR2.open("get", "Control/GetInfo.php?request=content&id="+id, true);
    oXHR2.onreadystatechange = function () {
        if (oXHR2.readyState == 4) {
            if (oXHR2.status == 200 || oXHR2.status == 304) {
                var aTitle = selectNodes(oXHR2.responseXML, "//TITLE");
                title.innerHTML = getText(aTitle[0]);
                var aAuthor = selectNodes(oXHR2.responseXML, "//AUTHOR");
                author.innerHTML +="<i>Author: </i><b>"+getText(aAuthor[0])+"</b>";
                var aDate = selectNodes(oXHR2.responseXML, "//DATE");
                date.innerHTML +="<i>Date: </i>"+getText(aDate[0]);
            } 
        }            
    };
    oXHR2.send(null);  	
    
}

/*
 * remove the local link in the search result
 */
function linkSearch(){
    var content = document.getElementById("content");	  
    var aLinks = content.getElementsByTagName("a"); 
    for (var i = 0; i<aLinks.length; i++){
        var href = aLinks[i].getAttribute("href");
        var t1=href.lastIndexOf(".html");
        var t2=href.lastIndexOf("http://", 0);
        if (t1!= -1 && t2==-1){
            aLinks[i].setAttribute("href", "#");
            aLinks[i].setAttribute("OnClick", "changeContentSearch('"+href.toString()+"')");
        }
        
    }
}
/*
 * show a new search result 
 */
function changeContentSearch(link){
    var oXHR = zXmlHttp.createRequest();
    oXHR.open("get", "Control/GetContentIndex.php?page="+link, true);
    oXHR.onreadystatechange = function () {
        if (oXHR.readyState == 4) {
            if (oXHR.status == 200 || oXHR.status == 304) {                         			
                var xml =oXHR.responseXML;
                xml = selectNodes(xml, "//ID");
                var id = getText(xml[0]);
                fillSearch(id);
            } 
        }            
    };
    oXHR.send(null);
}
