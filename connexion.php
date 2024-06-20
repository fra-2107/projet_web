<?php

require_once("php/database.php"); // on recupere le fichier qui connecte la bdd

$erreur = "";

if(isset($_POST["check-btn"])) {

    if(isset($_POST['mail']) && isset($_POST['mdp'])) { // on verifie si l'utilisateur a bien rentré des donnée

        $email = $_POST['mail'];
        $pwd = $_POST['mdp'];

        $con = dbConnect();

        if(!$con) {
            header("HTTP/1.1 503 Service Unavailable");
            exit;
        }

        $request = $con->prepare("SELECT * FROM utilisateur WHERE mail=? AND mdp=?");
        $request->bindParam(1, $email);
        $request->bindParam(2, $pwd);
        $request->execute();

        $num_ligne = $request->rowCount(); // compter le nb de ligne ayant un rapport avec la requete sql
        if ($num_ligne > 0) {
            session_start();
            $_SESSION['mail'] = $email;
            header("Location:index_accueil.html");
        } else {
            $erreur = "Adresse mail ou mot de passe incorrect !";
        }
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
</head>
<body>
    <form method="POST" action="">
        <input type="text" name="mail">
        <input type="password" name="mdp">
        <button name="btn_connect">Se connecter</button>
    </form>
</body>
</html>