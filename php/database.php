<?php
/**
 * @Author: Thibault Napoléon <Imothep>
 * @Company: ISEN Yncréa Ouest
 * @Email: thibault.napoleon@isen-ouest.yncrea.fr
 * @Created Date: 22-Jan-2018 - 13:57:23
 * @Last Modified: 13-Dec-2019 - 22:21:52
 */

  require_once('constants.php');

  //----------------------------------------------------------------------------
  //--- dbConnect --------------------------------------------------------------
  //----------------------------------------------------------------------------
  // Create the connection to the database.
  // \return False on error and the database otherwise.
  function dbConnect()
  {
    try
    {
      $db = new PDO('mysql:host='.DB_SERVER.';dbname='.DB_NAME.';charset=utf8',
        DB_USER, DB_PASSWORD);
      $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
    }
    catch (PDOException $exception)
    {
      error_log('Connection error: '.$exception->getMessage());
      return false;
    }
    return $db;
  }

  //----------------------------------------------------------------------------
  //--- dbRequestTweets --------------------------------------------------------
  //----------------------------------------------------------------------------
  // Function to get all tweets (if $login='') or the tweets of a user
  // (otherwise).
  // \param db The connected database.
  // \param login The login of the user (for specific request).
  // \return The list of tweets.
  function dbRequestArbres($db, $login = '')
  {
    try
    {
      $request = 'SELECT * FROM arbre';
      if ($login != '')
        $request .= ' WHERE login=:login';
      $statement = $db->prepare($request);
      if ($login != '')
        $statement->bindParam(':login', $login, PDO::PARAM_STR, 20);
      $statement->execute();
      $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    catch (PDOException $exception)
    {
      error_log('Request error: '.$exception->getMessage());
      return false;
    }
    return $result;
  }

  function dbGetArbre($db, $id)
  {
    try
    {
      $request = 'SELECT * FROM arbre WHERE id=:id';
      $statement = $db->prepare($request);
      $statement->bindParam(':id', $id, PDO::PARAM_INT);
      $statement->execute();
      $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    catch (PDOException $exception)
    {
      error_log('Request error: '.$exception->getMessage());
      return false;
    }
    return $result;
  }

  function dbGetArbretoAge($db, $id)
  {
    try
    {
      $request = 'SELECT haut_tot, haut_tronc, diam_tronc FROM arbre WHERE id=:id';
      $statement = $db->prepare($request);
      $statement->bindParam(':id', $id, PDO::PARAM_INT);
      $statement->execute();
      $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    catch (PDOException $exception)
    {
      error_log('Request error: '.$exception->getMessage());
      return false;
    }
    return $result;
  }

  function dbGetArbretoRisque($db, $id)
  {
    try
    {
      $request = 'SELECT haut_tot, haut_tronc, diam_tronc FROM arbre WHERE id=:id';
      $statement = $db->prepare($request);
      $statement->bindParam(':id', $id, PDO::PARAM_INT);
      $statement->execute();
      $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    catch (PDOException $exception)
    {
      error_log('Request error: '.$exception->getMessage());
      return false;
    }
    return $result;
  }


  function dbGetArbres($db, $limit = 10, $filters = null){
    $whereArgs = [];

    if (isset($_GET["page"])) {
        $page = intval($_GET['page']);
    } else {
        $page = 1;
    }

    $decalage = ($page - 1) * $limit;

    $sql = 'SELECT * FROM arbre ';

    if ($filters != null) {
        foreach ($filters as $key => $value) {
            if ($value != '') $whereArgs[] = $key . ' = :' . $key;
        }
    }
    if (!empty($whereArgs)) $sql .= 'WHERE ' . implode(' AND ', $whereArgs);

    $sql .= ' LIMIT :limit OFFSET :offset';
    $sth = $db->prepare($sql);

    if ($filters != null) {
        // if ($filters['date'] != '') $sth->bindParam('date', $filters['date']);
        // if ($filters['espece'] != '') $sth->bindParam('espece', $filters['espece']);
        // if ($filters['zone'] != '') $sth->bindParam('zone', $filters['zone']);
        echo('filters: ');
    }
    $sth->bindParam(':limit', $limit, PDO::PARAM_INT);
    $sth->bindParam(':offset', $decalage, PDO::PARAM_INT);
    $sth->execute();
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);

  return $result;
}

function dbGetTotalArbres($db) {
  try {
      $stmt = $db->query("SELECT COUNT(*) as total FROM arbre");
      return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
  } catch (PDOException $e) {
      echo json_encode(['error' => 'Erreur : ' . $e->getMessage()]);
      return false;
  }
}

  //----------------------------------------------------------------------------
  //--- dbAddCTweet ------------------------------------------------------------
  //----------------------------------------------------------------------------
  // Add a tweet.
  // \param db The connected database.
  // \param login The login of the user.
  // \param text The tweet to add.
  // \return True on success, false otherwise.
  function dbAddArbre($db, $data) {
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
  //--- dbModifyTweet ----------------------------------------------------------
  //----------------------------------------------------------------------------
  // Function to modify a tweet.
  // \param db The connected database.
  // \param id The id of the tweet to update.
  // \param login The login of the user.
  // \param text The new tweet.
  // \return True on success, false otherwise.
  function dbModifyTweet($db, $id, $login, $text)
  {
    try
    {
      $request = 'UPDATE tweets SET text=:text WHERE id=:id AND login=:login ';
      $statement = $db->prepare($request);
      $statement->bindParam(':id', $id, PDO::PARAM_INT);
      $statement->bindParam(':login', $login, PDO::PARAM_STR, 20);
      $statement->bindParam(':text', $text, PDO::PARAM_STR, 80);
      $statement->execute();
    }
    catch (PDOException $exception)
    {
      error_log('Request error: '.$exception->getMessage());
      return false;
    }
    return true;
  }

  //----------------------------------------------------------------------------
  //--- dbDeleteTweet ----------------------------------------------------------
  //----------------------------------------------------------------------------
  // Delete a tweet.
  // \param db The connected database.
  // \param id The id of the tweet.
  // \param login The login of the user.
  // \return True on success, false otherwise.
  function dbDeleteTweet($db, $id, $login)
  {
    try
    {
      $request = 'DELETE FROM tweets WHERE id=:id AND login=:login';
      $statement = $db->prepare($request);
      $statement->bindParam(':id', $id, PDO::PARAM_INT);
      $statement->bindParam(':login', $login, PDO::PARAM_STR, 20);
      $statement->execute();
    }
    catch (PDOException $exception)
    {
      error_log('Request error: '.$exception->getMessage());
      return false;
    }
    return true;
  }

  function dbGetOptions($db, $table) {
    try {
        $stmt = $db->prepare("SELECT * FROM $table");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Erreur : ' . $e->getMessage()]);
        return false;
    }
}

function dbGetCoordMap($db)
{
  try
  {
      $request = 'SELECT lat, longi FROM arbre';
      $statement = $db->prepare($request);
      $statement->execute();
      $result = $statement->fetchAll(PDO::FETCH_ASSOC);
  }
  catch (PDOException $exception)
  {
      error_log('Request error: '.$exception->getMessage());
      return false;
  }
  return $result;
}

?>
