<?php
    
/*
 * given the content id returns the its info
 */

    header("Content-Type: text/xml");
    require 'db.php';
    // connect to DB
    $db = mysql_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD) or
	die ('Unable to connect. Check your connection parameters.');
    mysql_select_db(MYSQL_DB, $db) or die(mysql_error($db));
   
    $sResponse = '<INFO>';
   
     if (isset($_GET['request'])) {
      
	switch ($_GET['request']) {
	    
	case 'index':
	    //customer ID
	    $sID = $_GET["id"];	       
	    $sql = 'SELECT
		    author
		FROM
		    index_page 
		WHERE
		    index_page.id="'.$sID.'"';
	    $result = mysql_query($sql, $db);
	    while ($row = mysql_fetch_array($result)) {
		$author = $row['author'];
	    }
	    
	    if ($author != null){
		$sql = 'SELECT
		    title, name
		  FROM
		      index_page JOIN users ON author = users.id
		  WHERE
		      index_page.id="'.$sID.'"';
		$result = mysql_query($sql, $db);
		while ($row = mysql_fetch_array($result)) {	      		
		    $sResponse .= '<AUTHOR>'.$row['name'].'</AUTHOR>';
		    $sResponse .= '<TITLE>'.$row['title'].'</TITLE>';			
		}
	    } else {
		$sql = 'SELECT
		    title
		  FROM
		      index_page
		  WHERE
		      index_page.id="'.$sID.'"';
		$result = mysql_query($sql, $db);
		while ($row = mysql_fetch_array($result)) {	      		
		    $sResponse .= '<AUTHOR>-</AUTHOR>';
		    $sResponse .= '<TITLE>'.$row['title'].'</TITLE>';
		}
	    }
	    break;
	  
	case 'content':
	    //customer ID
	    $sID = $_GET["id"];	      	    
	    $sql = 'SELECT
		    title, publish_date, submit_date, source, name
		FROM
		    content_page, content_page_author, users
		WHERE
		  content_page.id=content_page_author.contentPage AND
		  content_page_author.author = users.id AND
		    content_page.id="'.$sID.'"';
	    $result = mysql_query($sql, $db);
	    while ($row = mysql_fetch_array($result)) {
	       $sResponse .= '<TITLE>'.$row['title'].'</TITLE>';
	       $sResponse .= '<AUTHOR>'.$row['name'].'</AUTHOR>';
	       if($row['source'] != null){
	         $sResponse .= '<SOURCE>'.$row['source'].'</SOURCE>';
	       }else{
	         $sResponse .= '<SOURCE>Unknown</SOURCE>';
	       }
	       if($row['publish_date'] != null){
		  $sResponse .= '<DATE>'.$row['publish_date'].' (publish)</DATE>';
	       }else{
		  $sResponse .= '<DATE>'.$row['submit_date'].' (submit)</DATE>';
	       }
	    }            
	    mysql_free_result($result); 
	    break;
	    
	case 'link':
	    //customer ID
	    $sRef = $_GET["ref"];	      	    
	    $sql = 'SELECT
		    title, name, submit_date
		FROM
		    content_page JOIN users ON author = users.id
		WHERE
		    content_page.href="'.$sRef.'"';
	    $result = mysql_query($sql, $db);
	    
	    if ($row = mysql_fetch_array($result)) {	      
		$sResponse .= '<TITLE>'.$row['title'].'</TITLE>';
		$sResponse .= '<AUTHOR>'.$row['name'].'</AUTHOR>';
		$sResponse .= '<DATE>'.$row['submit_date'].'</DATE>';
	    } else {
		    mysql_free_result($result); 
		    $sql = 'SELECT
			    author
			FROM
			    index_page 
			WHERE
			    index_page.href="'.$sRef.'"';
		    $result = mysql_query($sql, $db);
		    while ($row = mysql_fetch_array($result)) {
			$author = $row['author'];
		    }
		    
		    if ($author != null){
			$sql = 'SELECT
			    title, name
			  FROM
			      index_page JOIN users ON author = users.id
			  WHERE
			      index_page.href="'.$sRef.'"';
			$result = mysql_query($sql, $db);
			while ($row = mysql_fetch_array($result)) {	      		
			    $sResponse .= '<AUTHOR>'.$row['name'].'</AUTHOR>';
			    $sResponse .= '<TITLE>'.$row['title'].'</TITLE>';			
			}
		    } else {
			$sql = 'SELECT
			    title
			  FROM
			      index_page
			  WHERE
			      index_page.href="'.$sRef.'"';
			$result = mysql_query($sql, $db);
			while ($row = mysql_fetch_array($result)) {	      		
			    $sResponse .= '<AUTHOR>-</AUTHOR>';
			    $sResponse .= '<TITLE>'.$row['title'].'</TITLE>';
			}
		    }
	    }            
	    break;	  
	}
	
    } 
	   
    $sResponse .= '</INFO>';    
    echo $sResponse;

?>