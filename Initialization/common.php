<?php

function fixUser($name){
    if($name == "Ville V Lähde"){ $name = "Ville Lähde";}
    if($name == "hapyplarry"){ $name = "happylarry";}
    if($name == "Morphius Shadowleaf"){ $name = "Mo-rphius Shadowleaf";}
    if($name == "George E. Hrabovsky"){ $name = "George Hrabovsky";}
    if($name == "James Rhuland"){ $name = "James Ruhland";}
    if($name == "Simone"){ $name = "Simone Neri";}
    if($name == "Zarcyk"){ $name = "Zaryck";}
    if($name == "Mischa Gelman"){ $name = "Mischa E Gelman ";}
    if($name == "Giulio N Caroletti"){ $name = "Giulio Caroletti";}
    if($name == "Giulio N. Caroletti"){ $name = "Giulio Caroletti";}
    if($name == "Roger LaVern Girtman, II"){ $name = "Roger LV Girtman II";}
    if($name == "George E. Hrabovsky"){ $name = "George Hrabovsky";}
    if($name == "The Stalker"){ $name = "Jens \"the Stalker\" Schnabel";}
    if($name == "Steven B Wilson"){ $name = "Steven B. Wilson";}
    if($name == "Joe Not Charles"){ $name = "Joenotcharles";}
    if($name == "JTR"){ $name = "Old Dawg";}
    if($name == "Ville V Lähde"){ $name = "Ville Lähde";}
    if($name == "Wolfgang Nickel"){ $name = "Wolfgang Neckel";}
    if($name == "Larry Lamb"){ $name = "Larry E. Lamb";}
    if($name == "Luke Maximillian McCal"){ $name = "L. Maximillian McCal";}
    if($name == "David Leland"){ $name = "David S. Leland";}
    if($name == "Katana One"){ $name = "katana_one";}
    if($name == "Mmoangle"){ $name = "MMonagle";}
    if($name == "Beau Yarbrough"){ $name = "Beau Yarbrough";}
    if($name == "Paul George Dooley"){ $name = "Paul Dooley";}
    return $name;
}

function addAuthors($nodes, $db, $lastInseredContent){
    if($nodes->length < 1){
        echo "UNKNOWN AUTHOR<br/>";
	insertAuthor("Unknown", $db, $lastInseredContent);
	return;
    }
    if($nodes->length==1){
            echo $nodes->item(0)->nodeValue."<br/>";
            insertAuthor($nodes->item(0)->nodeValue, $db, $lastInseredContent);
            return;
    }
    $authorList = array();
    for($i=0; $i<($nodes->length-1); $i++) {
        for($g=$i+1, $diff=0 ; $g<$nodes->length; $g++) {
            if($nodes->item($i)->nodeValue != $nodes->item($g)->nodeValue) {
                $diff++;
            }
        }
        if($nodes->length-$diff==$i+1) {
                        echo $nodes->item($i)->nodeValue."<br/>";
			insertAuthor($nodes->item($i)->nodeValue, $db, $lastInseredContent);
        }
        //se all'ultimo giro non ci sono differenze è un array di autori ugulai
        //ne aggiungo uno
        if($i == ($nodes->length-2 ) && $diff == 0){
            echo $nodes->item(0)->nodeValue."<br/>";
            insertAuthor($nodes->item($i)->nodeValue, $db, $lastInseredContent);
        }
    }
    if($nodes->item($i)->nodeValue != $nodes->item($i-1)->nodeValue) {
        	echo $nodes->item($i)->nodeValue."<br/>";
		insertAuthor($nodes->item($i)->nodeValue, $db, $lastInseredContent);
    }
    return;
}

function insertAuthor($name, $db, $lastInseredContent){
        //fix degli utenti che danno problemi
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

function cleanIndexTable($db){
    $sql="DELETE from index_page where (href!='resource.html' and menu='1' and href!='stories.html' and href!='adv_camp.html' and href!='atlas.html' and href!='rules.html') or menu='0'";
    mysql_query($sql, $db);
    $sql="DELETE from content_page_author where contentPage=0";
    mysql_query($sql, $db);
     $sql="UPDATE `content_page` SET `source` = 'the Mystara Message Board' WHERE `source` LIKE '%the%Mystara%Message%Board%'";
    mysql_query($sql, $db);
    $sql="UPDATE `content_page` SET `source` = 'the Mystara Mailing List' WHERE `source` LIKE '%the%Mystara%Mailing%List%'";
    mysql_query($sql, $db);
}

?>