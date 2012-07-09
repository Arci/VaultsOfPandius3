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
        <script type="text/javascript" src="Script/sections.js"></script>
    </head>
    <body>       

        <?php include 'Banner.php' ?>

        <?php
        require 'Control/db.php';
        // connect to DB
        $db = mysql_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD) or
                die('Unable to connect. Check your connection parameters.');
        mysql_select_db(MYSQL_DB, $db) or die(mysql_error($db));
        
        $id = $_GET['id'];
        
        $sql="SELECT c.id , c.title, c.submit_date, u.name, c.text FROM content_page c, users u where c.author=u.id and c.id='$id'";
        $result = mysql_query($sql, $db);
        $result = mysql_fetch_object($result);
        ?>

        <div id="central">    
            <div class="shadowbox">	    
                <h2 id=title ><?php echo $result->title ?></h2>
                <div class="hr"></div>
                <div id=author><?php echo $result->name ?></div>
                <div id=date><?php echo $result->submit_date ?></div>
                <div class="hr"></div>
                <div id=content ><?php echo $result->text ?></div>
                <script type="text/javascript">
                    link();
                </script>
            </div>


        </div>
        <?php include 'Footer.php' ?>
    </body>
</html>