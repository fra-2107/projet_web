<?php
/**
 * @Author: Thibault Napoléon <Imothep>
 * @Company: ISEN Yncréa Ouest
 * @Email: thibault.napoleon@isen-ouest.yncrea.fr
 * @Created Date: 29-Jan-2018 - 16:48:46
 * @Last Modified: 08-Dec-2019 - 15:56:37
 */

    require_once('database.php');

    // Database connexion.
    $db = dbConnect();
    if (!$db)
    {
        header ('HTTP/1.1 503 Service Unavailable');
        exit;
    }

    // Check the request.
    $requestMethod = $_SERVER['REQUEST_METHOD'];
    $request = $_SERVER['PATH_INFO'];
    $request = explode('/', $request);

    if ($request[1] != 'arbres')
    {
        header('HTTP/1.1 400 Bad Request');
        exit;
    }
    $id=$request[2];

    if ($requestMethod == 'GET')
    {
        if(isset($_GET['login']))
            $data = dbRequestArbres($db, $_GET['login']);
        else 
            $data = dbRequestArbres($db);
    }
  
    if ($requestMethod == 'POST'){
        // Vérification de l'existence de tous les paramètres requis
        $requiredFields = ['espece', 'haut_tot', 'haut_tronc', 'diam_tronc', 'lat', 'longi', 'fk_arb_etat', 'fk_stadedev', 'fk_port', 'fk_pied'];
        $data = [];
        $errors = [];
    
        foreach ($requiredFields as $field) {
            if (isset($_POST[$field])) {
                // Nettoyage et assignation des valeurs
                $data[$field] = strip_tags($_POST[$field]);
            } else {
                $errors[] = "Le champ $field est manquant.";
            }
        }
    
        if (empty($errors)) {
            // Validation des données, par exemple s'assurer que les valeurs sont des nombres valides
            if (!is_numeric($data['haut_tot']) || !is_numeric($data['haut_tronc']) || !is_numeric($data['diam_tronc']) ||
                !is_numeric($data['lat']) || !is_numeric($data['longi']) ||
                !is_numeric($data['fk_arb_etat']) || !is_numeric($data['fk_stadedev']) ||
                !is_numeric($data['fk_port']) || !is_numeric($data['fk_pied'])) {
                $errors[] = "Tous les champs numériques doivent contenir des valeurs valides.";
            }
    
            if (empty($errors)) {
                // Appel de la fonction pour ajouter les données à la base
                $result = dbAddArbre($db, $data);
                
                if ($result) {
                    echo "L'arbre a été ajouté avec succès. ID : " . $result;
                } else {
                    echo "Erreur lors de l'ajout de l'arbre.";
                }
            } else {
                foreach ($errors as $error) {
                    echo $error . "<br>";
                }
            }
        } else {
            foreach ($errors as $error) {
                echo $error . "<br>";
            }
        }
    } else {
        echo 'Erreur : méthode de requête non valide.';
    }

    if ($requestMethod == 'PUT')
    {
        parse_str(file_get_contents('php://input'), $_PUT);
        if($id !=''&&isset($_PUT['login'])&&isset($_PUT['text']))
            $data = dbModifyTweet($db, $id, $_PUT['login'], strip_tags($_PUT['text']));
    }
    
    if ($requestMethod == 'DELETE')
    {
        if($id !=''&&isset($_GET['login']))
            $data = dbDeleteTweet($db, intval($id), $_GET['login']);
    }

    // Send data to the client.
    header('Content-Type: application/json; charset=utf-8');
    header('Cache-control: no-store, no-cache, must-revalidate');
    header('Pragma: no-cache');
    if($requestMethod == 'POST')
        header('HTTP/1.1 201 Created');
    else
        header('HTTP/1.1 200 OK');
    echo json_encode($data);
    exit;
?>
