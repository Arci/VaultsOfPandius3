<?php
    session_start();
    header("Content-Type: text/plain");
    require 'Control/db.php';
    require 'Control/Mail.php';
    // connect to DB
    $db = mysql_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD) or
	die ('Unable to connect. Check your connection parameters.');
    mysql_select_db(MYSQL_DB, $db) or die(mysql_error($db));

     if (isset($_POST['request']) and isset($_SESSION['access_level'])) {
	
	switch ($_POST['request']) {
	    
	case 'modify':
	    if ( isset($_POST['id']) and isset($_POST['title']) and isset($_POST['textHtml']) and $_SESSION['access_level'] >= 2){
		$sID = $_POST['id'];
		$sTitle = $_POST['title'];
		$sText =  $_POST['textHtml'];
		
		$sql = 'UPDATE content_page SET
			title = "'.mysql_real_escape_string($sTitle, $db).'",
			text = "'.mysql_real_escape_string($sText, $db).'"		
		    WHERE
			id="'.$sID.'"';
		mysql_query($sql, $db) or die(mysql_error($db));
		$resultQuery = "done";
	    } else {
		$resultQuery = "fail";	      
	    }
	    break;
	    
    
	case 'create':
	   if ( isset($_POST['idIndex']) and isset($_POST['title']) and isset($_POST['textHtml']) and isset($_POST['linkName']) and $_SESSION['access_level'] >= 2 ){
		
		$sAuthor = $_SESSION['id'];
		
		$sIDindex = $_POST['idIndex'];
		$sLinkName = $_POST['linkName'];
		
		$sTitle = $_POST['title'];
		$sText =  $_POST['textHtml'];		
		$sHref = $sTitle.$sIDindex.$sAuthor.".html";
		
		
		$sql = 'INSERT INTO content_page
			(href, title, submit_date, is_published, text)
		    VALUES
			("'.mysql_real_escape_string($sHref, $db).'",
			 "'.mysql_real_escape_string($sTitle, $db).'",
			 "'.date('Y-m-d').'",
			 FALSE,
			 "'.mysql_real_escape_string($sText, $db).'")';
		mysql_query($sql, $db) or die(mysql_error($db));
		$lastInseredContent = mysql_insert_id();
		
		//Aggiungo l'autore
		$sql = 'INSERT IGNORE INTO content_page_author
		(contentPage, author)
		VALUES
		("'.$lastInseredContent.'",
		"'.$sAuthor.'")';
		mysql_query($sql, $db) or die(mysql_error($db));
		
		$sql = 'SELECT id 
		    FROM 
			content_page 
		    WHERE 
			href="'.mysql_real_escape_string($sHref, $db).'"';
			
		$result = mysql_query($sql, $db);
		if (mysql_num_rows($result) == 1) {
		    $row = mysql_fetch_array($result);
		    $sID = $row['id'];
		}
		
		$sql = 'INSERT IGNORE INTO index_2_content 
			    (id_start_index_page, id_target_content_page, link_name)
			VALUES
			    ("'.$sIDindex.'",
			     "'.$sID.'",
			     "'.mysql_real_escape_string($sLinkName, $db).'")';	
		mysql_query($sql, $db) or die(mysql_error($db));
		
		$resultQuery = "done";
	    } else {
		$resultQuery = "fail";	      
	    }
	    sendmail("marzorati.andrea@gmail.com", "Vaults Of Pandius", "da cestinare");
	    break;
	    
	    
	case 'approve':
	    if ( isset($_POST['id'])and $_SESSION['access_level'] == 3){
		$sID = $_POST['id'];
				
		$sql = 'UPDATE content_page SET
			is_published = TRUE,
			publish_date = "'.date('Y-m-d').'"
		    WHERE
			id="'.$sID.'"';
		mysql_query($sql, $db) or die(mysql_error($db));
		$resultQuery = "done";
	    } else {
		$resultQuery = "fail";	      
	    }
	    break;    
	    
	 
	case 'delete':
	    if ( isset($_POST['id'])){
		$sID = $_POST['id'];
				
		$sql = 'DELETE FROM content_page 
		    WHERE
			id="'.$sID.'"';
		mysql_query($sql, $db) or die(mysql_error($db));
		//da content_page_author viene eliminata
		//automaticamente: ON DELETE CASCADE
		$resultQuery = "done";
	    } else {
		$resultQuery = "fail";	      
	    } 
	    break;
	    
	}
	
    } 
	    
     echo $resultQuery;
    
?>