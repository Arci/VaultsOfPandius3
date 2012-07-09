<?php
/**
 * returns the last n approved content 
 */
if (!isset($_GET['n']))
    return;

header("Content-Type: text/xml");

require 'db.php';

$db = mysql_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD) or
        die('Unable to connect. Check your connection parameters.');
mysql_select_db(MYSQL_DB, $db) or die(mysql_error($db));

$xml = '<?xml version="1.0" encoding="utf-8"?><ROOT>';

$sql = "SELECT c.`id`, c.`title` FROM content_page c
WHERE c.`is_published`=1
ORDER BY c.`submit_date` desc
Limit 0," . $_GET['n'];

$result = mysql_query($sql, $db);
while ($link = mysql_fetch_object($result)){
    $xml.="<LINK id='$link->id'>$link->title</LINK>";
}


$xml .= "</ROOT>";
echo $xml;
?>
