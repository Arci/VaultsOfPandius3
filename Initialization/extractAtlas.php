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
echo "----> ATLAS <----";
extractFirstPage('atlas.html', $db, $dom, $domHTML);

cleanIndexTable($db);



//**************************************************************************************************************//



function extractFirstPage($iref, $db, $dom, $domHTML){
      
  // inserisco la prima pagina
      $dom = new DomDocument();
      // open if file exists
      if(!$domHTML->loadHTMLFile('./pandius.com/'.$iref)){
	  echo "Cannot open page ".$iref;
	  return;
      }

      $xpath = new DomXPath($domHTML);      
      $nodes = $xpath->query("//title", $domHTML->documentElement);
      $title = $nodes->item(0)->nodeValue;
      $sql = 'INSERT IGNORE INTO index_page 
		  (href, title, author, text, menu)
	      VALUES
		  ("'.$iref.'",
		  "'.mysql_real_escape_string($title, $db).'",
		  NULL,
		  NULL,"1")';	
      mysql_query($sql, $db) or die(mysql_error($db));
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
      
      
      extractIndex('maps.html', $db, $dom, $domHTML);
      $sql = 'SELECT id 
	  FROM 
	      index_page 
	  WHERE 
	      href="'.mysql_real_escape_string("maps.html", $db).'"';
      $result = mysql_query($sql, $db);
      if (mysql_num_rows($result) == 1) {
	  //ok
	  $row = mysql_fetch_array($result);
	  $id_target_index_page = $row['id'];
      }  else {
	  //errore    
      }
      mysql_free_result($result);
      $sql = 'INSERT IGNORE INTO index_2_content 
			    (id_start_index_page, id_target_index_page, link_name)
			VALUES
			    ("'.$id_index_page.'",
			    "'.$id_target_index_page.'",
			    "'.mysql_real_escape_string("Maps", $db).'")';	
	    mysql_query($sql, $db) or die(mysql_error($db));
      
      
      
      extractBlockquote('planejam.html', $db, $dom, $domHTML);
      $sql = 'SELECT id 
	  FROM 
	      index_page 
	  WHERE 
	      href="'.mysql_real_escape_string("planejam.html", $db).'"';
      $result = mysql_query($sql, $db);
      if (mysql_num_rows($result) == 1) {
	  //ok
	  $row = mysql_fetch_array($result);
	  $id_target_index_page = $row['id'];
      }  else {
	  //errore    
      }
      mysql_free_result($result);
      $sql = 'INSERT IGNORE INTO index_2_content 
			    (id_start_index_page, id_target_index_page, link_name)
			VALUES
			    ("'.$id_index_page.'",
			    "'.$id_target_index_page.'",
			    "'.mysql_real_escape_string("Mystaraspace", $db).'")';	
	    mysql_query($sql, $db) or die(mysql_error($db));
      
      
      extractBlockquoteFake($iref, $db, $dom, $domHTML,"worlds.html");
      $sql = 'SELECT id 
	  FROM 
	      index_page 
	  WHERE 
	      href="'.mysql_real_escape_string("worlds.html", $db).'"';
      $result = mysql_query($sql, $db);
      if (mysql_num_rows($result) == 1) {
	  //ok
	  $row = mysql_fetch_array($result);
	  $id_target_index_page = $row['id'];
      }  else {
	  //errore    
      }
      mysql_free_result($result);
      $sql = 'INSERT IGNORE INTO index_2_content 
			    (id_start_index_page, id_target_index_page, link_name)
			VALUES
			    ("'.$id_index_page.'",
			    "'.$id_target_index_page.'",
			    "'.mysql_real_escape_string("Worlds", $db).'")';	
	    mysql_query($sql, $db) or die(mysql_error($db));
      
      
      
      /*
      // SOTTOZEZIONE WORLDS 
            
      
      //estraggo le sotto-sezioni
      $nodes = $xpath->query("//h1[position()>1]", $domHTML->documentElement);
      //echo $nodes->length;      
      
      $subNodes = array();
      for ($j=0; $j<$nodes->length; $j++) {       	  
	  $subNodes[] = $xpath->query("//blockquote/blockquote[position()=".($j+2)."]/a[contains(@href,'html') and not(contains(@href,'authors')) and not(contains(@href,'#'))]", $domHTML->documentElement);
      }
      
            
      for ($j=0; $j<$nodes->length; $j++) {       
	  $singleNode = $nodes->item($j);
	  $sql = 'INSERT IGNORE INTO index_page 
		  (href, title, author, text)
	      VALUES
		  ("'.$iref.$j.'",
		  "'.mysql_real_escape_string($singleNode->nodeValue, $db).'",
		  NULL,
		  NULL)';
	   mysql_query($sql, $db) or die(mysql_error($db));	   
	   $sql = 'SELECT id 
	      FROM 
		  index_page 
	      WHERE 
		  href="'.mysql_real_escape_string($iref.$j, $db).'"';
	   $result = mysql_query($sql, $db);
	   if (mysql_num_rows($result) == 1) {
	      //ok
	      $row = mysql_fetch_array($result);
	      $id_target_index_page = $row['id'];
	   }  else {
	      //errore    
	   }  
	   mysql_free_result($result);
	   
	   $sql = 'INSERT IGNORE INTO index_2_content 
			    (id_start_index_page, id_target_index_page, link_name)
			VALUES
			    ("'.$id_index_page.'",
			    "'.$id_target_index_page.'",
			    "'.mysql_real_escape_string($name, $db).'")';	
	    mysql_query($sql, $db) or die(mysql_error($db));
	    
	    
	    
	 //   $subNodes[] = $xpath->query("//blockquote/blockquote[position()=".($j+3)."]/a[contains(@href,'html') and not(contains(@href,'authors')) and not(contains(@href,'#'))]", $domHTML->documentElement);
	 //   $subNodes[] = $xpath->query("//blockquote[position()=".($j+3)."]/a[contains(@href,'html') and not(contains(@href,'authors')) and not(contains(@href,'#'))]", $domHTML->documentElement);
	      
	    //inizio parte comune
	    echo "j: ".($j+2)."<br/>";
	   
	    echo "--- subnodes: ".($subNodes[$j]->length)."<br/>";
	    for ($z=0; $z<$subNodes[$j]->length; $z++) {        	    
	    $singleSubNode = $subNodes[$j]->item($z);  
	    $ref = $singleSubNode->attributes->getNamedItem('href')->nodeValue;
	    $name = $singleSubNode->nodeValue;
	    if (!(in_array($ref, $GLOBALS['global']))) {
		  //inserisco il ref nell'array'
		  $GLOBALS['global'][] = $ref;
		  
		  // METTO QUI LA CHIAMATA A extractContentPage.php //
		  if (extractContent($ref, $db, $dom, $domHTML)){
			    
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
			      ("'.$id_target_index_page.'",
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
			  $id_new_target_index_page = $row['id'];
		      } else {
			  //errore    
		      }
		      mysql_free_result($result);


		      // ORA AGGIUNGO IL LINK TRA LE DUE PAGINE
		      $sql = 'INSERT IGNORE INTO index_2_content 
			      (id_start_index_page, id_target_index_page, link_name)
			  VALUES
			      ("'.$id_target_index_page.'",
			      "'.$id_new_target_index_page.'",
			      "'.mysql_real_escape_string($name, $db).'")';	
		      mysql_query($sql, $db) or die(mysql_error($db));

		  }
	    
		}
	    }
	    //fine parte comune
      }
      */
}


