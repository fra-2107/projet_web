<?php
require_once('constants.php');

//----------------------------------------------------------------------------
//--- dbConnect --------------------------------------------------------------
//----------------------------------------------------------------------------
// Create the connection to the database.
// \return False on error and the database otherwise.
function dbConnect()
{
  try {
    $db = new PDO(
      'mysql:host=' . DB_SERVER . ';dbname=' . DB_NAME . ';charset=utf8',
      DB_USER,
      DB_PASSWORD
    );
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  } catch (PDOException $exception) {
    error_log('Connection error: ' . $exception->getMessage());
    return false;
  }
  return $db;
}

//----------------------------------------------------------------------------
//--- dbRequestArbres --------------------------------------------------------
//----------------------------------------------------------------------------
// Function to get all trees from the database.
// \param db The connected database.
// \param login The login of the user.
// \return The list of trees on success, false otherwise.

function dbRequestArbres($db, $login = '')
{
  try {
    $request = 'SELECT * FROM arbre';
    if ($login != '')
      $request .= ' WHERE login=:login';
    $statement = $db->prepare($request);
    if ($login != '')
      $statement->bindParam(':login', $login, PDO::PARAM_STR, 20);
    $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
  } catch (PDOException $exception) {
    error_log('Request error: ' . $exception->getMessage());
    return false;
  }
  return $result;
}

//----------------------------------------------------------------------------
//--- dbGetArbre -------------------------------------------------------------
//----------------------------------------------------------------------------
// Function to get a tree from the database.
// \param db The connected database.
// \param id The id of the tree.
// \return The tree on success, false otherwise.

function dbGetArbre($db, $id)
{
  try {
    $request = 'SELECT * FROM arbre WHERE id=:id';
    $statement = $db->prepare($request);
    $statement->bindParam(':id', $id, PDO::PARAM_INT);
    $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
  } catch (PDOException $exception) {
    error_log('Request error: ' . $exception->getMessage());
    return false;
  }
  return $result;
}

//----------------------------------------------------------------------------
//--- dbGetArbretoAge --------------------------------------------------------
//----------------------------------------------------------------------------
// Function to get a tree from the database.
// \param db The connected database.
// \param id The id of the tree.
// \return The tree on success, false otherwise.

function dbGetArbretoAge($db, $id)
{
  try {
    $request = 'SELECT haut_tot, haut_tronc, diam_tronc FROM arbre WHERE id=:id';
    $statement = $db->prepare($request);
    $statement->bindParam(':id', $id, PDO::PARAM_INT);
    $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
  } catch (PDOException $exception) {
    error_log('Request error: ' . $exception->getMessage());
    return false;
  }
  return $result;
}

//----------------------------------------------------------------------------
//--- dbGetArbretoRisque -----------------------------------------------------
//----------------------------------------------------------------------------
// Function to get a tree from the database.
// \param db The connected database.
// \param id The id of the tree.
// \return The tree on success, false otherwise.

function dbGetArbretoRisque($db, $id)
{
  try {
    $request = 'SELECT espece, lat, longi, diam_tronc, haut_tronc, haut_tot FROM arbre WHERE id=:id';
    $statement = $db->prepare($request);
    $statement->bindParam(':id', $id, PDO::PARAM_INT);
    $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
  } catch (PDOException $exception) {
    error_log('Request error: ' . $exception->getMessage());
    return false;
  }
  return $result;
}

//----------------------------------------------------------------------------
//--- dbGetArbres ------------------------------------------------------------
//----------------------------------------------------------------------------
// Function to get all trees from the database.
// \param db The connected database.
// \param limit The number of trees to get.
// \return The list of trees on success, false otherwise.

function dbGetArbres($db, $limit = 10, $filters = null)
{
    $whereArgs = [];
    $params = [
        ':limit' => (int)$limit,
        ':offset' => 0, // Décalage initial, ajusté plus loin
    ];

    // Gestion de la pagination
    if (isset($_GET["page"])) {
        $page = intval($_GET['page']);
    } else {
        $page = 1;
    }
    $offset = ($page - 1) * $limit;
    $params[':offset'] = (int)$offset; // Convertir en entier

    $sql = 'SELECT * FROM arbre ';

    // Construction de la clause WHERE pour les filtres
    if ($filters != null && is_array($filters)) {
        foreach ($filters as $key => $value) {
            if ($value != '') {
                $whereArgs[] = $key . ' = :' . $key;
                $params[':' . $key] = $value; // Ajouter le paramètre au tableau des paramètres
            }
        }
    }

    // Ajout de la clause WHERE si des filtres sont présents
    if (!empty($whereArgs)) {
        $sql .= 'WHERE ' . implode(' AND ', $whereArgs);
    }

    $sql .= ' LIMIT :limit OFFSET :offset';

    echo 'SQL: ' . $sql ;

    try {
        $sth = $db->prepare($sql);

        // Liaison des paramètres
        $sth->bindParam(':limit', $params[':limit'], PDO::PARAM_INT);
        $sth->bindParam(':offset', $params[':offset'], PDO::PARAM_INT);

        // Exécution de la requête avec les paramètres
        $sth->execute($params);
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Erreur : ' . $e->getMessage()]);
        return false;
    }
}


