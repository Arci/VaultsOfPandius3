<?php

require 'db.php';

// variabili globali
$global = array();

//connessione al database;
$db = mysql_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD) or
		die ('Unable to connect. Check your connection parameters.');
mysql_select_db(MYSQL_DB, $db) or die(mysql_error($db));

// creazione oggetti DOM 
$dom = new DomDocument();
$domHTML = new DomDocument();

// variabili per il <br/>
$br = 4;


// pagina da cui estrarre il contenuto
echo "----> RESOURCES <----";
extractIndex('resource.html', $db, $dom, $domHTML);

require_once('common.php');
cleanIndexTable($db);


//**************************************************************************************************************//


function extractIndex($iref, $db, $dom, $domHTML){
      
      $dom = new DomDocument();
      // open if file exists
      if(!$domHTML->loadHTMLFile('./pandius.com/'.$iref)){
	  echo "Cannot open page ".$iref;
	  return;
      }

      $xpath = new DomXPath($domHTML);
      
      $nodesH2 = $xpath->query("//h2", $domHTML->documentElement);
      if ($nodesH2->length == 1){
	  $title = $nodesH2->item(0)->nodeValue;
      } else {
	  $nodes = $xpath->query("//title", $domHTML->documentElement);
	  $title = $nodes->item(0)->nodeValue;
      }
      
      echo "<br/>-------------<br/>";
      echo "INDEX --> $iref<br/>"; 
      
      $subNodes = array();
      $nodesText = array();
      $nodesText = $xpath->query("//body/blockquote/blockquote", $domHTML->documentElement);
      
      $list = array();
      for ($i=0; $i<$nodesText->length; $i++){
	  $list[$i] = explode(".",$nodesText->item($i)->nodeValue);
      }
      
      $nodes = $xpath->query("//body", $domHTML->documentElement);
      for ($i=0; $i<$nodes->length; $i++) {        
	  $singleNode = $nodes->item($i);
	  //$p = $dom->createElement($singleNode->nodeName);    	  
	  $p = $dom->createElement("xml");
	  $GLOBALS['br'] = 4;
	  exploreIndex($dom, $p, $singleNode);	  
	  $dom->appendChild($p);
      }
      $text = $dom->saveHTML();
           
      $nodes = $xpath->query("//a[contains(@href,'authors')]", $domHTML->documentElement);
      if ($nodes->length > 0){
	    $name = trim($nodes->item(0)->nodeValue);
	    $sql = 'SELECT id 
		      FROM 
			  users 
		      WHERE 
			  name="'.mysql_real_escape_string($name, $db).'"';
	    $result = mysql_query($sql, $db);

	    if (mysql_num_rows($result) == 1) {
		//ok
		$row = mysql_fetch_array($result);
		$author = $row['id'];
		
		$sql = 'INSERT IGNORE INTO index_page 
			(href, title, author, text, menu)
		    VALUES
			("'.$iref.'",
			"'.mysql_real_escape_string($title, $db).'",
			"'.$author.'",
			"'.mysql_real_escape_string($text, $db).'","1")';	
		mysql_query($sql, $db) or die(mysql_error($db));
	    }  else {
		//errore    
	    }
      } else {
	    $author = null;
	    $sql = 'INSERT IGNORE INTO index_page 
			(href, title, author, text, menu)
		    VALUES
			("'.$iref.'",
			"'.mysql_real_escape_string($title, $db).'",
			NULL,
			"'.mysql_real_escape_string($text, $db).'","1")';	
		mysql_query($sql, $db) or die(mysql_error($db));
      }
                

      $sql = 'SELECT id 
	  FROM 
	      index_page 
	  WHERE 
	      href="'.mysql_real_escape_string($iref, $db).'"';
      $result = mysql_query($sql, $db);
      if (mysql_num_rows($result) == 1) {
	  //ok
	  $row = mysql_fetch_array($result);
	  $id_index_page = $row['id'];
      }  else {
	  //errore    
      }
      mysql_free_result($result);

            
      $nodes = $xpath->query("//a[contains(@href,'html') and not(contains(@href,'authors')) and not(contains(@href,'#'))]", $domHTML->documentElement);
      
      if (($iref == 'stories.html')or($iref == 'atlas.html')or($iref == 'resource.html')or($iref == 'adv_camp.html')) 
	  {$start=8;} else {$start=9;}
      
      for ($i=$start; $i<$nodes->length; $i++) {        
	  $singleNode = $nodes->item($i);  
	  $ref = $singleNode->attributes->getNamedItem('href')->nodeValue;
	  $name = $singleNode->nodeValue;
	  //recupero informazioni from e date
	  $info = array();
	  foreach($list as $element){
	    foreach($element as $article){
	      if(strstr($article,$name)){
		echo "list selected: ".$article."<br/>";
		$info = extractInfo($article,$name);
	      }
	    }
	  }
	  
	  if (!(in_array($ref, $GLOBALS['global']))) {
		
		//inserisco il ref nell'array'
		$GLOBALS['global'][] = $ref;
		
		// METTO QUI LA CHIAMATA A extractContentPage.php //
		if (extractContent($ref, $db, $dom, $domHTML,$info)){
			  
		    // *** LA PAGINA ESAMINATA SI è RIVELATA EFFETTIVAMENTE UNA PAGINA CONTENT ***
		    // ottengo l'id della pagina content
		    $sql = 'SELECT id 
			FROM 
			    content_page 
			WHERE 
			    href="'.mysql_real_escape_string($ref, $db).'"';
		    $result = mysql_query($sql, $db);
		    if (mysql_num_rows($result) == 1) {
			//ok
			$row = mysql_fetch_array($result);
			$id_target_content_page = $row['id'];
		    } else {
			//errore    
		    }
		    mysql_free_result($result);


		    // ORA AGGIUNGO IL LINK TRA LE DUE PAGINE
		    $sql = 'INSERT IGNORE INTO index_2_content 
			    (id_start_index_page, id_target_content_page, link_name)
			VALUES
			    ("'.$id_index_page.'",
			    "'.$id_target_content_page.'",
			    "'.mysql_real_escape_string($name, $db).'")';	
		    mysql_query($sql, $db) or die(mysql_error($db));
		} else {
			  
		  // *** LA PAGINA ESAMINATA SI è RIVELATA INVECE UNA PAGINA INDEX ***
		  $sql = 'SELECT id 
			FROM 
			    index_page 
			WHERE 
			    href="'.mysql_real_escape_string($ref, $db).'"';
		    $result = mysql_query($sql, $db);
		    if (mysql_num_rows($result) == 1) {
			//ok
			$row = mysql_fetch_array($result);
			$id_target_index_page = $row['id'];
		    } else {
			//errore    
		    }
		    mysql_free_result($result);


		    // ORA AGGIUNGO IL LINK TRA LE DUE PAGINE
		    $sql = 'INSERT IGNORE INTO index_2_content 
			    (id_start_index_page, id_target_index_page, link_name)
			VALUES
			    ("'.$id_index_page.'",
			    "'.$id_target_index_page.'",
			    "'.mysql_real_escape_string($name, $db).'")';	
		    mysql_query($sql, $db) or die(mysql_error($db));

		}
	  
	  }
      }

  // max_allowed_packet = 1M */
  
}


