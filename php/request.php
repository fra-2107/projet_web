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

    if ($request[1] != 'tweets')
    {
        header('HTTP/1.1 400 Bad Request');
        exit;
    }
    $id=$request[2];

    if ($requestMethod == 'GET')
    {
        if(isset($_GET['login']))
            $data = dbRequestTweets($db, $_GET['login']);
        else 
            $data = dbRequestTweets($db);
    }
  
    if ($requestMethod == 'POST')
        if(isset($_POST['login'])&&isset($_POST['text']))
            $data = dbAddTweet($db, $_POST['login'], strip_tags($_POST['text']));
    
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
