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
echo "----> RULES <----";
extractFirstPage('rules.html', $db, $dom, $domHTML);

require_once('common.php');
cleanDatabase($db);

echo "</br></br><b>FINISHPARSE</b>";

//**************************************************************************************************************//



function extractFirstPage($iref, $db, $dom, $domHTML) {
    $dom = new DomDocument();
    if(!$domHTML->loadHTMLFile('./pandius.com/'.$iref)) {
        echo "Cannot open page ".$iref;
        return;
    }
    $xpath = new DomXPath($domHTML);

    // inserisco nel db nome sezione e ricavo id
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

    $id_target_index_page = $id_index_page + 1;

    // sottosezione livello 1 nodi h1
    $nodes = $xpath->query("//h1[position()>1]", $domHTML->documentElement);

    // indice partenza per nodi h2
    $id_new_target_index_page = -1;

    // SOTTOSEZIONE PRINCIPALE

    for ($j=0; $j<$nodes->length; $j++) {
        $singleNode = $nodes->item($j);

        echo "</br></br>".$j."||</br>";
        $nextNode = $singleNode;
        do {
            $nextNode = $nextNode->nextSibling;
        } while($nextNode->nodeName!="blockquote");

		$GLOBALS['br'] = 4;
		$domInd = new DomDocument();
		$p = $domInd->createElement("xml");
		exploreIndex($domInd, $p, $nextNode);
		$domInd->appendChild($p);
        $text = $domInd->saveHTML();

        $sql = 'INSERT IGNORE INTO index_page
               (id, href, title, author, text)
               VALUES
			   ("'.($j+$id_index_page+1).'",
               "'.$iref.$j.'",
               "'.mysql_real_escape_string($singleNode->nodeValue, $db).'",
               NULL,
               "'.mysql_real_escape_string($text, $db).'")';
        mysql_query($sql, $db) or die(mysql_error($db));

        $sql = 'INSERT IGNORE INTO index_2_content
               (id_start_index_page, id_target_index_page, link_name)
               VALUES
               ("'.$id_index_page.'",
               "'.($id_target_index_page+$j).'",
               "'.mysql_real_escape_string($singleNode->nodeValue, $db).'")';
        mysql_query($sql, $db) or die(mysql_error($db));

        if($id_new_target_index_page==-1) {
            echo "numero nodi h1: ".$nodes->length."</br>";
            $id_new_target_index_page = $id_target_index_page + $nodes->length - 1;
        }

        for($z=0; $z<$nextNode->childNodes->length; $z++) {
            $childNode = $nextNode->childNodes->item($z);

            // ESTRAZIONE ARTICOLI SOTTOSEZIONE PRINCIPALE

            if($childNode->nodeName=="a" && !$childNode->attributes->getNamedItem("name")) {
                echo "-art: ".$childNode->nodeValue."</br>";
                $artHref = $childNode->attributes->getNamedItem("href")->nodeValue;
                $artName = $childNode->nodeValue;

                $z++;
                $childNode = $nextNode->childNodes->item($z);
                $artAuthor = array();
                $w = -1;
                $artText = "";
                while(!strstr($childNode->nodeValue, ".")) {
                    if($childNode->nodeName=="a") {
                        $artAuthor[$w++] = $childNode->nodeValue;
                    } else {
                        $artText = $artText.$childNode->nodeValue;
                    }
                    $z++;
                    $childNode = $nextNode->childNodes->item($z);
                }
                $artText = $artText.$childNode->nodeValue;

                if(strstr($artHref,".html")) {
                    $info = extractInfo($artText, $artName);
                    extractContent($artHref, $db, $dom, $domHTML, $info);
                } else {
                    $info = extractInfo($artText, $artName);
                    linkAtFile($artName, $artHref, $artAuthor, $info, $db);
                }
                // ricavo idContent e lo aggiungo all'index_2_content
                $sql = 'SELECT id
                       FROM
                       content_page
                       WHERE
                       href="'.mysql_real_escape_string($artHref, $db).'"';
                $result = mysql_query($sql, $db);
                if (mysql_num_rows($result) == 1) {
                    //ok
                    $row = mysql_fetch_array($result);
                    $id_content_page = $row['id'];
                }  else {
                    //errore
                }
                mysql_free_result($result);
                $sql = 'INSERT IGNORE INTO index_2_content
                       (id_start_index_page, id_target_content_page, link_name)
                       VALUES
                       ("'.$id_target_index_page.'",
                       "'.$id_content_page.'",
                       "'.mysql_real_escape_string($artName, $db).'")';
                mysql_query($sql, $db) or die(mysql_error($db));

            } else if($childNode->nodeName=="h2") {

                // SOTTOSEZIONE SECONDARIA

                echo "</br>-level2: ".$childNode->nodeValue."</br>";

                // la ecommerciale da' problemi, non viene caricato il nodo
                $childNode->nodeValue = str_replace("&","and",$childNode->nodeValue);
				
				$blockNode = $childNode;
				do {
                    $blockNode = $blockNode->nextSibling;
                } while($blockNode->nodeName!="blockquote");
				
				$domInd = new DomDocument();
				$p = $domInd->createElement("xml");
				exploreIndex($domInd, $p, $blockNode);
				$domInd->appendChild($p);
				$text = $domInd->saveHTML();

                // AGGIUNTA ALL'INDEXPAGE PER IL VIEW INDEX
                $id_new_target_index_page++;
                $sql = 'INSERT IGNORE INTO index_page
                       (id, href, title, author, text)
                       VALUES
					   ("'.$id_new_target_index_page.'",
                       "'.$iref.$j."-".$id_new_target_index_page.'",
                       "'.mysql_real_escape_string($childNode->nodeValue, $db).'",
                       NULL,
                       "'.mysql_real_escape_string($text, $db).'")';
                mysql_query($sql, $db) or die(mysql_error($db));

                $sql = 'INSERT IGNORE INTO index_2_content
                       (id_start_index_page, id_target_index_page, link_name)
                       VALUES
                       ("'.($id_target_index_page+$j).'",
                       "'.$id_new_target_index_page.'",
                       "'.mysql_real_escape_string($childNode->nodeValue, $db).'")';
                mysql_query($sql, $db) or die(mysql_error($db));                

                // estrazione articoli livello h2
                extractArticlesH2($blockNode, $db, $dom, $domHTML, $id_new_target_index_page);
            }
        }
    }
}