function extractInfo($article,$name){
    $info = array( 'from' => null,'date' => null);
    if(strstr($article,'from') && strstr($article,'posted')){
	/*DEBUG*/$fullName = trim(substr($article,0,strpos($article,"from")));
	$info['from'] = trim(substr($article,strpos($article,"from")+4,(strpos($article,"posted")-strpos($article,"from")-4)));
	$info['date'] = date('Y-m-d',strtotime(trim(substr($article,strpos($article,"posted")+6))));
	if(strstr($info['from'],'from') || strstr($info['from'],'posted')){
	  if(strstr($info['from'],"the Mystara Message Board")){
	    $info['from'] = "the Mystara Message Board";
	  }else if(strstr($info['from'],"The Piazza")){
	    $info['from'] = "The Piazza";
	  }else if(strstr($info['from'],"the Mystara Mailing List")){
	    $info['from'] = "the Mystara Mailing List";
	  }
	}
    }else if(strstr($article,'from') && !strstr($article,'posted')){
	if(strstr($article,'current as of')){
	  /*DEBUG*/$fullName = trim(substr($article,0,strpos($article,"from")));
	  $info['from'] = trim(substr($article,strpos($article,"from")+4,(strpos($article,"current as of")-strpos($article,"from")-4)));
	  $info['date'] = date('Y-m-d',strtotime(trim(substr($article,strpos($article,"current as of")+13))));
	}else{
	  /*DEBUG*/$fullName = trim(substr($article,0,strpos($article,"from")));
	  $from = trim(substr($article,strpos($article,"from")+4));
	  if(strstr($from,"the Mystara Message Board")){
	      $date = substr($from,25);
	  }else if(strstr($from,"The Piazza")){
	      $date = substr($from,10);
	  }else if(strstr($from,"the Mystara Mailing List")){
	      $date = substr($from,24);		
	  }else if(strstr($from,"the Savage Coast Monstrous Manual")){
		  $date = substr($from,33);	
	  }
	  $info['from'] = substr($from,0,(strlen($from)-strlen($date)));
	  $info['date'] = date('Y-m-d',strtotime(trim($date)));
	}
    }else if(strstr($article,'current as of')){
	/*DEBUG*/ $fullName = trim(substr($article,0,strpos($article,"current as of")));
	$info['date']= date('Y-m-d',strtotime(trim(substr($article,strpos($article,"current as of")+13))));
    }else if(strstr($article,'last section updated')){
	/*DEBUG*/ $fullName = trim(substr($article,0,strpos($article,"last section updated")));
	$info['date']= date('Y-m-d',strtotime(trim(substr($article,strpos($article,"last section updated")+20))));
    }
    
    /*DEBUG*/ echo "<b>name:</b> ".$name." <b>from:</b> ".$info['from']." <b>date:</b> ".$info['date']."<br/>";
    
    return $info;
}


