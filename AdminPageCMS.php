<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
    http://www.w3.org/TR/html4/loose.dtd>

<?php
session_start();
if (!isset($_SESSION['access_level']) || !$_SESSION['access_level'] == 2)
    return;
?>
<html>
    <head>
        <title>INDEX CMS</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" href="Style/base.css"/>
        <style type="text/css" media="all">@import "Style/style.css";</style>
        <style>

            #logreg li#liAdminPage a{
                color:white;
            }

            #fakeDiv {
                float:right;
                width:60%;
                margin:10px;
                margin-bottom:0px;
                margin-top:14px;
                padding:0px 20px;
            }

            #divSecondIndex ul {  
                list-style-type:none;
                padding-left:7%;		  
            }

            #divSecondIndex a {  
                text-decoration:none;
                color: #666;
                font-weight: normal;
                font-size: 0.9em;
                font-family:sans-serif;
            }

            #divSecondIndex a:hover { 
                text-decoration: underline;
                color: #666;
            }

            #divIndex {
                margin-top: 20px;
            }

            #divView {   
                float:right;
                width:60%;
            }

            #contentView {
                overflow-y: scroll;

                padding-left:10px;
                padding-right:10px;
            }

            #titleView {
                margin-bottom: 0px;
            }

            #authorView {
                padding-top:10px;
                padding-left:10px;
                padding-right:10px;
                color: #666;
                font-size: 0.9em;
                font-family:sans-serif;
            }

            #dateView {
                padding-left:10px;
                padding-right:10px;
                color: #666;
                font-size: 0.9em;
                font-family:sans-serif;
            }

            #divUsers {
                margin-top:20px;
                max-height: 500px;
            }

            #divUsers ul {
                list-style-type:none;
                padding-left:7%;  
            }

            #divUsers a {
                text-decoration:none;
                color: #666;
                font-weight: normal;
                font-size: 0.9em;
                font-family:sans-serif;
            } 	  

            .divButton {
                float:left; 
                height:40px; 
                width:40px; 
                padding-top:300px; 
                padding-bottom:300px; 
                padding-left:50px; 
                padding-right:50px;
            }

            #h3content {
                margin-top: 0px;
                padding: 0px;
            }

            #h3location {
                margin-top: 0px;
                padding: 0px;
            }

            #divContent {   
                float:right;
                width:60%;

            }


            #divModify {   
                float:right;
                width:60%;

            }

            #divSendMail {
                margin-top:20px;
                float:right;
                width:70%;

            }	  	  

            #modify {
                margin-left: 10px;
                margin-right:10px;
            }

            #modify input {
                margin-left: 7%;
                margin-top: 2%;
                margin-bottom: 2%;
                padding:4px;
            }

            #modify select {
                margin-left: 7%;
                margin-top: 2%;
                margin-bottom: 2%;
                padding:4px;
            }



            #modify span {
                margin-left: 1%;
            }

            #modify img {	    
                margin-bottom:-8px;
            }

            #submitModify {	    
                padding:4px 20px;
            }

            #submitId {
                margin-right: 7%;
                margin-top: 2%;
                padding:4px 20px;
                float:right;
            }

            .submitApprove {
                width:100px;
                height:30px;
            }


            #sendMail {
                margin-left: 10px;
                margin-right:10px;
            }	

            #sendMail h3 {
                padding-left:0px;
            }

            #sendMail table {
                margin-top: 2%;
                margin-left: 7%;	    
            }

            #sendMail input {
                margin-left: 3%;
                margin-top: 2%;	    
                padding:4px;
            } 

            #sendMail textarea {
                margin-top: 2%;
                margin-left: 7%;
                width: 85%;
                /*height: 50%;*/
            }

            #sendMail #submitMail {
                margin-left:7%;
                padding:4px 20px;
            }


            #divHidden {
                background-color: white;
                margin-left: 7%;
                margin-right: 7%;
                margin-top: 2%;
            }

            #divLocation{
                background-color: silver;
                padding-top: 10px;
                padding-right: 10px;
                padding-left: 10px;
                padding-bottom: 1px;

            }

            #divStatus {   
                float:right;
                width: 78%;

                border-right: thick gray solid;
                border-left: thick gray solid;
                border-bottom: thick gray solid;
                margin-top: 40px;
            }

            #divContentView {

                float:right;
                width:66%;

                border-right: thick silver solid;
                border-left: thick silver solid;
                border-bottom: thick silver solid;
                margin-right: 10px;
            }


            #content {
                margin-left: 10px;
                margin-right:10px;
            }



            #status {
                margin-left: 10px;
                margin-right:10px;
            }

            #modify {
                margin-left: 10px;
                margin-right:10px;
            }

            #title {
                margin-left: 7%;
                margin-top: 2%;
                padding:4px;
                width: 85%;
            }

            #titleView {
                margin-bottom: 0px;
            }

            #ulLocation {
                overflow-y: scroll;
                height: 30%;
                background-color: gray;
                padding-right: 30px;
                padding-left: 30px;
                padding-top:10px;
                padding-bottom:10px;	    
                background-color:white;	    
            }

            #ulUsers {
                overflow-y: scroll;
                max-height: 400px;
            }

            #ulPending {
                overflow-y: scroll;
                max-height: 82%;	    
            }

            #titleIndex {
                background-color:#EFEFEF;
            }

            #submit {
                margin-right: 7%;
                margin-top: 2%;
                padding:4px 20px;
                float:right;
            }

            #imgMail {	    
                float:right;
                padding:0px;
                border:none;
                height:40%;
                margin-right:0px;
            }

            #img {	    
                float:right;
                padding:0px;
                border:none;
                height:4%;
                margin-right:0px;
            }

            .userImg{
                height:16px;
            }

            .img {
                height:16px;
            }

            #hrApproval {
                margin-top:20px;
            }

            #submitModify {	    
                padding:4px 20px;
            }

            #subAppr {
                margin-top:10px;
            }


        </style>
        <script type="text/javascript" src="Script/widgEditor.js"></script>    
        <script type="text/javascript" src="Script/zxml.js"></script>
        <script type="text/javascript" src="Script/Xml.js"></script>
        <script type="text/javascript" src="Script/Post.js"></script>
        <script type="text/javascript" src="Script/Logout2.js"></script>
        <script type="text/javascript" src="Script/Form.js"></script>
        <script type="text/javascript" src="Script/admin.js"></script>
    </head>
    <body onload="hideAll()">       
        <?php include 'Banner.php' ?>
        <div id="central">
            <div style="float: left; width: 30%"> 
                <div id="divMainIndex" class="shadowbox">
                    <h3 id=h2MainIndex>Menu</h3>
                    <div class="hr"></div>
                    <ul>
                        <li><a id="aUsers" href="#" onclick="show('users')">Users</a></li>
                        <li><a id="aContents" href="#" onclick="show('contents')">Contents</a></li>
                    </ul>
                </div>    


                <div id="divSide">
                    <div id="storeDivSecondIndex">
                        <div id="divSecondIndex" class="shadowbox">
                            <h3 id=h2SecondIndex>Content Options</h3>
                            <div class="hr"></div>
                            <ul>
                                <li><a id="aApproval" href="#" onclick="show('approval')">Approve</a></li>
                                <li><a id="aEdit" href="#" onclick="show('edit')">Edit</a></li>     
                            </ul>
                        </div>
                    </div>
                    <div id="storeDivUsers">
                        <div id="divUsers" class="shadowbox">
                            <h3 id=h2users>Users</h3>
                            <div class="hr"></div>
                            <ul id=ulUsers>
                                <li id="level1"><img class="userImg" src="images/people-icon.png"/><a href="#" onclick="expandLevel('1')">    Simple users</a></li>
                                <li id="level2"><img class="userImg" src="images/people-icon.png"/><a href="#" onclick="expandLevel('2')">    Old users</a></li>
                                <li id="level3"><img class="userImg" src="images/people-icon.png"/><a href="#" onclick="expandLevel('3')">    Administrators</a></li>
                            </ul>    
                        </div>
                    </div>
                    <div id="storeDivIndex">
                        <div id="divIndex" class="shadowbox">
                            <h3 id="h2index">Index</h3>
                            <div class="hr"></div>
                            <ul id="ulPending">
                                <li id="i1"><img class="img" src="images/folder_grey.png"/><a href="#" onclick="GetIndexLevel1('stories',i1)">    Stories</a></li>
                                <li id="i2"><img class="img" src="images/folder_grey.png"/><a href="#" onclick="GetIndexLevel1('atlas',i2)">    Atlas</a></li>
                                <li id="i3"><img class="img" src="images/folder_grey.png"/><a href="#" onclick="GetIndexLevel1('resource',i3)">    Resources</a></li>
                                <li id="i4"><img class="img" src="images/folder_grey.png"/><a href="#" onclick="GetIndexLevel1('adv_camp',i4)">    Adventures</a></li>	  
                            </ul>	
                        </div>
                    </div>
                </div>
            </div>
            <div id=divGeneral>

                <!-- *************************************************** EDIT CONTENT ***************************************************************** !-->
                <div id=storeDivContent>
                    <div id="divContent" class="shadowbox">	
                        <h2>Editor</h2>
                        <div class="hr"></div>
                        <div id="content">
                            <form method="post" id="contentForm" action="SetContent.php" onsubmit="submitContent(); return false">
                                <div id="divTitle">
                                    <h3 id="h3title">Title</h3>
                                    <div class="hr"></div>
                                    <input type="text" id="title" name="title"/><span id="span"></span><input id="submitId" type="submit" value="Submit"/>
                                </div>
                                <div id="divTextArea">
                                    <h3>Content</h3>
                                    <div class="hr"></div>
                                    <div id="divHidden">
                                    </div>
                                </div>
                                <input id="inputId" type="hidden" name="id" value=""/>			    
                                <input id= "request" type="hidden" name="request" value="modify"/>
                            </form>
                        </div>	
                    </div>
                </div>	

                <!-- ************************************************ SEZIONE MODIFY INFO ************************************************************** !-->	
                <div id=storeDivModifyInfo>
                    <div id="divModify" class="shadowbox" style="visibility:hidden">
                        <h2 id=h2modify>Modify User Profile</h2>
                        <div class="hr"></div>
                        <div id="modify">
                            <form id="modifyForm" method="post" action="Control/UserControl.php" onsubmit="sendRequest(); return false">		  
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
                                <div id="divLevel">
                                    <h3>Role</h3>
                                    <div class="hr"></div>
                                    <select id="level" name="level">
                                        <option id="option1" value="1">Simple User</option>
                                        <option id="option2" value="2">Old User</option>
                                        <option id="option3" value="3">Administrator</option>
                                    </select>
                                </div>
                                <div id="divPassword">
                                    <h3>Reset Password?</h3>
                                    <div class="hr"></div>
                                    <!--<input disabled type="password" id="passw" name="passw" onchange="validatePassword()" size=50/><span id="passwImg"></span><span id="passwMex"></span>-->
                                    <input type="checkbox" id="passw" name="passw"/>
                                </div>
                                <input id="submitModify" type="submit" value="Modify" style="padding:4px 20px" /><span id="spanModify"></span>
                                <input id="hiddenId" type="hidden" name="id" value=""/>
                                <input type="hidden" name="request" value="modifyadmin"/>
                            </form>
                        </div>	    
                    </div>
                </div>	

                <!-- **************************************************** SEZIONE VIEW ***************************************************************** !-->
                <div id="storeDivView">
                    <div id="divView" class="shadowbox" style="visibility:hidden">	    	    
                        <a href="#"><img id="img" src="images/zoom1.jpeg" class="shadowbox" onclick="zoomInContent()"/></a>		    
                        <h2 id=titleView>Content</h2>
                        <div class="hr"></div>
                        <div id=authorView><i>Author:</i></div>
                        <div id=dateView><i>Date:</i></div>
                        <div id=sourceView><i>Source:</i></div>
                        <div class="hr"></div>
                        <div id=contentView>
                        </div>
                        <div id="hrApproval" class="hr"></div>
                        <div id="divApproval">
                            <center><form id="approveForm" method="post" action="SetContent.php" onsubmit="sendApprove(); return false"><input id="subAppr" class="submitApprove" type="submit" value="Approve" /><input type="hidden" name="request" value="approve"/><input id="approveId" type="hidden" name="id" value=""/></form><form id="deleteForm" method="post" action="SetContent.php" onsubmit="sendDelete(); return false"><input class="submitApprove" type="submit" value="Delete" /><input type="hidden" name="request" value="delete"/><input id="deleteId" type="hidden" name="id" value=""/><center>			
                                        </div>
                                        </div>
                                        </div>	
                                        </div>
                                        </div>
                                        <div id="divBottom">
                                            <div id=storeDivMail>
                                                <div id="divSendMail" class="shadowbox" style="visibility:hidden">
                                                    <a href="#"><img id="imgMail" src="images/zoom1.jpeg" class="shadowbox" onclick="showMail()"/></a>
                                                    <h2 id=h2mail>Send E-Mail</h2>
                                                    <div class="hr"></div>
                                                    <div id="sendMail">
                                                        <form method="post" action="Control/MailControl.php" onsubmit="sendMail(); return false">		  
                                                            <table>
                                                                <tr>
                                                                    <td><h3>To: </h3></td><td><input type="text" id="address" name="address" size=50 readonly/></td>								
                                                                </tr>
                                                                <tr>
                                                                    <td><h3>Subject: </h3></td><td><input type="text" id="subject" name="subject" size=50/></td>
                                                                </tr>			      
                                                            </table>
                                                            <textarea id="textMail" name="textMail" ></textarea><td>
                                                                <input id="submitMail" type="submit" value="Send" />	    
                                                        </form>		      		      
                                                    </div>
                                                </div>
                                            </div>
                                        </div> 
                                        </div>
                                        <?php include 'Footer.php' ?>
                                        </body>
                                        </html>


