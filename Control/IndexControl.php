<?php
/*
 * The controller of the index page 
 */

    header("Content-Type: text/xml");
    
    require 'db.php';
    
    $link_id = array();
    
    // connect to DB
    $db = mysql_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD) or
	die ('Unable to connect. Check your connection parameters.');
    mysql_select_db(MYSQL_DB, $db) or die(mysql_error($db));
   
    $resultQuery = '<?xml version="1.0" encoding="utf-8"?><LINKS>';
    
    $sAuthor = $_GET["author"];    
    switch ($sAuthor) {
    case 'all':
	
	$sID = $_GET["id"]; 
	
	if (isset($_GET['pending'])){
	    
	    $sql = 'SELECT 
		      id_target_content_page, id_target_index_page, link_name 
		  FROM 
		      index_2_content 
		  WHERE 
		      id_start_index_page="'.$sID.'" AND id_target_index_page IS NOT NULL AND link_name NOT LIKE "%&%"';
	      $result = mysql_query($sql, $db);
	      while ($row = mysql_fetch_array($result)) {    
		  $GLOBALS["link_id"][] = $row['id_target_index_page'];
		  if (searchPendingAll($row['id_target_index_page'])){		 
		      $resultQuery = $resultQuery.'<LINK id="'.$row['id_target_index_page'].'" type="index">'.$row['link_name'].'</LINK>';  
		  } 	    
	      }
	      
	      $sql = 'SELECT
		      id_target_content_page, id_target_index_page, link_name
		  FROM 
		      index_2_content JOIN content_page ON id_target_content_page=content_page.id  
		  WHERE 
		      id_start_index_page="'.$sID.'" AND link_name NOT LIKE "%&%" AND is_published IS FALSE
		  ORDER BY link_name ASC';

	      $result = mysql_query($sql, $db);    
	      while ($row = mysql_fetch_array($result)) {
		  $resultQuery = $resultQuery.'<LINK id="'.$row['id_target_content_page'].'" type="content">'.$row['link_name'].'</LINK>';
	      }            
	      mysql_free_result($result);
	    
	} else {
	      	  

	    $sql = 'SELECT
		    id_target_content_page, id_target_index_page, link_name
		FROM
		    index_2_content
		WHERE
		    id_start_index_page="'.$sID.'" AND link_name NOT LIKE "%&%"
		ORDER BY link_name ASC';
	    $result = mysql_query($sql, $db);    
	    while ($row = mysql_fetch_array($result)) {
		    if ($row['id_target_content_page']==NULL){
			$resultQuery = $resultQuery.'<LINK id="'.$row['id_target_index_page'].'" type="index">'.$row['link_name'].'</LINK>';  
		    } else {
			$resultQuery = $resultQuery.'<LINK id="'.$row['id_target_content_page'].'" type="content">'.$row['link_name'].'</LINK>';
		    }
		
	    }            
	    mysql_free_result($result);
	}
			    
        break;
    
    default:
            
	$sID = $_GET["id"];	
	if (isset($_GET['pending'])){
	  
	      $sql = 'SELECT 
		      id_target_content_page, id_target_index_page, link_name 
		  FROM 
		      index_2_content 
		  WHERE 
		      id_start_index_page="'.$sID.'" AND id_target_index_page IS NOT NULL AND link_name NOT LIKE "%&%"
		  ORDER BY link_name ASC';
	      $result = mysql_query($sql, $db);
	      while ($row = mysql_fetch_array($result)) {    
		  $GLOBALS["link_id"][] = $row['id_target_index_page'];
		  if (searchPending($row['id_target_index_page'], $sAuthor)){		 
		      $resultQuery = $resultQuery.'<LINK id="'.$row['id_target_index_page'].'" type="index">'.$row['link_name'].'</LINK>';  
		  } 	    
	      }
	      
	      $sql = 'SELECT
		      id_target_content_page, id_target_index_page, link_name
		  FROM 
		      index_2_content JOIN content_page ON id_target_content_page=content_page.id  
		  WHERE 
		      author="'.$sAuthor.'" AND id_start_index_page="'.$sID.'" AND link_name NOT LIKE "%&%" AND is_published IS FALSE
		  ORDER BY link_name ASC';
	      $result = mysql_query($sql, $db);    
	      while ($row = mysql_fetch_array($result)) {
		  $resultQuery = $resultQuery.'<LINK id="'.$row['id_target_content_page'].'" type="content">'.$row['link_name'].'</LINK>';
	      }            
	      mysql_free_result($result);
	  
	  
	} else {

	      $sql = 'SELECT 
		      id_target_content_page, id_target_index_page, link_name 
		  FROM 
		      index_2_content 
		  WHERE 
		      id_start_index_page="'.$sID.'" AND id_target_index_page IS NOT NULL AND link_name NOT LIKE "%&%"
		  ORDER BY link_name ASC';
	      $result = mysql_query($sql, $db);
	      while ($row = mysql_fetch_array($result)) {
		  $GLOBALS["link_id"][] = $row['id_target_index_page'];
		  if (search($row['id_target_index_page'], $sAuthor)){		 
		      $resultQuery = $resultQuery.'<LINK id="'.$row['id_target_index_page'].'" type="index">'.$row['link_name'].'</LINK>';  
		  } 	    
	      }
	      
	      $sql = 'SELECT
		      id_target_content_page, id_target_index_page, link_name
		  FROM 
		      index_2_content JOIN content_page ON id_target_content_page=content_page.id  
		  WHERE 
		      author="'.$sAuthor.'" AND id_start_index_page="'.$sID.'" AND link_name NOT LIKE "%&%" AND is_published IS TRUE
		  ORDER BY link_name ASC';
	      $result = mysql_query($sql, $db);    
	      while ($row = mysql_fetch_array($result)) {
		  $resultQuery = $resultQuery.'<LINK id="'.$row['id_target_content_page'].'" type="content">'.$row['link_name'].'</LINK>';
	      }            
	      mysql_free_result($result);
    
	}
    
    
	break;
    }
    
    $resultQuery = $resultQuery.'</LINKS>';
      
    echo $resultQuery;
    
    
    
    
    function search($sIdSub, $sAuth){
	
	$db = $GLOBALS["db"]; 
	$sql = 'SELECT link_name 
	    FROM 
		content_page JOIN index_2_content ON content_page.id=id_target_content_page 
	    WHERE 
		author="'.$sAuth.'" AND id_start_index_page="'.$sIdSub.'" AND link_name NOT LIKE "%&%" AND is_published IS TRUE
	    ORDER BY link_name ASC';
	$result = mysql_query($sql, $db);    	  
	if ($row = mysql_fetch_array($result)) {
	  return true;    
	}            
	mysql_free_result($result);

	
	
	$sql = 'SELECT id_target_index_page 
	    FROM 
		index_2_content 
	    WHERE 
		id_start_index_page="'.$sIdSub.'" AND id_target_index_page IS NOT NULL AND link_name NOT LIKE "%&%"';
	$result = mysql_query($sql, $db);    
	while ($row = mysql_fetch_array($result)) {
	    if (!(in_array($row['id_target_index_page'], $GLOBALS['link_id']))) {
		$GLOBALS["link_id"][] = $row['id_target_index_page'];
		if (search($row['id_target_index_page'], $sAuth)){
		  return true;
		}
	    }
	}            
	mysql_free_result($result);
	
	
	return false;
    }
    
    
    function searchPending($sIdSub, $sAuth){
	
	$db = $GLOBALS["db"]; 
	$sql = 'SELECT link_name 
	    FROM 
		content_page JOIN index_2_content ON content_page.id=id_target_content_page 
	    WHERE 
		author="'.$sAuth.'" AND id_start_index_page="'.$sIdSub.'" AND link_name NOT LIKE "%&%" AND is_published IS FALSE
	    ORDER BY link_name ASC';
	$result = mysql_query($sql, $db);    	  
	if ($row = mysql_fetch_array($result)) {
	  return true;    
	}            
	mysql_free_result($result);

	
	
	$sql = 'SELECT id_target_index_page 
	    FROM 
		index_2_content 
	    WHERE 
		id_start_index_page="'.$sIdSub.'" AND id_target_index_page IS NOT NULL AND link_name NOT LIKE "%&%"';
	$result = mysql_query($sql, $db);    
	while ($row = mysql_fetch_array($result)) {
	    if (!(in_array($row['id_target_index_page'], $GLOBALS['link_id']))) {
		$GLOBALS["link_id"][] = $row['id_target_index_page'];
		if (searchPending($row['id_target_index_page'], $sAuth)){
		  return true;
		}
	    }
	}            
	mysql_free_result($result);
	
	
	return false;
    }
    
    
    function searchPendingAll($sIdSub){
	$db = $GLOBALS["db"]; 
	$sql = 'SELECT link_name 
	    FROM 
		content_page JOIN index_2_content ON content_page.id=id_target_content_page 
	    WHERE 
		id_start_index_page="'.$sIdSub.'" AND link_name NOT LIKE "%&%" AND is_published IS FALSE
	    ORDER BY link_name ASC';
	$result = mysql_query($sql, $db);    	  
	if ($row = mysql_fetch_array($result)) {
	  return true;    
	}            
	mysql_free_result($result);

	
	
	$sql = 'SELECT id_target_index_page 
	    FROM 
		index_2_content 
	    WHERE 
		id_start_index_page="'.$sIdSub.'" AND id_target_index_page IS NOT NULL AND link_name NOT LIKE "%&%"';
	$result = mysql_query($sql, $db);    
	while ($row = mysql_fetch_array($result)) {
	    if (!(in_array($row['id_target_index_page'], $GLOBALS['link_id']))) {
		$GLOBALS["link_id"][] = $row['id_target_index_page'];
		if (searchPendingAll($row['id_target_index_page'])){
		  return true;
		}
	    }
	}            
	mysql_free_result($result);
	
	
	return false;
    }
?>