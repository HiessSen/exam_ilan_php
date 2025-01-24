<?php
// Page inaccessible si la personne est connectée
$file = file_get_contents('template/login.html');
echo $file;
if (isset($_POST['submit'])) {
    // On vérifie si les champs ont bien une valeur
    if (!empty($_POST['username']) && !empty($_POST['password'])) {
        $email = filter_var($_POST['username'], FILTER_SANITIZE_EMAIL);
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // Vérification des informations de connexion
            $requete = $dbh->prepare('SELECT password FROM utilisateurs WHERE email = :email');
            $requete->bindValue(':email', $email, PDO::PARAM_STR);
            $requete->execute();
            $result = $requete->fetch(PDO::FETCH_ASSOC);
            if ($result && password_verify($_POST['password'], $result['password'])) {
                // Connexion réussie
                $message = 'Connexion réussie';
                // Démarrer une session ou définir des cookies ici
                session_start();
                $_SESSION['user'] = $email;
                header('Location: index.php');
            } else {
                // Informations de connexion incorrectes
                $message = 'Email ou mot de passe incorrect';
            }
        } else {
            $message = "Adresse email invalide";
        }
    } else {
        // Si tous les champs n'ont pas été remplis
        $message = "Veuillez remplir tous les champs";
    }
    echo $message;
}
?>