function extractContent($ref, $db, $dom, $domHTML, $info){
      
      $dom = new DomDocument();
      // open if file exists
      if(!$domHTML->loadHTMLFile('./pandius.com/'.$ref)){
	echo "Cannot open page ".$ref;
	return;
      }
            
      $xpath = new DomXPath($domHTML);

      $nodes = $xpath->query("//p | //ul  | //ol | //table", $domHTML->documentElement);
      $nodesA = $xpath->query("//a[contains(@href,'html') and not(contains(@href,'authors')) and not(contains(@href,'#'))]", $domHTML->documentElement);
      
      
      //se non ci sono <p> allora mi trovo in una index page e quindi devo chiamare la extractIndex e terminare
      if ( (($nodes->length <= 1) and ($nodesA->length >= 11)) or ( ($nodes->length >= 2) and ((($nodesA->length) >= 5* ($nodes->length)))) ){
	  
//	  echo 'INDEX: P = '.$nodes->length.', A = '.$nodesA->length.' --> '.$ref.'<br/>';
	  
	  extractIndex($ref, $db, $dom, $domHTML);
	  return false;
      }
      
//      echo 'CONTENT: P = '.$nodes->length.', A = '.$nodesA->length.' --> '.$ref.'<br/>';
            
      for ($i=1; $i<$nodes->length; $i++) {
         $singleNode = $nodes->item($i);
	 if($singleNode->nodeName== "p"){
	    $p = $dom->createElement('p');    	  
	    explore($dom, $p, $singleNode);
	    $dom->appendChild($p);
	  }else if($singleNode->nodeName == "ul"){
	    $ul = $dom->createElement('ul');    	  
	    explore($dom, $ul, $singleNode);
	    $dom->appendChild($ul);
	  }else if($singleNode->nodeName == "ol"){
	    $ol = $dom->createElement('ol');    	  
	    explore($dom, $ol, $singleNode);
	    $dom->appendChild($ol);
	  }else if($singleNode->nodeName == "table"){
	    $table = $dom->createElement('table');    	  
	    explore($dom, $table, $singleNode);
	    $dom->appendChild($table);
	  }
      }
      $text = $dom->saveHTML();
      
      //$nodes = $xpath->query("//h2", $domHTML->documentElement);
      $nodes = $xpath->query("//title", $domHTML->documentElement);
      $title = $nodes->item(0)->nodeValue;

      //aggiungo l'articolo
      //filtro il campo from per eliminare falsi positivi
      if($info['from'] != null && (strlen($info['from']) > 33) || strstr($info['from'],"by")){
	echo "FROM FIELD DELETED<br/>";
	$info['from'] = null;
      }
      if($info['date'] != null && $info['from'] != null){
	$sql = 'INSERT IGNORE INTO content_page 
	      (href, title, source, submit_date, publish_date, is_published, text)
	  VALUES
	      ("'.$ref.'",
	      "'.mysql_real_escape_string($title, $db).'",
	      "'.$info['from'].'",
	      "'.date('Y-m-d').'",
	      "'.$info['date'].'",
	      TRUE,
	      "'.mysql_real_escape_string($text, $db).'")';
      }else if($info['date'] != null && $info['from'] == null){
	$sql = 'INSERT IGNORE INTO content_page 
	      (href, title, submit_date, publish_date, is_published, text)
	  VALUES
	      ("'.$ref.'",
	      "'.mysql_real_escape_string($title, $db).'",
	      "'.date('Y-m-d').'",
	      "'.$info['date'].'",
	      TRUE,
	      "'.mysql_real_escape_string($text, $db).'")';
      }else{
	$sql = 'INSERT IGNORE INTO content_page 
	      (href, title, submit_date, is_published, text)
	  VALUES
	      ("'.$ref.'",
	      "'.mysql_real_escape_string($title, $db).'",
	      "'.date('Y-m-d').'",
	      TRUE,
	      "'.mysql_real_escape_string($text, $db).'")';
      }
      mysql_query($sql, $db) or die(mysql_error($db));
      $lastInseredContent = mysql_insert_id();

      //scorro tutti gli autori e li aggungo
      $nodes = $xpath->query("//a[contains(@href,'authors')]", $domHTML->documentElement);
      
      foreach($nodes as $node){
	    $name = trim($node->nodeValue);
	    //fix degli utenti che danno problemi
	    require_once('common.php');
	    $name = fixUser($name);
	    $sql = 'SELECT id 
		FROM 
		    users 
		WHERE 
		    name="'.mysql_real_escape_string($name, $db).'"';
	    $result = mysql_query($sql, $db);
	    
	    if (mysql_num_rows($result) == 1) {
		//ok
		$row = mysql_fetch_array($result);
		$author = $row['id'];
	    }  else {
		//errore    
	    }
	    mysql_free_result($result);
	    $sql = 'INSERT IGNORE INTO content_page_author
		(contentPage, author)
		VALUES
		("'.$lastInseredContent.'",
		"'.$author.'")';
	    mysql_query($sql, $db) or die(mysql_error($db));
      }
      return true;
}