function extractBlockquote($iref, $db, $dom, $domHTML){
  
  // inserisco la prima pagina
      $dom = new DomDocument();
      // open if file exists
      if(!$domHTML->loadHTMLFile('./pandius.com/'.$iref)){
	  echo "Cannot open page ".$iref;
	  return;
      }

      $xpath = new DomXPath($domHTML);      
      $nodes = $xpath->query("//title", $domHTML->documentElement);
      $title = $nodes->item(0)->nodeValue;
      $sql = 'INSERT IGNORE INTO index_page 
		  (href, title, author, text)
	      VALUES
		  ("'.$iref.'",
		  "'.mysql_real_escape_string($title, $db).'",
		  NULL,
		  NULL)';	
      mysql_query($sql, $db) or die(mysql_error($db));
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
      
      
      
      
      //estraggo le sotto-sezioni
      $nodes = $xpath->query("//h2", $domHTML->documentElement);
      //echo $nodes->length;      
      
      $subNodes = array();
      $nodesText = array();
      for ($j=0; $j<$nodes->length; $j++) {       	  
	  $subNodes[] = $xpath->query("//blockquote/blockquote[position()=".($j+1)."]/a[contains(@href,'html') and not(contains(@href,'authors')) and not(contains(@href,'#'))]", $domHTML->documentElement);
	  $nodesText[] = $xpath->query("//blockquote/blockquote[position()=".($j+1)."]", $domHTML->documentElement);	
      }
           
            
      for ($j=0; $j<$nodes->length; $j++) {       
	  $singleNode = $nodes->item($j);
	  
	  $dom = new DomDocument();
	  for ($i=0; $i<$nodesText[$j]->length; $i++) {        
	      $singleNodeText = $nodesText[$j]->item($i);
	      //$p = $dom->createElement($singleNode->nodeName);    	  
	      $p = $dom->createElement("xml");
	      $GLOBALS['br'] = 0;
	      exploreIndex($dom, $p, $singleNodeText);	  
	      $dom->appendChild($dom->createElement("br"));
	      $bq = $dom->createElement("blockquote");
	      $bq->appendChild($p);
	      $dom->appendChild($bq);
	  }
	  $text = $dom->saveHTML();
	  
	  
	  $sql = 'INSERT IGNORE INTO index_page 
		  (href, title, author, text)
	      VALUES
		  ("'.$iref.$j.'",
		  "'.mysql_real_escape_string($singleNode->nodeValue, $db).'",
		  NULL,
		  "'.mysql_real_escape_string($text, $db).'")';
		  
	   mysql_query($sql, $db) or die(mysql_error($db));	   
	   $sql = 'SELECT id 
	      FROM 
		  index_page 
	      WHERE 
		  href="'.mysql_real_escape_string($iref.$j, $db).'"';
	   $result = mysql_query($sql, $db);
	   if (mysql_num_rows($result) == 1) {
	      //ok
	      $row = mysql_fetch_array($result);
	      $id_target_index_page = $row['id'];
	   }  else {
	      //errore    
	   }  
	   mysql_free_result($result);
	   
	   $sql = 'INSERT IGNORE INTO index_2_content 
			    (id_start_index_page, id_target_index_page, link_name)
			VALUES
			    ("'.$id_index_page.'",
			    "'.$id_target_index_page.'",
			    "'.mysql_real_escape_string($singleNode->nodeValue, $db).'")';	
	    mysql_query($sql, $db) or die(mysql_error($db));
	    
	    
	    
	 //   $subNodes[] = $xpath->query("//blockquote/blockquote[position()=".($j+3)."]/a[contains(@href,'html') and not(contains(@href,'authors')) and not(contains(@href,'#'))]", $domHTML->documentElement);
	 //   $subNodes[] = $xpath->query("//blockquote[position()=".($j+3)."]/a[contains(@href,'html') and not(contains(@href,'authors')) and not(contains(@href,'#'))]", $domHTML->documentElement);
	      
	    //inizio parte comune
	    echo "j: ".($j+2)."<br/>";
	   
	    echo "--- subnodes: ".($subNodes[$j]->length)."<br/>";
	    for ($z=0; $z<$subNodes[$j]->length; $z++) {        	    
	    $singleSubNode = $subNodes[$j]->item($z);  
	    $ref = $singleSubNode->attributes->getNamedItem('href')->nodeValue;
	    $name = $singleSubNode->nodeValue;
	    if (!(in_array($ref, $GLOBALS['global']))) {
		  //inserisco il ref nell'array'
		  $GLOBALS['global'][] = $ref;
		  
		  // METTO QUI LA CHIAMATA A extractContentPage.php //
		  if (extractContent($ref, $db, $dom, $domHTML)){
			    
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
			      ("'.$id_target_index_page.'",
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
			  $id_new_target_index_page = $row['id'];
		      } else {
			  //errore    
		      }
		      mysql_free_result($result);


		      // ORA AGGIUNGO IL LINK TRA LE DUE PAGINE
		      $sql = 'INSERT IGNORE INTO index_2_content 
			      (id_start_index_page, id_target_index_page, link_name)
			  VALUES
			      ("'.$id_target_index_page.'",
			      "'.$id_new_target_index_page.'",
			      "'.mysql_real_escape_string($name, $db).'")';	
		      mysql_query($sql, $db) or die(mysql_error($db));

		  }
	    
		}
	    }
	    //fine parte comune
      }
      
      
      
      
}


