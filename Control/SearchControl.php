<?php
/**
 * returns search results in XML format 
 */
header("Content-Type: text/xml");

require 'db.php';

// connect to DB
$db = mysql_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD) or
        die('Unable to connect. Check your connection parameters.');
mysql_select_db(MYSQL_DB, $db) or die(mysql_error($db));

if (!isset($_GET['request'])) {
    return;
}
$request=$_GET['request'];
$sql = "SELECT id, title FROM content_page c where title like '%$request%'";
$result = mysql_query($sql, $db);
if (!$result)
    return;
$xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?><ROOT>";
while ($results = mysql_fetch_object($result)) {
    $id = $results->id;
    
    $title = $results->title;
    $title = htmlentities($title);
    $xml .="<LINK id ='$id'>$title</LINK>";
}

echo $xml."</ROOT>";
?>