function exploreIndex($dom, $fatherElement, $fatherNode){
            
      foreach ($fatherNode->childNodes as $childNode){
	  if ($childNode->nodeName != "center" && $childNode->nodeName != "h2" && $childNode->nodeName != "h1"){	    	  
	      if($childNode->hasChildNodes()){
		  if ($childNode->nodeName == "a" && strpos("dkaj".$childNode->attributes->getNamedItem("href")->nodeValue, "authors.html")){
		    exploreIndex($dom, $fatherElement, $childNode);
		    continue;
		  }
		  if ($childNode->nodeName == "a"){
		      $childElement = $dom->createElement($childNode->nodeName);		      
		      if($childNode->hasAttributes()){
			  foreach ($childNode->attributes as $attribute){
			      $childElement->setAttribute($attribute->name, "#");
			      $childElement->setAttribute("onclick", "linkTo('".$attribute->value."'); return false");
			  }
		      }
		      exploreIndex($dom, $childElement, $childNode);	      
		      $fatherElement->appendChild($childElement);    
		  } else {
		      $childElement = $dom->createElement($childNode->nodeName);
		      if($childNode->hasAttributes()){
			  foreach ($childNode->attributes as $attribute){
			      $childElement->setAttribute($attribute->name, $attribute->value);
			  }
		      }   	      
		      exploreIndex($dom, $childElement, $childNode);	      
		      $fatherElement->appendChild($childElement);
		  }   
	      } else {
		  //allora è un nodo testo	      
		  if ($childNode->nodeType == 3){ //costante del tipo testo
		    $childElement = $dom->createTextNode($childNode->nodeValue);
		    $fatherElement->appendChild($childElement);   
		  } else {
		  //o un nodo senza figli
		    if ($childNode->nodeType != 8){
		      //i commenti mi danno problemi quindi non li considero
		      if ($childNode->nodeName == "br") {
			$GLOBALS['br']--;
			if ($GLOBALS['br'] < 0){
			  $childElement = $dom->createElement($childNode->nodeName);
			  $fatherElement->appendChild($childElement);
			}
		      } else {
			$childElement = $dom->createElement($childNode->nodeName);
			$fatherElement->appendChild($childElement);
		      }      
		    }
		  }     
	      }
	  }
      }    
}


