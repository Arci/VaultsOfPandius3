<?php

define(UP_DIR,"./data/");

class Upload{

    /*
     *$error = array di stringhe di errore
    */
    static public function displayUploadForm($error = null){
            ?>
            <form  name="upload" action="uploadFile.php" method="post" enctype="multipart/form-data">
                    <div class="editprofile">
                         Carica un file:
                         <br /><br />
                         <input type='file' name='upfile' /><br />
                         <br/>
                     <input type="submit" value="Upload">
                        <?php
                        if(isset($error)){
                                ?> <div id="error"> <?php
                                    echo "<br /><span style=\"color: red\">$error</span>";
                                ?> </div> <?php
                        } ?>
                 </form>
            <?php
	}
    /*
     *@param url immagine
     *@return array[width, height]
    */
    public function getSize($image){
        list($width,$height) = getimagesize($source);
        $size = array("width" => $width,
                      "height" => $height);
        return $size;
    }
    
    /**
    * Salva in /data l'immagine
    * @param fname: nome del file
    * @param tmp_name: nome temporaneo attribuito dal server all'imamgine
    */
    static public function uploadPhoto($fname,$tmp_name,$mime){
        //tronco il nome file a max 50 caratteri
        $fname = self::truncateFileName($fname);
        //salvo il file nella cartella data/
        $path = self::generatePath($fname);
        if(@is_uploaded_file($tmp_name)){
              @move_uploaded_file($tmp_name,$path)
              or die("Impossibile spostare il file $fname con tmp_name: $tmp_name in: $path");
        }
        $path = substr($path,7);
        return $path;
    }
    
    /**
    * Tronca a 50 caratteri il nome di un file mantenendone l'estensione e filtra alcuni caratteri
    * @param fname: nome da troncare
    * @return: il nome troncato
    */
    private function truncateFileName($fname){
        if(strlen($fname) > 50){
                $string=explode(".", $fname); //$string[0] = file name, $string[1]= extension
                $fname = substr($fname,0,49) . "." . $string[1];	
        }
        $fname = str_replace('?','',$fname);
        $fname = str_replace('#','',$fname);
        $fname = str_replace(' ','_',$fname);
        return trim($fname);
    }
    
    /**
    *Genera un path relativo per il salvataggio dell'imamgine $fname
    *@param fname: nome dell'imamgine
    *@return: data/nomefile.jpg
    *Se il file esiste giˆ genera n nome casuale aggiungendo un numero in fondo al nome del file
    */
    private function generatePath($fname){
        if(!file_exists(UP_DIR."$fname")){
            $path= UP_DIR."$fname";
            return $path;
        }else{
            $i=1;
            do{
                $string=explode(".", $fname); //$string[0] = file name, $string[1]= extension
                $editedfname=$string[0]. $i . "." . $string[1];
                $i++;
            }while(file_exists(UP_DIR."$editedfname"));
            $path=UP_DIR."$editedfname";
            return $path;
        }
    }
}
?>