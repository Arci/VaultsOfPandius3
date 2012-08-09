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
        
        $user = $_GET['user'];
        
        
        $sql = "SELECT id, name FROM users where email like '%$user%'";
        $user = mysql_query($sql, $db);
        $user = mysql_fetch_object($user);
        $sql="SELECT title, id  FROM content_page, content_page_author WHERE contentPage=id AND author = $user->id";
        $results = mysql_query($sql, $db);
        //$result = mysql_fetch_object($results);
        ?>

        <div id="central">    
            <div class="shadowbox">
                <h2>Texts by the author <?php echo $user->name?></h2><br/>
                <table>
                    <?php while ($result = mysql_fetch_object($results)) {
                                echo "<tr><td><a href=/Content.php?id=$result->id>$result->title</a></td></tr>";
                            }?>
                    </table>
            </div>


        </div>
        <?php include 'Footer.php' ?>
    </body>
</html>