function exploreIndexH2($dom, $fatherElement, $fatherNode){
            
      foreach ($fatherNode->childNodes as $childNode){
	  if ($childNode->nodeName != "center" && $childNode->nodeName != "h1"){	    	  
	      if($childNode->hasChildNodes()){
		  if ($childNode->nodeName == "a" && strpos("dkaj".$childNode->attributes->getNamedItem("href")->nodeValue, "authors.html")){
		    exploreIndexH2($dom, $fatherElement, $childNode);
		    continue;
		  }
		  if ($childNode->nodeName == "a"){
		      $childElement = $dom->createElement($childNode->nodeName);		      
		      if($childNode->hasAttributes()){
			  foreach ($childNode->attributes as $attribute){
			      $childElement->setAttribute($attribute->name, "#");
			      $childElement->setAttribute("onclick", "linkTo('".$attribute->value."'); return false");
			  }
		      }
		      exploreIndexH2($dom, $childElement, $childNode);	      
		      $fatherElement->appendChild($childElement);    
		  } else {
		      $childElement = $dom->createElement($childNode->nodeName);
		      if($childNode->hasAttributes()){
			  foreach ($childNode->attributes as $attribute){
			      $childElement->setAttribute($attribute->name, $attribute->value);
			  }
		      }   	      
		      exploreIndexH2($dom, $childElement, $childNode);	      
		      $fatherElement->appendChild($childElement);
		  }   
	      } else {
		  //allora è un nodo testo	      
		  if ($childNode->nodeType == 3){ //costante del tipo testo
		    $childElement = $dom->createTextNode($childNode->nodeValue);
		    $fatherElement->appendChild($childElement);   
		  } else {
		  //o un nodo senza figli
		    if ($childNode->nodeType != 8){
		      //i commenti mi danno problemi quindi non li considero
		      if ($childNode->nodeName == "br") {
			$GLOBALS['br']--;
			if ($GLOBALS['br'] < 0){
			  $childElement = $dom->createElement($childNode->nodeName);
			  $fatherElement->appendChild($childElement);
			}
		      } else {
			$childElement = $dom->createElement($childNode->nodeName);
			$fatherElement->appendChild($childElement);
		      }      
		    }
		  }     
	      }
	  }
      }    
}


function explore($dom, $fatherElement, $fatherNode){
            
      foreach ($fatherNode->childNodes as $childNode){  
	      if($childNode->hasChildNodes()){
		  $childElement = $dom->createElement($childNode->nodeName);
		  if($childNode->hasAttributes()){
		      foreach ($childNode->attributes as $attribute){
			  $childElement->setAttribute($attribute->name, $attribute->value);
		      }
		  }   	      
		  explore($dom, $childElement, $childNode);	      
		  $fatherElement->appendChild($childElement);
	      } else {
		  //allora è un nodo testo	      
		  if ($childNode->nodeType == 3){ //costante del tipo testo
		    $childElement = $dom->createTextNode($childNode->nodeValue);
		    $fatherElement->appendChild($childElement);   
		  } else {
		  //o un nodo senza figli
		    if ($childNode->nodeType != 8){
		      //i commenti mi danno problemi quindi non li considero
		      $childElement = $dom->createElement($childNode->nodeName);
			  // aggiunta del campo src ai tag img
			  if($childNode->nodeName == "img"){
				foreach ($childNode->attributes as $attribute){
				$childElement->setAttribute($attribute->name, "data/".$attribute->value);
				}
			  }
			  $fatherElement->appendChild($childElement);
		    }
		  }	     	      
	      }  
      }    
}

echo 'success';

?>

