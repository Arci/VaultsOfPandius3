<?php 

require 'db.php';

define('PAGE','authors.html');

$db = mysql_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD) or
    die ('Unable to connect. Check your connection parameters.');

mysql_select_db(MYSQL_DB, $db) or die(mysql_error($db));

//estrazione contenuto dalla pagina
$domHTML = new DomDocument();
// open if file exists
if(!$domHTML->loadHTMLFile('./pandius.com/'.PAGE)){
  echo "Cannot open page ".PAGE;
  return;
}

$xpath = new DomXPath($domHTML);
$nodes = $xpath->query("//a[@href]", $domHTML->documentElement);
$password = "000000";

for ($i=9; $i<$nodes->length; $i++) {
    $singleNode = $nodes->item($i);
    $name = $singleNode->nodeValue;
    $name = trim($name);
    echo $name.'<br/>';  
    $email = str_replace(" ", "",$name.'@pandius.com');
    $password++;
    $access_level = 2;
    
    $sql = 'INSERT IGNORE INTO users 
	    (email, password, name, access_level)
	VALUES
	    ("'.mysql_real_escape_string($email, $db).'",
	    PASSWORD("'.$password.'"),
	    "'.mysql_real_escape_string($name, $db).'",
	    "'.$access_level.'")';
    mysql_query($sql, $db) or die(mysql_error($db));

}

echo "success"
?>
