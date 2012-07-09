/*
 * Get the last n content
 * @param n: In number of content you want to have
 */
function getLast(n){
    var oXHR = zXmlHttp.createRequest();
    oXHR.open("get", "Control/GetLast.php?n="+n, true);
    oXHR.onreadystatechange = function () {
        if (oXHR.readyState == 4) {
            if (oXHR.status == 200 || oXHR.status == 304) {                         			
                var xml = oXHR.responseXML;
                var ul = document.createElement('ul');
                
                xml = selectNodes(xml, "//LINK");
                for (var i=0; i<xml.length; i++){
                    var li = document.createElement('li');
                    li.innerHTML = "<a href=Content.php?id="+xml[i].getAttribute('id')+">"+getText(xml[i])+"</a>";
                    ul.appendChild(li);
                }
                last.appendChild(ul);
            } 
        }            
    };
    oXHR.send(null);
}

