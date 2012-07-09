<?php
    /*
     * return the text content xml
     */
    header("Content-Type: text/plain");
    require 'db.php';
    // connect to DB
    $db = mysql_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD) or
	die ('Unable to connect. Check your connection parameters.');
    mysql_select_db(MYSQL_DB, $db) or die(mysql_error($db));

    if (isset($_GET['request'])) {
      
	switch ($_GET['request']) {
	    
	case 'index':
	    //customer ID
	    $sID = $_GET["id"];	      
	    $sql = 'SELECT
		    text
		FROM
		    index_page
		WHERE
		    id="'.$sID.'"';
	    $result = mysql_query($sql, $db);	    
	    while ($row = mysql_fetch_array($result)) {
		$resultQuery = $row['text'];		
	    }            
	    mysql_free_result($result);	  
	    break;
    
	case 'content':
	    //customer ID
	    $sID = $_GET["id"];	      
	    $sql = 'SELECT
		    text
		FROM
		    content_page
		WHERE
		    id="'.$sID.'"';
	    $result = mysql_query($sql, $db);	    
	    while ($row = mysql_fetch_array($result)) {
		$resultQuery = $row['text'];
		
	    }            
	    mysql_free_result($result);
	    break;
	    
	case 'link':
	    $sRef = $_GET["ref"];	      
	    $sql = 'SELECT
		    text
		FROM
		    content_page		           
		WHERE
		    href="'.$sRef.'"
	    UNION
		SELECT
		    text
		FROM           
		    index_page
		WHERE
		    href="'.$sRef.'"';
	    $result = mysql_query($sql, $db);	    
	    while ($row = mysql_fetch_array($result)) {
		$resultQuery = $row['text'];
		
	    }            
	    mysql_free_result($result);
	    break;
	
	}
	
    } 
	    
    echo $resultQuery; 
?>