function extractArticlesH2($nextNode, $db, $dom, $domHTML, $id_new_target_index_page) {
    for($z=0; $z<$nextNode->childNodes->length; $z++) {
        $childNode = $nextNode->childNodes->item($z);
        if($childNode->nodeName=="a" && !$childNode->attributes->getNamedItem("name")) {
            echo "-art: ".$childNode->nodeValue."</br>";
            $artHref = $childNode->attributes->getNamedItem("href")->nodeValue;
			
			// la ecommerciale da' problemi, non viene caricato il nodo
			$childNode->nodeValue = str_replace("&","and",$childNode->nodeValue);
            $artName = $childNode->nodeValue;

            $z++;
            $childNode = $nextNode->childNodes->item($z);
            $artAuthor = array();
            $w = -1;
            $artText = "";
            while(!strstr($childNode->nodeValue, ".")) {
                if($childNode->nodeName=="a") {
                    $artAuthor[$w++] = $childNode->nodeValue;
                } else {
                    $artText = $artText.$childNode->nodeValue;
                }
                $z++;
                $childNode = $nextNode->childNodes->item($z);
            }
            $artText = $artText.$childNode->nodeValue;

            // ARTICOLI BLOCCANTI
            if($artHref == "chron.html") {
                break;
            }

            if(strstr($artHref,".html")) {
                $info = extractInfo($artText, $artName);
                extractContent($artHref, $db, $dom, $domHTML, $info);
            } else {
                $info = extractInfo($artText, $artName);
                linkAtFile($artName, $artHref, $artAuthor, $info, $db);
            }
            // ricavo idContent e lo aggiungo all'index_2_content
            $sql = 'SELECT id
                   FROM
                   content_page
                   WHERE
                   href="'.mysql_real_escape_string($artHref, $db).'"';
            $result = mysql_query($sql, $db);
            if (mysql_num_rows($result) == 1) {
                //ok
                $row = mysql_fetch_array($result);
                $id_content_page = $row['id'];
            }  else {
                //errore
            }
            mysql_free_result($result);
            $sql = 'INSERT IGNORE INTO index_2_content
                   (id_start_index_page, id_target_content_page, link_name)
                   VALUES
                   ("'.$id_new_target_index_page.'",
                   "'.$id_content_page.'",
                   "'.mysql_real_escape_string($artName, $db).'")';
            mysql_query($sql, $db) or die(mysql_error($db));
        }
    }
}

