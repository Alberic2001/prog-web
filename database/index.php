<?php
    require_once(__DIR__.'/cors.php');
    require_once(__DIR__.'/controller.php');
    ob_start();

    // inscription
    if(isset($_POST['inscription'])) {
        if ($_POST['inscription'] === 'createUser') {
            $res = incription();
            // initialise la session
            session_set_cookie_params(['samesite' => 'None']);
            session_start();
            $_SESSION['user_id'] = $res['id'];
            $_SESSION['user_name'] = filter_var($_POST['prenom'], FILTER_SANITIZE_STRING);
            header('Content-Type: application/json');
            ob_end_clean();
            echo json_encode($res);
            exit();
        } else {
            header('Content-Type: application/json');
            ob_end_clean();
            echo json_encode(array('success' => false));
            exit();
        }
    }

    // connexion
    if(isset($_POST['login'])) {
        if ($_POST['login'] === 'loginUser') {

            $res = login();
            if($res) {
                // initialise la session
                //ini_set('session.cookie_samesite', 'None');
                session_start();
                $_SESSION['user_id'] = $res['id'];
                var_dump($_SESSION['user_id']);
                var_dump($res['id']);

                $_SESSION['user_name'] = $res['surname'];
                ob_end_clean();
                header('Content-Type: application/json');
                echo json_encode($res);
                exit();
            }
            else {
                header('Content-Type: application/json');
                ob_end_clean();
                echo json_encode(array('success' => false, 'reason' => 'php' ,'message' => 'Something went wrong'));
                exit();
            }
        }
    }

    // ajout post it 
    if(isset($_POST['ajoutPostit'])) {
        echo 'postit';
        if ($_POST['ajoutPostit'] === 'true') {
                echo 'avant function';
                
                $res = ajoutPostit();
                echo 'après function';

                
                if($res) {
                    header('Content-Type: application/json');
                    ob_end_clean();
                    echo json_encode($res);
                    exit();
                }
                else {
                    header('Content-Type: application/json');
                    ob_end_clean();
                    echo json_encode(array('success' => false, 'reason' => 'php' ,'message' => 'Something went wrong'));
                    exit();
                }
            }
        }

        // supprimer post it 
        if(isset($_POST['deletePostit'])) {
            echo 'postit';
            if ($_POST['deletePostit'] === 'true') { 
                    //echo 'apres delete postit it true '; 
                    $res = deletePostit();     
                    if($res) {
                        //echo 'ok res'; 
                        header('Content-Type: application/json');
                        ob_end_clean();
                        echo json_encode(array('success' => $res));
                        exit();
                    }
                    else {
                        header('Content-Type: application/json');
                        ob_end_clean();
                        echo json_encode(array('success' => false, 'reason' => 'php' ,'message' => 'Something went wrong'));
                        exit();
                    }
                }
            }    

    // deconnexion
    if(isset($_GET['logout'])) {
        echo 'dunction logout';
        logout();
    }
?>