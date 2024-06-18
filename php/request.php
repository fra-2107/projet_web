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

    if ($request[1] == 'arbres')
    {

        $id=$request[2];

        if ($requestMethod == 'GET')
        {
            if(isset($_GET['login']))
                $data = dbRequestArbres($db, $_GET['login']);
            else 
                $data = dbRequestArbres($db);
        }
    
        if ($requestMethod == 'POST'){
            // Liste des champs requis
            $requiredFields = ['espece', 'haut_tot', 'haut_tronc', 'diam_tronc', 'lat', 'longi', 'fk_arb_etat', 'fk_stadedev', 'fk_port', 'fk_pied', 'remarquable'];
            $data = [];
            $errors = [];
        
            // Log the POST data to ensure it's correct
            error_log('POST Data: ' . print_r($_POST, true));
        
            // Vérifier la présence de chaque champ requis dans $_POST
            foreach ($requiredFields as $field) {
                if (isset($_POST[$field]) && !empty($_POST[$field])) {
                    // Nettoyer les valeurs et les stocker dans le tableau $data
                    $data[$field] = strip_tags($_POST[$field]);
                } else {
                    $errors[] = "Le champ $field est manquant.";
                }
            }
        
            // Log errors if any
            if (!empty($errors)) {
                error_log('Errors: ' . print_r($errors, true));
            }
        
            // Si aucun champ n'est manquant, procéder à la validation des données
            if (empty($errors)) {
                // Validation des données pour s'assurer qu'elles sont numériques où c'est nécessaire
                foreach (['haut_tot', 'haut_tronc', 'diam_tronc', 'lat', 'longi'] as $numericField) {
                    if (!is_numeric($data[$numericField])) {
                        $errors[] = "Le champ $numericField doit être un nombre valide.";
                    }
                }
            }
        
            // Si aucune erreur de validation, insérer les données dans la base de données
            if (empty($errors)) {
                $result = dbAddArbre($db, $data);
                if ($result) {
                    echo json_encode(['message' => "L'arbre a été ajouté avec succès. ID : " . $result]);
                } else {
                    echo json_encode(['message' => "Erreur lors de l'ajout de l'arbre."]);
                }
            } else {
                // Afficher les erreurs
                echo json_encode($errors);
            }
        } else {
            echo json_encode(['message' => 'Erreur : méthode de requête non valide.']);
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
    }
    else if ($request[1] == 'fk_arb_etat'){
        $data = dbGetOptions($db, 'fk_arb_etat');
    }else if ($request[1] == 'fk_stadedev'){
        $data = dbGetOptions($db, 'fk_stadedev');
    }else if ($request[1] == 'fk_pied'){
        $data = dbGetOptions($db, 'fk_pied');
    }else if ($request[1] == 'fk_port'){
        $data = dbGetOptions($db, 'fk_port');
    }else if ($request[1] == 'map'){
        $data = dbGetCoordMap($db);
    }
    
    else
    {
        header('HTTP/1.1 400 Bad Request');
        exit;
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
