
<?php
/*
 * the content controller
 */    
    header("Content-Type: text/xml");
    require 'db.php';
    //connect to DB
    $db = mysql_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD) or
	die ('Unable to connect. Check your connection parameters.');
    mysql_select_db(MYSQL_DB, $db) or die(mysql_error($db));
    //customer ID
    $sID = $_GET["id"];	      
    //variable
    $sResponse = '<CONTENT>';
    $sql = 'SELECT
	    title, name, submit_date, text
	FROM
	    content_page JOIN users ON author = users.id
	WHERE
	    content_page.id="'.$sID.'"';
    $result = mysql_query($sql, $db);
    while ($row = mysql_fetch_array($result)) {	      
	$sResponse .= '<TITLE>'.$row['title'].'</TITLE>';
	$sResponse .= '<AUTHOR>'.$row['name'].'</AUTHOR>';
	$sResponse .= '<DATE>'.$row['submit_date'].'</DATE>';
	$sResponse .= '<TEXT>"'.$row['text'].'"</TEXT>';
    }            
    mysql_free_result($result);          
    
    $sResponse .= '</CONTENT>';
    
    echo $sResponse;
?>