function linkAtFile($title, $ref, $author, $info, $db) {
    $text = '</br><a href="'.$ref.'">LINK FILE<a>';
    if($info['from'] != null && (strlen($info['from']) > 25) || strstr($info['from'],"by")) {
        echo "FROM FIELD DELETED<br/>";
        $info['from'] = null;
    }
    if($info['date'] != null && $info['from'] != null) {
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
    } else if($info['date'] != null && $info['from'] == null) {
        $sql = 'INSERT IGNORE INTO content_page
               (href, title, submit_date, publish_date, is_published, text)
               VALUES
               ("'.$ref.'",
               "'.mysql_real_escape_string($title, $db).'",
               "'.date('Y-m-d').'",
               "'.$info['date'].'",
               TRUE,
               "'.mysql_real_escape_string($text, $db).'")';
    } else {
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
    foreach($author as $name) {
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
}

function extractInfo($article,$name) {
    $info = array( 'from' => null,'date' => null);
    if(strstr($article,'from') && strstr($article,'posted')) {
        /*DEBUG*/$fullName = trim(substr($article,0,strpos($article,"from")));
        $info['from'] = trim(substr($article,strpos($article,"from")+4,(strpos($article,"posted")-strpos($article,"from")-4)));
        $info['date'] = date('Y-m-d',strtotime(trim(substr($article,strpos($article,"posted")+6))));
        if(strstr($info['from'],'from') || strstr($info['from'],'posted')) {
            if(strstr($info['from'],"the Mystara Message Board")) {
                $info['from'] = "the Mystara Message Board";
            } else if(strstr($info['from'],"The Piazza")) {
                $info['from'] = "The Piazza";
            } else if(strstr($info['from'],"the Mystara Mailing List")) {
                $info['from'] = "the Mystara Mailing List";
            }
        }
    } else if(strstr($article,'from') && !strstr($article,'posted')) {
        if(strstr($article,'current as of')) {
            /*DEBUG*/$fullName = trim(substr($article,0,strpos($article,"from")));
            $info['from'] = trim(substr($article,strpos($article,"from")+4,(strpos($article,"current as of")-strpos($article,"from")-4)));
            $info['date'] = date('Y-m-d',strtotime(trim(substr($article,strpos($article,"current as of")+13))));
        } else {
            /*DEBUG*/$fullName = trim(substr($article,0,strpos($article,"from")));
            $from = trim(substr($article,strpos($article,"from")+4));
            if(strstr($from,"the Mystara Message Board")) {
                $date = substr($from,25);
            } else if(strstr($from,"The Piazza")) {
                $date = substr($from,10);
            } else if(strstr($from,"the Mystara Mailing List")) {
                $date = substr($from,24);
            }
            $info['from'] = substr($from,0,(strlen($from)-strlen($date)));
            $info['date'] = date('Y-m-d',strtotime(trim($date)));
        }
    } else if(strstr($article,'current as of')) {
        /*DEBUG*/ $fullName = trim(substr($article,0,strpos($article,"current as of")));
        $info['date']= date('Y-m-d',strtotime(trim(substr($article,strpos($article,"current as of")+13))));
    } else if(strstr($article,'last section updated')) {
        /*DEBUG*/ $fullName = trim(substr($article,0,strpos($article,"last section updated")));
        $info['date']= date('Y-m-d',strtotime(trim(substr($article,strpos($article,"last section updated")+20))));
    }

    /*DEBUG*/ echo "<b>name:</b> ".$name." <b>from:</b> ".$info['from']." <b>date:</b> ".$info['date']."<br/>";

    return $info;
}

function extractContent($ref, $db, $dom, $domHTML, $info) {

    $dom = new DomDocument();
    // open if file exists
    if(!$domHTML->loadHTMLFile('./pandius.com/'.$ref)) {
        echo "Cannot open page ".$ref;
        return;
    }

    $xpath = new DomXPath($domHTML);

    $nodes = $xpath->query("//p | //ul  | //ol | //table", $domHTML->documentElement);
    $nodesA = $xpath->query("//a[contains(@href,'html') and not(contains(@href,'authors')) and not(contains(@href,'#'))]", $domHTML->documentElement);

//      echo 'CONTENT: P = '.$nodes->length.', A = '.$nodesA->length.' --> '.$ref.'<br/>';

    for ($i=1; $i<$nodes->length; $i++) {
        $singleNode = $nodes->item($i);
        if($singleNode->nodeName== "p") {
            $p = $dom->createElement('p');
            explore($dom, $p, $singleNode);
            $dom->appendChild($p);
        } else if($singleNode->nodeName == "ul") {
            $ul = $dom->createElement('ul');
            explore($dom, $ul, $singleNode);
            $dom->appendChild($ul);
        } else if($singleNode->nodeName == "ol") {
            $ol = $dom->createElement('ol');
            explore($dom, $ol, $singleNode);
            $dom->appendChild($ol);
        } else if($singleNode->nodeName == "table") {
            $table = $dom->createElement('table');
            explore($dom, $table, $singleNode);
            $dom->appendChild($table);
        }
    }
    $text = $dom->saveHTML();

    //$nodes = $xpath->query("//h2", $domHTML->documentElement);
    $nodes = $xpath->query("//title", $domHTML->documentElement);
    $title = $nodes->item(0)->nodeValue;
	
	// la ecommerciale da' problemi, non viene caricato il nodo
	$title = str_replace("&","and",$title);

    //aggiungo l'articolo
    //filtro il campo from per eliminare falsi positivi
    if($info['from'] != null && (strlen($info['from']) > 25) || strstr($info['from'],"by")) {
        echo "FROM FIELD DELETED<br/>";
        $info['from'] = null;
    }
    if($info['date'] != null && $info['from'] != null) {
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
    } else if($info['date'] != null && $info['from'] == null) {
        $sql = 'INSERT IGNORE INTO content_page
               (href, title, submit_date, publish_date, is_published, text)
               VALUES
               ("'.$ref.'",
               "'.mysql_real_escape_string($title, $db).'",
               "'.date('Y-m-d').'",
               "'.$info['date'].'",
               TRUE,
               "'.mysql_real_escape_string($text, $db).'")';
    } else {
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

    //controlla autori ripetuti e inserisce nel db
    require_once('common.php');
    addAuthors($nodes, $db, $lastInseredContent);

    return true;
}

function explore($dom, $fatherElement, $fatherNode) {

    foreach ($fatherNode->childNodes as $childNode) {
        if($childNode->hasChildNodes()) {
            $childElement = $dom->createElement($childNode->nodeName);
            if($childNode->hasAttributes()) {
                foreach ($childNode->attributes as $attribute) {
                    $childElement->setAttribute($attribute->name, $attribute->value);
                }
            }
            explore($dom, $childElement, $childNode);
            $fatherElement->appendChild($childElement);
        } else {
            //allora è un nodo testo
            if ($childNode->nodeType == 3) { //costante del tipo testo
                $childElement = $dom->createTextNode($childNode->nodeValue);
                $fatherElement->appendChild($childElement);
            } else {
                //o un nodo senza figli
                if ($childNode->nodeType != 8) {
                    //i commenti mi danno problemi quindi non li considero
                    $childElement = $dom->createElement($childNode->nodeName);
                    // aggiunta del campo src ai tag img
                    if($childNode->nodeName == "img") {
                        foreach ($childNode->attributes as $attribute) {
                            $childElement->setAttribute($attribute->name, "data/".$attribute->value);
                        }
                        // incapsulo in <a> le immagini articolo che non lo sono
                        if($fatherElement->nodeName=="p" && $fatherNode->childNodes->length==1) {
                            $aHref = $dom->createElement("a");
                            $aHref->setAttribute("href", $attribute->value);
                            $aHref->setAttribute("target", "_blank");
                            $childElement->setAttribute("style", "max-width:100%; max-height:100%;");
                            $aHref->appendChild($childElement);
                            $childElement = $aHref;
                        }
                        if($fatherElement->nodeName=="a") {
                            $fatherElement->setAttribute("target", "_blank");
                        }
                    }
                    $fatherElement->appendChild($childElement);
                }
            }
        }
    }
}

function exploreIndex($dom, $fatherElement, $fatherNode) {

    foreach ($fatherNode->childNodes as $childNode) {
        if ($childNode->nodeName != "center" && $childNode->nodeName != "small") {
            if($childNode->hasChildNodes()) {
                if ($childNode->nodeName == "a" && strpos("dkaj".$childNode->attributes->getNamedItem("href")->nodeValue, "authors.html")) {
                    exploreIndex($dom, $fatherElement, $childNode);
                    continue;
                }
                if ($childNode->nodeName == "a") {
                    $childElement = $dom->createElement($childNode->nodeName);
                    if($childNode->hasAttributes()) {
                        foreach ($childNode->attributes as $attribute) {
                            $childElement->setAttribute($attribute->name, "#");
                            $childElement->setAttribute("onclick", "linkTo('".$attribute->value."'); return false");
                        }
                    }
                    exploreIndex($dom, $childElement, $childNode);
                    $fatherElement->appendChild($childElement);
                } else {
                    $childElement = $dom->createElement($childNode->nodeName);
                    if($childNode->hasAttributes()) {
                        foreach ($childNode->attributes as $attribute) {
                            $childElement->setAttribute($attribute->name, $attribute->value);
                        }
                    }
                    exploreIndex($dom, $childElement, $childNode);
                    $fatherElement->appendChild($childElement);
                }
            } else {
                //allora è un nodo testo
                if ($childNode->nodeType == 3) { //costante del tipo testo
                    $childElement = $dom->createTextNode($childNode->nodeValue);
                    $fatherElement->appendChild($childElement);
                } else {
                    //o un nodo senza figli
                    if ($childNode->nodeType != 8) {
                        //i commenti mi danno problemi quindi non li considero
                        if ($childNode->nodeName == "br") {
                            $GLOBALS['br']--;
                            if ($GLOBALS['br'] < 0) {
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
?>