<?php
if(!empty($_FILES))
{
    $size = $_FILES['fichier']['size'];
    if($size>0)
    {
        require('./includes/connexion_db.php');
        $fileName = $_FILES['fichier']['name'];
        $fileExtension = strrchr($fileName,".");
        $allowedExtentions= array('.pdf','.PDF');
        if(in_array($fileExtension,$allowedExtentions))
        {
            function nbr_pages($pdf){
                if (false !== ($fichier = file_get_contents($pdf))){
                   $pages = preg_match_all("/\/Page\W/", $fichier, $matches);
                   return $pages;
                }
            }
            $file_tpt_name=$_FILES['fichier']['tmp_name'];
            $destinationFile = 'files/'.$fileName;
            $countPages = nbr_pages($file_tpt_name);
            if(move_uploaded_file($file_tpt_name,$destinationFile))
            {
                try
                {
                    $req=$db->prepare('INSERT INTO tableTest(name,path,nbrPages) VALUES(?,?,?)');
                    $req->execute(array($fileName,$destinationFile,$countPages));
                    echo '<br>','reference du fichier envoyer avec succès dans la base de donnée';
                }
                catch(Exception $e)
                {
                    echo '<br>','erreur lors de l\envoi des references dans la base de donnée','<br>';
                    die($e->getMessage());
                }
            }
            else
            {
                echo '<br/>','erreur lors de l\'envoi dans le repertoire';
            }
        }
        else
        {
            echo '<br/>';
            echo 'SEUL LES FICHIERS PDF SONT AUTORISES';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>UPLOAD DE FICHIER TEST</title>
        <meta charset="utf-8" />
        <link rel="stylesheet" href="./styles.css">
    </head>
    <body>
        <h1 id="title">TEST D'ENVOI DE FICHIER PDF</h1>
        <hr/>
        <div id="formulaire">
            <form method="POST" enctype="multipart/form-data">
                <input type="file" name="fichier" />
                <input type="submit" value="envoyer le fichier" />
            </form>
        </div>
    </body>
</html>