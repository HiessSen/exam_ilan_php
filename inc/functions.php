<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';
// Vos fonctions (token, traitement des fichiers etc...)
function envoyerMailConfirmation($emailF)
{
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;                                  //Enable verbose debug output
        $mail->isSMTP();                                                        //Send using SMTP
        $mail->Host       = 'dwwm2425.fr';                                      //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                               //Enable SMTP authentication
        $mail->Username   = 'contact@dwwm2425.fr';                              //SMTP username
        $mail->Password   = '!cci18000Bourges!';                                //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;                        //Enable implicit TLS encryption
        $mail->Port       = 465;                                                //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom('contact@dwwm2425.fr');
        $mail->addAddress($emailF, 'Toujours Ilan SENOUCI');
        $mail->addAddress('ilansenouci@gmail.com');
        $mail->addReplyTo(strip_tags($emailF));

        //Content
        $mail->isHTML(true);                                             //Set email format to HTML
        $mail->Subject = 'Si tu reçoit ça c\'est que mon devoir avance bien';
        $mail->Body    = '<a href="http://localhost/cci_php/devoir_ilan_php/examphp-main/?route=login&confirm=1">Connexion</a>';
        $mail->AltBody = 'Veuillez noter qu\e vous êtes bel et bien inscrit !!';

        $mail->send();
        echo 'Message has been sent';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
function genererToken()
{
    // Je crée une chaine de caractère pour le token
    $chaine = 'azertyuiopqsdfghjklmwxcvbnAZERTYUIOPQSDFGHJKLMWXCVBN0123456789&é"(-è_çà/*-+=*!§:/;.,?<>¤€`]°+}@^\|[{#~';
    // Je transforme la chaine en tableau pour des raisons d'encodage;
    $tableau = mb_str_split($chaine);
    // Je calcule la longueur de la chaine de caractère avec strlen()
    $longueur = count($tableau);
    // J'initialise une variable Token vide
    $token = '';
    // Je génère une clé aléatoire avec une boucle for avec une longueur comprise entre 16 et 30
    for ($i = 0; $i < rand(16, 30); $i++) {
        $token .= $tableau[rand(0, $longueur - 1)];
    }
    // Je hashe le token
    $token = md5(sha1($token));
    // J'enregistre mon token dans une session
    $_SESSION['token'] = $token;
    // Une fois le token terminé, je le retourne
    return $token;
}
// Je crée une fonction qui verifie le token
function verifierToken($cle)
{
    if ($_SESSION['token'] === $cle) {
        return true;
    }else{
        return false;
    }
}