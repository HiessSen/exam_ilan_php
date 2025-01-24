<?php
// Page inaccessible si la personne est connectée
$file = file_get_contents('template/register.html');
echo $file;

if (isset($_POST['submit'])) {
    // On vérifie si les champs ont bien une valeur
    if (!empty($_POST['username']) && !empty($_POST['password']) && !empty($_POST['confirm-password'])) {
        // Je vérifie si les 2 password sont identiques
        if ($_POST['password'] == $_POST['confirm-password']) {
            $email = filter_var($_POST['username'], FILTER_SANITIZE_EMAIL);
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                // Vérification si l'email existe déjà
                $checkEmail = $dbh->prepare('SELECT COUNT(*) FROM utilisateurs WHERE email = :email');
                $checkEmail->bindValue(':email', $email, PDO::PARAM_STR);
                $checkEmail->execute();
                $emailExists = $checkEmail->fetchColumn();

                if ($emailExists == 0) {
                    // Je hashe le mot de passe
                    $hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                    $requete = $dbh->prepare('INSERT INTO utilisateurs (email, password) VALUES (:email, :password)');
                    $requete->bindValue(':email', $email, PDO::PARAM_STR);
                    $requete->bindValue(':password', $hashed_password, PDO::PARAM_STR);
                    if ($requete->execute()) {
                        $message = 'Utilisateur enregistré';
                        envoyerMailConfirmation($email);
                        genererToken();
                    } else {
                        $message = 'Erreur lors de l\'enregistrement';
                    }
                } else {
                    $message = "Cet email est déjà utilisé";
                }
            } else {
                $message = "Adresse email invalide";
            }
        } else {
            $message = "Les mots de passe ne correspondent pas";
        }
    } else {
        // Si tous les champs n'ont pas été remplis
        $message = "Veuillez remplir tous les champs";
    }
    echo $message;
}
?>