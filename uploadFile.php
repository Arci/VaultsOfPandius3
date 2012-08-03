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
        <script type="text/javascript" src="Script/widgEditor.js"></script>    
        <script type="text/javascript" src="Script/zxml.js"></script>
        <script type="text/javascript" src="Script/Xml.js"></script>
        <script type="text/javascript" src="Script/Post.js"></script>
        <script type="text/javascript" src="Script/Logout2.js"></script>
        <script type="text/javascript" src="Script/Form.js"></script>
        <script type="text/javascript" src="Script/user.js"></script>
        <style>
            #logreg li#liUpload a{
                color:white;
            }
            
            #fUpload {
                padding-top:15px;
                padding-left:20px;
                color: #666;
                font-weight: normal;
                font-size: 0.9em;
                font-family: sans-serif;
            }
        </style>
    </head>
    <body onload="hideAll(<?php echo $_SESSION['id'] ?>)">       
    <?php include 'Banner.php' ?>
    <?php include "upload.php"; ?>

    <div id="central">    
        <div style="float: left; width: 40%">
            <div id="divMainIndex" class="shadowbox">
                <h3 id=h2MainIndex>Upload</h3>
                <div class="hr"></div>
                <div id="fUpload">
                <?php if(count($_FILES) > 0){
                    if($_FILES["upfile"]["name"] !=null){
                        $name = Upload::uploadPhoto($_FILES["upfile"]["name"],$_FILES["upfile"]["tmp_name"],$_FILES["upfile"]["type"]);
                        echo "Upload complete!!<br/><br/>The name that you can use to retrive your file is: <b>$name</b><br/><br/>";
                    }else{
                        Upload::displayUploadForm("Inserisci un file");
                    }
                }else{
                    Upload::displayUploadForm();
                } ?>
                </div>
            </div>
        </div>
    </div>
    <?php include 'Footer.php' ?> 
    </body>
</html>