<?php 
// récupération de la connexion à la bdd
require_once("php/database.php");
$erreur = "";
$erreur_mdp = "";

// lorsqu'on clique sur le bouton
if(isset($_POST["btn-create"])) {

    // on vérifie si tous les champs sont remplis
    if(empty($_POST["nom"]) || empty($_POST["prenom"]) || empty($_POST["age"]) || empty($_POST["mail"]) || empty($_POST["mdp"]) || empty($_POST["confirm-mdp"])) {
        $erreur = "Veuillez remplir tous les champs";
    } else {
        $nom = $_POST["nom"];
        $prenom = $_POST["prenom"];
        $age = $_POST["age"];
        $mail = $_POST["mail"];
        $mdp = $_POST["mdp"];
        $confirm_mdp = $_POST["confirm-mdp"];

        // vérification que les mots de passe correspondent
        if ($mdp != $confirm_mdp) {
            $erreur_mdp = "Les mots de passe ne correspondent pas";
        } elseif (!is_numeric($age) || intval($age) <= 0) {
            $erreur = "L'âge doit être un nombre entier positif";
        } else {
            $age = intval($age); // convertir l'âge en entier

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

            try {
                $request->execute();
                header("Location:index_connexion.php");
                exit;
            } catch (PDOException $e) {
                $erreur = "Erreur lors de l'inscription : " . $e->getMessage();
            }
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
    <link rel="stylesheet" href="assets/css/style.css">
    <title>Projet web - nouveau compte</title>
</head>
<body>
    <!-- Formulaire pour créer un nouveau compte -->

    <div class="page">

        <h1>Créer un compte</h1>

        <div class="form">
            <form class="form_auth_1" method="POST">

                <div class="top_form">
                    <?php
                    // Gestion des erreurs
                    if($erreur_mdp != "") {
                        echo "<p class='erreur'>".$erreur_mdp."</p>";
                    }

                    if($erreur != "") {
                        echo "<p class='erreur'>".$erreur."</p>";
                    }
                    ?>
                    <input type="text" placeholder="Nom" name="nom">
                    <input type="text" placeholder="Prénom" name="prenom">
                    <input type="text" placeholder="Date de naissance (exemple : 2023-06-06)" name="age">
                    <input type="text" placeholder="Email" name="mail">
                    <input type="password" placeholder="Mot de passe" name="mdp">
                    <input type="password" placeholder="Confirmez mot de passe" name="confirm-mdp">
                </div>

                <div class="bottom_form">
                    <button class="btn-create" name="btn-create">S'inscrire</button>
                </div>

            </form>
        </div>
    </div>

    <!-- fin du formulaire -->
</body>
</html>