//----------------------------------------------------------------------------
//--- dbGetTotalArbres -------------------------------------------------------
//----------------------------------------------------------------------------
// Function to get the total number of trees in the database.
// \param db The connected database.
// \return The total number of trees on success, false otherwise.

function dbGetTotalArbres($db)
{
  try {
    $stmt = $db->query("SELECT COUNT(*) as total FROM arbre");
    return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
  } catch (PDOException $e) {
    echo json_encode(['error' => 'Erreur : ' . $e->getMessage()]);
    return false;
  }
}

//----------------------------------------------------------------------------
//--- dbAddArbre -------------------------------------------------------------
//----------------------------------------------------------------------------
// Function to add a tree to the database.
// \param db The connected database.
// \param data The data of the tree.
// \return The id of the tree on success, false otherwise.

function dbAddArbre($db, $data)
{
  try {
    $stmt = $db->prepare("INSERT INTO arbre (espece, haut_tot, haut_tronc, diam_tronc, lat, longi, fk_arb_etat, fk_stadedev, fk_port, fk_pied, remarquable)
                              VALUES (:espece, :haut_tot, :haut_tronc, :diam_tronc, :lat, :longi, :fk_arb_etat, :fk_stadedev, :fk_port, :fk_pied, :remarquable)");
    $stmt->bindParam(':espece', $data['espece'], PDO::PARAM_STR);
    $stmt->bindParam(':haut_tot', $data['haut_tot'], PDO::PARAM_INT);
    $stmt->bindParam(':haut_tronc', $data['haut_tronc'], PDO::PARAM_INT);
    $stmt->bindParam(':diam_tronc', $data['diam_tronc'], PDO::PARAM_INT);
    $stmt->bindParam(':lat', $data['lat'], PDO::PARAM_STR);
    $stmt->bindParam(':longi', $data['longi'], PDO::PARAM_STR);
    $stmt->bindParam(':fk_arb_etat', $data['fk_arb_etat'], PDO::PARAM_STR); // Assurez-vous que les types de paramètres sont corrects
    $stmt->bindParam(':fk_stadedev', $data['fk_stadedev'], PDO::PARAM_STR); // Assurez-vous que les types de paramètres sont corrects
    $stmt->bindParam(':fk_port', $data['fk_port'], PDO::PARAM_STR); // Assurez-vous que les types de paramètres sont corrects
    $stmt->bindParam(':fk_pied', $data['fk_pied'], PDO::PARAM_STR); // Assurez-vous que les types de paramètres sont corrects
    $stmt->bindParam(':remarquable', $data['remarquable'], PDO::PARAM_STR);
    $stmt->execute();
    return $db->lastInsertId();
  } catch (PDOException $e) {
    echo json_encode(['error' => 'Erreur : ' . $e->getMessage()]);
    return false;
  }
}

//----------------------------------------------------------------------------
//--- dbGetOptions -----------------------------------------------------------
//----------------------------------------------------------------------------
// Function to get all options for the selects from the database.
// \param db The connected database.
// \param table The table to get the options from.

function dbGetOptions($db, $table)
{
  try {
    $stmt = $db->prepare("SELECT * FROM $table");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  } catch (PDOException $e) {
    echo json_encode(['error' => 'Erreur : ' . $e->getMessage()]);
    return false;
  }
}


function dbGetEspeces($db)
{
  try {
    $stmt = $db->prepare("SELECT espece FROM arbre GROUP BY espece");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  } catch (PDOException $e) {
    echo json_encode(['error' => 'Erreur : ' . $e->getMessage()]);
    return false;
  }
}

//----------------------------------------------------------------------------
//--- dbGetCoordMap ----------------------------------------------------------
//----------------------------------------------------------------------------
// Function to get the coordinates of the trees.
// \param db The connected database.
// \return The list of coordinates on success, false otherwise.
function dbGetCoordMap($db)
{
  try {
    $request = 'SELECT lat, longi FROM arbre';
    $statement = $db->prepare($request);
    $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
  } catch (PDOException $exception) {
    error_log('Request error: ' . $exception->getMessage());
    return false;
  }
  return $result;
}
