<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
http://www.w3.org/TR/html4/loose.dtd>
<?php session_start(); ?>
<html>
    <head>
        <title>Search</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" href="Style/base.css"/>

        <script type="text/javascript" src="Script/zxml.js"></script>
        <script type="text/javascript" src="Script/Xml.js"></script>
        <script type="text/javascript" src="Script/Login.js"></script>
        <script type="text/javascript" src="Script/Post.js"></script>
        <style>
            #result{
                padding: 20px;
                overflow:auto;
                max-height: 200px;
            }
        </style>

        <script type="text/javascript" src="Script/sections.js"></script>
    </head>
    <body>       

        <?php include 'Banner.php' ?>
        <div id="central">    
            <div>
                <center>
                    <form>
                        <label for="field">Insert text</label><input id="field" type="text">
                        <input id="button" type="button" value="Search" onclick="search(field.value)">
                    </form>
                </center>
            </div>
            <div id="result" class="shadowbox" style="visibility: hidden ">
                
            </div>
            <div id="divContent" class="shadowbox" style="visibility: hidden">	    
                <h2 id=title >Content</h2>
                <div class="hr"></div>
                <div id=author></div>
                <div id=date></div>
                <div class="hr"></div>
                <div id=content ></div>
            </div>
        </div>
        <?php include 'Footer.php' ?>
    </body>
</html>