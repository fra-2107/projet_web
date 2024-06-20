<?php 
require_once('database.php');

// Database connexion.
$db = dbConnect();
if (!$db) {
    header('HTTP/1.1 503 Service Unavailable');
    exit;
}

// Check the request.
$requestMethod = $_SERVER['REQUEST_METHOD'];
$request = $_SERVER['PATH_INFO'];
$request = explode('/', $request);

if ($request[1] == 'arbres') {
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 20;
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($page - 1) * $limit;

    // Préparation des filtres à partir de la requête GET
    $filters = [];

    if (isset($_GET['etat'])) {
        $filters['fk_arb_etat'] = $_GET['etat'];
    }
    if (isset($_GET['espece'])) {
        $filters['espece'] = $_GET['espece'];
    }

    if ($requestMethod == 'GET') {
        // Appel de la fonction dbGetArbres avec les paramètres appropriés
        $arbres = dbGetArbres($db, $limit, $offset, $filters);
        $total = dbGetTotalArbres($db); 
        
        $response = [
            'total' => $total,
            'page' => $page,
            'limit' => $limit,
            'data' => $arbres
        ];

        $data = $response;
    }


    if ($requestMethod == 'POST') {
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
        } else {
            // Afficher les erreurs
            echo json_encode($errors);
        }
    }

    if ($requestMethod == 'DELETE') {
        $id = $_GET['id'];
        if($id !='')
            $data = dbDeleteArbre($db, intval($id));
    }
    
} else if ($request[1] == 'fk_arb_etat') {
    $data = dbGetOptions($db, 'fk_arb_etat');
} else if ($request[1] == 'fk_stadedev') {
    $data = dbGetOptions($db, 'fk_stadedev');
} else if ($request[1] == 'fk_pied') {
    $data = dbGetOptions($db, 'fk_pied');
} else if ($request[1] == 'fk_port') {
    $data = dbGetOptions($db, 'fk_port');
} else if ($request[1] == 'map') {
    $data = dbGetCoordMap($db);
} elseif ($request[1] == 'predictClust') {

    $nb_clusters = isset($_POST['nb_clusters']) ? (int)$_POST['nb_clusters'] : 0;

    if (is_numeric($nb_clusters) && $nb_clusters > 0) {
        // Construction de la commande pour exécuter le script Python

        $python_script = "/var/www/etu0106/projet_web/python/script_besoin_1.py"; // Chemin absolu vers le script Python

        if (file_exists($python_script)) {
            $command = "/usr/bin/python " . $python_script . " " . intval($nb_clusters);
            if (file_exists("/var/www/etu0106/projet_web/map.html"))
                exec("rm /var/www/etu0106/projet_web/map.html");
            // Exécution de la commande
            exec($command, $output, $return_var);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Nombre de clusters invalide.']);
    }
} elseif ($request[1] == 'preds') {
    $id = $_GET['id'];

    if (isset($_GET['age'])) {
        $data = dbGetArbretoAge($db, $id);
        $python_script = "/var/www/etu0106/projet_web/python/script_besoin_2.py"; // Chemin absolu vers le script Python


        // Vérifier si des données ont été récupérées
        if ($data !== false) {
            // Ajouter l'attribut 'fk_prec_estim' à chaque élément du tableau
            foreach ($data as &$item) {
                $item['fk_prec_estim'] = "10";
            }

            // Encoder le tableau en JSON
            $jsonData = json_encode($data);

            if (file_exists($python_script)) {
                $command = "/usr/bin/python " . $python_script . " '" . ($jsonData) . "'";
                // Exécution de la commande
                exec($command, $output, $return_var);
                $data = $output;
            }
        }
    } elseif (isset($_GET['risque'])) {
        $data = dbGetArbretoRisque($db, $id);
        $python_script = "/var/www/etu0106/projet_web/python/uprooting.py"; // Chemin absolu vers le script Python
        // Vérifier si des données ont été récupérées
        if ($data !== false) {
            // Encoder le tableau en JSON
            $model = 'rf';
            $species = $data[0]['espece'];
            $latitude  = $data[0]['lat'];
            $longitude = $data[0]['longi'];
            $trunc_diameter = $data[0]['diam_tronc'];
            $trunc_height = $data[0]['haut_tronc'];
            $height = $data[0]['haut_tot'];

            $argdata = sprintf(
                '-m %s --species "%s" --height %d --trunc_height %d --trunc_diameter %d --latitude %f --longitude %f',
                $model,
                $species,
                $height,
                $trunc_height,
                $trunc_diameter,
                $latitude,
                $longitude
            );

            if (file_exists($python_script)) {
                $command = "/usr/bin/python " . $python_script . " " . ($argdata);

                // Exécution de la commande
                exec($command, $output, $return_var);
                $data = $output;
            }
        }
    }elseif (isset($_GET['map'])) {
        $data = dbGetArbretoRisque($db, $id);
        // $data = json_encode($data);
    }
} elseif ($request[1] == 'especes') {
    $data = dbGetEspeces($db);
} else {
    header('HTTP/1.1 400 Bad Request');
    exit;
}

// Send data to the client.
header('Content-Type: application/json; charset=utf-8');
header('Cache-control: no-store, no-cache, must-revalidate');
header('Pragma: no-cache');
if ($requestMethod == 'POST')
    header('HTTP/1.1 201 Created');
else
    header('HTTP/1.1 200 OK');
echo json_encode($data);
exit;