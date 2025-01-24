<?php
$fichier = file_get_contents('template/upload.html');
echo $fichier;
// Je verifie si le formulaire a été envoyé
if(isset($_POST['submit']))
{
    // Je verifie si un fichier a été envoyé
    if(is_uploaded_file($_FILES['file']['tmp_name']))
    {
        // Je déplace le fichier vers le dossier Upload
        if(move_uploaded_file($_FILES['file']['tmp_name'], 'upload/' . 'test')){
            echo 'Oui oui oui';
        }
    }
    else
    {
        echo 'Non non non';
    }
}
?>