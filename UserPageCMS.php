<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
    http://www.w3.org/TR/html4/loose.dtd>
    <?php
    session_start();
    if (!isset($_SESSION['access_level']) || !$_SESSION['access_level'] == 3)
        return;
    ?>
<html>
    <head>
        <title>INDEX CMS</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" href="Style/base.css"/>
        <style type="text/css" media="all">@import "Style/style.css";</style>
        <link rel="stylesheet" href="Style/user.css"/>
        <script type="text/javascript" src="Script/widgEditor.js"></script>    
        <script type="text/javascript" src="Script/zxml.js"></script>
        <script type="text/javascript" src="Script/Xml.js"></script>
        <script type="text/javascript" src="Script/Post.js"></script>
        <script type="text/javascript" src="Script/Logout2.js"></script>
        <script type="text/javascript" src="Script/Form.js"></script>
        <script type="text/javascript" src="Script/user.js"></script>
    </head>
    <body onload="hideAll(<?php echo $_SESSION['id'] ?>)">       

        <?php include 'Banner.php' ?>  
        <div id="central">    
            <div style="float: left; width: 30%">
                <div id="divMainIndex" class="shadowbox">
                    <h3 id=h2MainIndex>Menu</h3>
                    <div class="hr"></div>
                    <ul>
                        <li><a id="aNew" href="#" onclick="show('new')">Create new content</a></li>
                        <li><a id="aEdit" href="#" onclick="show('edit')">Edit content</a></li>
                        <li><a id="aModify" href="#" onclick="show('modify')">Modify your info</a></li>
                        <li><a id="aPending" href="#" onclick="show('pending')">View pending contents</a></li>
                        <li><a id="aPublished" href="#" onclick="show('published')">View published contents</a></li>	    
                    </ul>
                </div>
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
            <div id=divGeneral>

                <!-- ************************************************ SEZIONE NEW CONTENT ************************************************************** !-->
                <div id=storeDivContent>
                    <div id="divContent" class="shadowbox">	
                        <h2>Editor</h2>
                        <div class="hr"></div>
                        <div id="content">
                            <form method="post" id="contentForm" action="SetContent.php" onsubmit="submitContent(); return false">
                                <div id="divTitle">
                                    <h3 id="h3title">Title</h3>
                                    <div class="hr"></div>
                                    <input type="text" id="title" name="title" ><span id="span"></span><input id="submitId" type="submit" value="Submit"/>
                                </div>
                                <div id="divTextArea">
                                    <h3>Content</h3>
                                    <div class="hr"></div>
                                    <div id="divHidden">
                                    </div>
                                </div>
                                <input id="inputId" type="hidden" name="id" value=""/>
                                <input id="linkId" type="hidden" name="idIndex" value=""/>
                                <input id="linkName" type="hidden" name="linkName" value=""/>
                                <input id= "request" type="hidden" name="request" value=""/>
                            </form>
                        </div>	
                    </div>
                </div>	

                <!-- ************************************************ SEZIONE MODIFY INFO ************************************************************** !-->
                <div id=storeDivModifyInfo>
                    <div id="divModify" class="shadowbox">
                        <h2 id=h2modify>Modify Information</h2>
                        <div class="hr"></div>
                        <div id="modify">
                            <form method="post" id="requestForm" action="Control/UserControl.php" onsubmit="sendRequest(); return false">		  
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
                                <input id="submitModify" type="submit" value="Modify" style="padding:4px 20px" /><span id="spanModify"></span>
                                <input type="hidden" id="AuthorId" name="id" value=""/> 								<!-- VA MESSO l'id DINAMICO -->
                                <input type="hidden" name="request" value="modify"/>			    
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
                        <div id=dateView><i>Date:</i></div>
                        <div class="hr"></div>
                        <div id=contentView>
                        </div>
                    </div>
                </div>	
            </div>   

        </div>
    </div>
    <?php include 'Footer.php' ?> 
</body>
</html>