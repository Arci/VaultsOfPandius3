<?php

header("Content-Type: text/xml");

require 'db.php';

$db = mysql_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD) or
        die('Unable to connect. Check your connection parameters.');
mysql_select_db(MYSQL_DB, $db) or die(mysql_error($db));

if (!isset($_GET['menu']))
    return;

$menu = $_GET['menu'];

$sql = "SELECT id, title from index_page where href='$menu'";
$result = mysql_query($sql, $db);
$id = mysql_fetch_object($result);
if (isset($id->id)) {
    $xml = '<?xml version="1.0" encoding="utf-8"?><MENU title="'.$id->title.'">' . $id->id . "</MENU>";
    echo $xml;
}
return;
?>
