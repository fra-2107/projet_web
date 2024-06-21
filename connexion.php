<?php

require_once("php/database.php"); // on recupere le fichier qui connecte la bdd

$erreur = "";

if(isset($_POST["check-btn"])) {

    if(isset($_POST['email']) && isset($_POST['pwd'])) { // on verifie si l'utilisateur a bien rentré des donnée

        $email = $_POST['email'];
        $pwd = $_POST['pwd'];

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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/style.css">
    <title>Projet web - connexion</title>
</head>
<body>
    <div class="page">
        
        <div class="form">
            <form class="form_auth_1" method="POST" action="">

                <div class="top_form">
                    <h2>Connectez-vous</h2>

                    <?php 

                    //Gestion des erreurs
                    if(isset($erreur)) {
                        echo "<p class='erreur'>".$erreur."</p>";
                    }
                    ?>

                    <input type="text" placeholder="Email" name="email">
                    <input type="text" placeholder="Mot de passe" name="pwd">
                    <button  name="check-btn">Connexion</button>
                </div>

                <hr>
            </form>

            <div class="bottom_form">
                <p>Vous n'avez pas de compte ?</p>
                <a href="create_account.php"><button class="btn-create">Créer un compte</button></a>
            </div>
        </div>
    </div>
</body>
</html>