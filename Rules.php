<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
http://www.w3.org/TR/html4/loose.dtd>
<?php session_start(); ?>
<html>
    <head>
        <title>INDEX CMS</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" href="Style/base.css"/>
        <link rel="stylesheet" href="Style/contenent.css"/>

        <script type="text/javascript" src="Script/zxml.js"></script>
        <script type="text/javascript" src="Script/Xml.js"></script>
        <script type="text/javascript" src="Script/Login.js"></script>
        <script type="text/javascript" src="Script/Post.js"></script>
        <script type="text/javascript" src="Script/contenent.js"></script>
        <script type="text/javascript" src="Script/sections.js"></script>

    </head>
    <body onload="GetIndexLevel1('rules','menu1')">           

        <?php include 'Banner.php' ?>
        <div id="central">    
            <div style="float: left; width: 30%;">
                <div id="divMainIndex" class="shadowbox">
                    <h3 id=section>Section</h3>
                    <div class="hr"></div>
                    <div id="menu1"></div>
                </div>

                <div id="divIndex" class="shadowbox" style="visibility:hidden">
                    <h3>Index</h3> 
                    <div class="hr"></div>
                </div>	  
            </div>
            <div id="divContent" class="shadowbox" style="visibility: hidden">	    
                <a href="#"><img id="img" src="images/zoom1.jpeg" class="shadowbox" onclick="zoomInContent()"/></a>
                <a href="#"><img id="imgRight" style="visibility:hidden" src="images/arrow_right.png" class="shadowbox" onclick="forwardPage()"/></a>
                <a href="#"><img id="imgLeft" style="visibility:hidden" src="images/arrow_left.png" class="shadowbox" onclick="backPage()"/></a>
                <h2 id=title >Content</h2>
                <div class="hr"></div>
                <div id=author></div>
                <div id=source></div>
                <div id=date></div>
                <div class="hr"></div>
                <div id=content ></div>
            </div>


        </div>
        <?php include 'Footer.php' ?>
    </body>
</html>