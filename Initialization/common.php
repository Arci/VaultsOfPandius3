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

function cleanIndexTable($db){
    $sql="DELETE from index_page where (href!='resource.html' and menu='1' and href!='stories.html' and href!='adv_camp.html' and href!='atlas.html' and href!='rules.html') or menu='0'";
    mysql_query($sql, $db);
    $sql="DELETE from content_page_author where contentPage=0";
    mysql_query($sql, $db);
}
