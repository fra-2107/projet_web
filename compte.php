<?php 
    //recuperation de la connexion a la bdd
    require_once("php/database.php");
    $erreur = "";
    $erreur_mdp = "";

    //lorsqu'on clique sur le boutton
    if(isset($_POST["btn-create"])) {

        // on verifie si tous les champs sont remplis
        if(empty($_POST["nom"]) || empty($_POST["prenom"]) || empty($_POST["age"]) || empty($_POST["mail"]) || empty($_POST["mdp"]) || empty($_POST["confirm-mdp"])) {
            $erreur = "Veuillez remplir tous les champs";
            
        } else {
            $nom = $_POST["nom"];
            $prenom = $_POST["prenom"];
            $age = $_POST["age"];
            $mail = $_POST["mail"];
            $mdp = $_POST["mdp"];
            $confirm_mdp = $_POST["confirm-mdp"];
        
            $con = dbConnect();

            if(!$con) {
                header("HTTP/1.1 503 Service Unavailable");
                exit;
            }
            
            
            $request = $con->prepare("
            INSERT INTO utilisateur (mail, nom, prenom, age, mdp)
            VALUES (:email, :nom, :prenom, :age, :mdp)
            ");

            $request->bindParam(':email', $mail);
            $request->bindParam(':nom', $nom);
            $request->bindParam(':prenom', $prenom);
            $request->bindParam(':age', $age);
            $request->bindParam(':mdp', $mdp);
            $request->execute();

            if ($mdp != $confirm_mdp) {
                $erreur_mdp = "Mot de passe incorrect";
            } else {
                header("Location:index_connexion.php");
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
    <form class="form_auth_1" method="POST">

    <div class="top_form">
        <?php

        //Gestion des erreurs
        if(isset($erreur_mdp)) {
            echo "<p class='erreur'>".$erreur_mdp."</p>";
        }

        if(isset($erreur)) {
            echo "<p class='erreur'>".$erreur."</p>";
        }
        ?>
        <input type="text" placeholder="Nom" name="nom">
        <input type="text" placeholder="Prénom" name="prenom">
        <input type="text" placeholder="Date naissance (exemple : 2023-06-06)" name="age">
        <input type="text" placeholder="Email" name="mail">
        <input type="password" placeholder="Mot de passe" name="mdp">
        <input type="password" placeholder="Confirmez mot de passe" name="confirm-mdp">
    </div>


    <div class="bottom_form">
            <a href="#"><button class="btn-create" name="btn-create">S'inscrire</button></a>
    </div>

    </form>
</body>
</html>