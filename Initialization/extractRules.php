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
cleanIndexTable($db);

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
		
        $sql = 'INSERT IGNORE INTO index_2_content
               (id_start_index_page, id_target_index_page, link_name)
               VALUES
               ("'.$id_index_page.'",
               "'.($id_target_index_page+$j).'",
               "'.mysql_real_escape_string($singleNode->nodeValue, $db).'")';
        mysql_query($sql, $db) or die(mysql_error($db));

        if($id_new_target_index_page==null) {
			echo "numero nodi h1: ".$nodes->length."</br>";
            $id_new_target_index_page = $id_target_index_page + $nodes->length;
        }

        echo $j."</br>";
        echo $singleNode->nodeValue."</br>";
        $nextNode = $singleNode;
        do {
            $nextNode = $nextNode->nextSibling;
        } while($nextNode->nodeName!="blockquote");
        foreach ($nextNode->childNodes as $childNode) {
            if($childNode->nodeName=="a" && $childNode->nodeValue!=null) {
                echo "articolo: ".$childNode->nodeValue."</br>";

                // TODO articolo: parso data, autori; esploro salvo content

            }
            if($childNode->nodeName=="h2") {
                echo "-level2: ".$childNode->nodeValue."</br>";

                // titolo di sottocategoria: salvo nell'indice
                $sql = 'INSERT IGNORE INTO index_page
                       (href, title, author, text)
                       VALUES
                       ("'.$iref.$j."-".$id_new_target_index_page.'",
                       "'.mysql_real_escape_string($childNode->nodeValue, $db).'",
                       NULL,
                       NULL)';
                mysql_query($sql, $db) or die(mysql_error($db));
				
				$childNode->nodeValue = str_replace("&","and",$childNode->nodeValue);
				
                $sql = 'INSERT IGNORE INTO index_2_content
                       (id_start_index_page, id_target_index_page, link_name)
                       VALUES
                       ("'.($id_target_index_page+$j).'",
                       "'.$id_new_target_index_page.'",
                       "'.mysql_real_escape_string($childNode->nodeValue, $db).'")';
                mysql_query($sql, $db) or die(mysql_error($db));
                $id_new_target_index_page++;

                do {
                    $childNode = $childNode->nextSibling;
                } while($childNode->nodeName!="blockquote");
                foreach ($childNode->childNodes as $subChildNode) {
                    if($subChildNode->nodeName=="a" && $subChildNode->nodeValue!=null) {
                        echo "--art: ".$subChildNode->nodeValue."</br>";

                        // TODO articolo sottocategoria: parso data, autori; esploro salvo content

                    }
                }
            }
        }
    }
}
?>