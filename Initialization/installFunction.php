<?php
function cleanIndexTable($db){
    $sql="DELETE from index_page where (href!='resource.html' and menu='1' and href!='stories.html' and href!='adv_camp.html' and href!='atlas.html') or menu='0'";
    mysql_query($sql, $db);
}

?>
