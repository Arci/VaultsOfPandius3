<?php
/**
 * The user controller 
 */
header("Content-Type: text/xml");

require 'db.php';

$db = mysql_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD) or
        die('Unable to connect. Check your connection parameters.');
mysql_select_db(MYSQL_DB, $db) or die(mysql_error($db));

if (!isset($_GET['menu']))
    return;

$xml = '<?xml version="1.0" encoding="utf-8"?><MENU>';
if ($_GET['menu'] == 'home.html') {
    $sql = "SELECT id, title from index_page where menu='1'";
} else {

    $menu = $_GET['menu'];

    $sql = "SELECT id from index_page where href='$menu'";
    $result = mysql_query($sql, $db);
    $id = mysql_fetch_object($result);
    if (isset($id->id))
        $id = $id->id;
    else
        return;

    

    $sql = "SELECT id_target_index_page as id, link_name as title from index_2_content where id_start_index_page='$id'";
}
$result = mysql_query($sql, $db);
//$element = mysql_fetch_object($result);
while ($element = mysql_fetch_object($result)) {
    $xml.="<ELEMENT id=\"$element->id\">$element->title</ELEMENT>";
}
$xml.="</MENU>";

echo $xml;
?>
