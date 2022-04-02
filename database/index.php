<?php
    define('__ROOT__', dirname(dirname(__FILE__)));
    require (__ROOT__.'/database/cors.php');

    // sign up
    if(isset($_POST['sign_up'])) {
        require (__ROOT__.'/database/auth_controller.php');
        if ($_POST['sign_up'] === 'createUser') {
            $res = sign_up_user(); 
            if ($res) {
                header('Content-Type: application/json');
                echo json_encode($res);
                exit();
            }
            else {
                header('Content-Type: application/json');
                echo json_encode(array('success' => false, 'reason' => 'php' ,'message' => 'Something went wrong try again'));
                exit();
            }
        }
    }

    // login
    if(isset($_POST['login'])) {
        require (__ROOT__.'/database/auth_controller.php');
        if ($_POST['login'] === 'loginUser') {
            $res = login();
            if($res) {
                header('Content-Type: application/json');
                echo json_encode($res);
                exit();
            }
            else {
                header('Content-Type: application/json');
                echo json_encode(array('success' => false, 'reason' => 'php' ,'message' => 'Something went wrong try again'));
                exit();
            }
        }
    }

    // logout
    if(isset($_GET['logout'])) {
        require (__ROOT__.'/database/auth_controller.php');
        logout();
    }

    // home
    if(isset($_GET['home'])) {
        require (__ROOT__.'/database/page_controller.php');
        home();
    }

    // add Postit Page
    if(isset($_POST['addPostitPage']) || isset($_GET['addPostitPage']) ) {
        if($_POST['addPostitPage'] == 'create' || $_POST['addPostitPage'] == 'update' || $_GET['addPostitPage'] == 'create' ) {
            require (__ROOT__.'/database/page_controller.php');
            addPostitPage();
        }
    }

    // visualisation Postit
    if(isset($_POST['visualisation'])) {
        if($_POST['visualisation'] == 'postit') {
            require (__ROOT__.'/database/page_controller.php');
            visualisationPostit();
        }
    }
    
    // ajout post it 
    if(isset($_POST['addPostit'])) {
        require (__ROOT__.'/database/page_controller.php');
        if ($_POST['addPostit'] === 'create' || $_POST['addPostit'] === 'update') { 
            $res = addPostit();
            if($res) {
                header('Content-Type: application/json');
                echo json_encode($res);
                exit();
            }
            else {
                echo json_encode(array('success' => false, 'reason' => 'php' ,'message' => 'Something went wrong try again'));
                exit();
            }
        }
    }

    // supprimer post it 
    if(isset($_POST['deletePostit'])) {
        require (__ROOT__.'/database/page_controller.php');
        if ($_POST['deletePostit'] === 'true') { 
            $res = deletePostit();     
            if($res) {
                header('Content-Type: application/json');
                echo json_encode($res);
                exit();
            }
            else {
                header('Content-Type: application/json');
                echo json_encode(array('success' => false, 'reason' => 'php' ,'message' => 'Something went wrong try again'));
                exit();
            }
        }
    }

    if(!isset($_POST) && !isset($_GET) ) {
        require (__ROOT__.'/database/page_controller.php');
        home();        
    }
?>
