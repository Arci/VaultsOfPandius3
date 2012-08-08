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
        
        $sql="SELECT c.id , c.title, u.name, c.source, c.publish_date, c.submit_date, c.text FROM content_page c, content_page_author a, users u WHERE (((c.id='$id') AND (a.contentPage=c.id)) AND u.id=a.author)";
        $result = mysql_query($sql, $db);
		$author = $result;
		$result = mysql_fetch_object($result);
        ?>

        <div id="central">    
            <div class="shadowbox" style="height: 71%;">	    
                <h2 id=title ><?php echo $result->title ?></h2>
                <div class="hr"></div>
                <div id=author><i>Author: </i><b>
                    <?php
                        echo $result->name;
                        while ($row = mysql_fetch_object($author)){
                            echo ", " . $row->name;
                        }
                    ?></b>
		</div>
                <div id=source>
                    <?php
                        if($result->source != null){
                            echo "<i>Source: </i>".$result->source;
                        }
                    ?>
                </div>
                <div id=date><i>Date: </i>
                    <?php
                        if($result->publish_date != null){
                            echo $result->publish_date." (publish)";
                        }else{
                            echo $result->submit_date." (submit)";
                        }
                    ?>
                </div>
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