function extractBlockquoteFake($iref, $db, $dom, $domHTML, $fakeref){
  
  // inserisco la prima pagina
      $dom = new DomDocument();
      // open if file exists
      if(!$domHTML->loadHTMLFile('./pandius.com/'.$iref)){
	  echo "Cannot open page ".$iref;
	  return;
      }

      $xpath = new DomXPath($domHTML);      
      $nodes = $xpath->query("//title", $domHTML->documentElement);
      $title = $nodes->item(0)->nodeValue;
      $sql = 'INSERT IGNORE INTO index_page 
		  (href, title, author, text)
	      VALUES
		  ("'.$fakeref.'",
		  "'.mysql_real_escape_string($title, $db).'",
		  NULL,
		  NULL)';	
      mysql_query($sql, $db) or die(mysql_error($db));
      $sql = 'SELECT id 
	  FROM 
	      index_page 
	  WHERE 
	      href="'.mysql_real_escape_string($fakeref, $db).'"';
      $result = mysql_query($sql, $db);
      if (mysql_num_rows($result) == 1) {
	  //ok
	  $row = mysql_fetch_array($result);
	  $id_index_page = $row['id'];
      }  else {
	  //errore    
      }
      mysql_free_result($result);
      
      
      
      
      //estraggo le sotto-sezioni
      $nodes = $xpath->query("//h1", $domHTML->documentElement);
      //echo $nodes->length;      
      
      $subNodes = array();
      $nodesText = array();
      for ($j=0; $j<$nodes->length-1; $j++) {       	  
	  $subNodes[] = $xpath->query("//body/blockquote/blockquote[position()=".($j+2)."]//a[contains(@href,'html') and not(contains(@href,'authors')) and not(contains(@href,'#'))]", $domHTML->documentElement);
	  $nodesText[] = $xpath->query("//body/blockquote/blockquote[position()=".($j+2)."]", $domHTML->documentElement);	
      }
           
            
      for ($j=0; $j<$nodes->length-1; $j++) {       
	  $singleNode = $nodes->item($j+1);
	  
	  $dom = new DomDocument();
	  for ($i=0; $i<$nodesText[$j]->length; $i++) {        
	      $singleNodeText = $nodesText[$j]->item($i);
	      //$p = $dom->createElement($singleNode->nodeName);    	  
	      $p = $dom->createElement("xml");
	      $GLOBALS['br'] = 0;
	      exploreIndexH2($dom, $p, $singleNodeText);	  
	      $dom->appendChild($dom->createElement("br"));
//	      $bq = $dom->createElement("blockquote");
//	      $bq->appendChild($p);
//	      $dom->appendChild($bq);
	       $dom->appendChild($p);
	  }
	  $text = $dom->saveHTML();
	  
	  
	  $sql = 'INSERT IGNORE INTO index_page 
		  (href, title, author, text)
	      VALUES
		  ("'.$fakeref.$j.'",
		  "'.mysql_real_escape_string($singleNode->nodeValue, $db).'",
		  NULL,
		  "'.mysql_real_escape_string($text, $db).'")';
		  
	   mysql_query($sql, $db) or die(mysql_error($db));	   
	   $sql = 'SELECT id 
	      FROM 
		  index_page 
	      WHERE 
		  href="'.mysql_real_escape_string($fakeref.$j, $db).'"';
	   $result = mysql_query($sql, $db);
	   if (mysql_num_rows($result) == 1) {
	      //ok
	      $row = mysql_fetch_array($result);
	      $id_target_index_page = $row['id'];
	   }  else {
	      //errore    
	   }  
	   mysql_free_result($result);
	   
	   $sql = 'INSERT IGNORE INTO index_2_content 
			    (id_start_index_page, id_target_index_page, link_name)
			VALUES
			    ("'.$id_index_page.'",
			    "'.$id_target_index_page.'",
			    "'.mysql_real_escape_string($singleNode->nodeValue, $db).'")';	
	    mysql_query($sql, $db) or die(mysql_error($db));
	    
	    
	    
	 //   $subNodes[] = $xpath->query("//blockquote/blockquote[position()=".($j+3)."]/a[contains(@href,'html') and not(contains(@href,'authors')) and not(contains(@href,'#'))]", $domHTML->documentElement);
	 //   $subNodes[] = $xpath->query("//blockquote[position()=".($j+3)."]/a[contains(@href,'html') and not(contains(@href,'authors')) and not(contains(@href,'#'))]", $domHTML->documentElement);
	      
	    //inizio parte comune
	    echo "j: ".($j+2)."<br/>";
	   
	    echo "--- subnodes: ".($subNodes[$j]->length)."<br/>";
	    for ($z=0; $z<$subNodes[$j]->length; $z++) {        	    
	    $singleSubNode = $subNodes[$j]->item($z);  
	    $ref = $singleSubNode->attributes->getNamedItem('href')->nodeValue;
	    $name = $singleSubNode->nodeValue;
	    if (!(in_array($ref, $GLOBALS['global']))) {
		  //inserisco il ref nell'array'
		  $GLOBALS['global'][] = $ref;
		  
		  // METTO QUI LA CHIAMATA A extractContentPage.php //
		  if (extractContent($ref, $db, $dom, $domHTML)){
			    
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
			      ("'.$id_target_index_page.'",
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
			  $id_new_target_index_page = $row['id'];
		      } else {
			  //errore    
		      }
		      mysql_free_result($result);


		      // ORA AGGIUNGO IL LINK TRA LE DUE PAGINE
		      $sql = 'INSERT IGNORE INTO index_2_content 
			      (id_start_index_page, id_target_index_page, link_name)
			  VALUES
			      ("'.$id_target_index_page.'",
			      "'.$id_new_target_index_page.'",
			      "'.mysql_real_escape_string($name, $db).'")';	
		      mysql_query($sql, $db) or die(mysql_error($db));

		  }
	    
		}
	    }
	    //fine parte comune
      }
      
      
      
      
}



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
			(href, title, author, text)
		    VALUES
			("'.$iref.'",
			"'.mysql_real_escape_string($title, $db).'",
			"'.$author.'",
			"'.mysql_real_escape_string($text, $db).'")';	
		mysql_query($sql, $db) or die(mysql_error($db));
	    }  else {
		//errore    
	    }
      } else {
	    $author = null;
	    $sql = 'INSERT IGNORE INTO index_page 
			(href, title, author, text)
		    VALUES
			("'.$iref.'",
			"'.mysql_real_escape_string($title, $db).'",
			NULL,
			"'.mysql_real_escape_string($text, $db).'")';	
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
	  
	  if (!(in_array($ref, $GLOBALS['global']))) {
		
		//inserisco il ref nell'array'
		$GLOBALS['global'][] = $ref;
		
		// METTO QUI LA CHIAMATA A extractContentPage.php //
		if (extractContent($ref, $db, $dom, $domHTML)){
			  
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



function extractContent($ref, $db, $dom, $domHTML){
      
      $dom = new DomDocument();
      // open if file exists
      if(!$domHTML->loadHTMLFile('./pandius.com/'.$ref)){
	echo "Cannot open page ".$ref;
	return;
      }
            
      $xpath = new DomXPath($domHTML);

      $nodes = $xpath->query("//p", $domHTML->documentElement);
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
	  $p = $dom->createElement('p');    	  
	  explore($dom, $p, $singleNode);	  
	  $dom->appendChild($p);
      }
      $text = $dom->saveHTML();
      
      //$nodes = $xpath->query("//h2", $domHTML->documentElement);
      $nodes = $xpath->query("//title", $domHTML->documentElement);
      $title = $nodes->item(0)->nodeValue;

      $nodes = $xpath->query("//a[contains(@href,'authors')]", $domHTML->documentElement);
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
      }  else {
	  //errore    
      }
      mysql_free_result($result);

      
      $sql = 'INSERT IGNORE INTO content_page 
	      (href, title, author, submit_date, is_published, text)
	  VALUES
	      ("'.$ref.'",
	      "'.mysql_real_escape_string($title, $db).'",
	      "'.$author.'",
	      "'.date('Y-m-d').'",
	      TRUE,
	      "'.mysql_real_escape_string($text, $db).'")';
      mysql_query($sql, $db) or die(mysql_error($db));
      
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
		      $fatherElement->appendChild($childElement);
		    }
		  }	     	      
	      }  
      }    
}

function cleanIndexTable($db){
    $sql="DELETE from index_page where (href!='resource.html' and menu='1' and href!='stories.html' and href!='adv_camp.html' and href!='atlas.html') or menu='0'";
    mysql_query($sql, $db);
}


echo 'success';

?>

