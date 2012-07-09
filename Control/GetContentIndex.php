<?php
/*
 * given the link to a page returns its id
 */
header("Content-Type: text/xml");

require 'db.php';

// connect to DB
$db = mysql_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD) or
        die('Unable to connect. Check your connection parameters.');
mysql_select_db(MYSQL_DB, $db) or die(mysql_error($db));

if (!isset($_GET['page'])) {
    return;
}
$sql = "Select id from content_page where href='" . $_GET['page']."'";
$result = mysql_query($sql, $db);
if ($result = mysql_fetch_object($result)) {
    $id = $result->id;
}
$xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?><ID>$id</ID>";
echo $xml;
?>
