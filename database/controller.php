<?php

   function incription() {
        require_once(__DIR__.'/manager/UserManager.php');
        // sanitize input, added security
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $prenom = filter_var($_POST['prenom'], FILTER_SANITIZE_STRING);
        $nom = filter_var($_POST['nom'], FILTER_SANITIZE_STRING);
        $birthday = filter_var($_POST['birthday'], FILTER_SANITIZE_STRING);

        $user = user_builder($email, $_POST['password'], $prenom, $nom, date('Y-m-d', strtotime($birthday)), 1);
        $user_signup = sign_up($user);
        return $user_signup;
    }

    function login() {
        require_once(__DIR__.'/manager/UserManager.php');
        // sanitize input, added security
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $user_signin = sign_in($email, $_POST['password']);
        return $user_signin;
    }

    function ajoutPostit() {
        require_once __DIR__.'/manager/PostitManager.php';
        require_once __DIR__.'/manager/SharedManager.php';
        var_dump($_POST);
        if(isset($_POST['edition'])) {
            session_start();
            $title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
            $content = filter_var($_POST['content'], FILTER_SANITIZE_STRING);

            if($_POST['edition'] == "create") {
                date_default_timezone_set('UTC');
                echo 'user id session  : '.$_SESSION['user_id']." <br>";

                $postit = postit_builder($title, $content, date("y/m/d"), $_SESSION['user_id'], 1);
                var_dump($postit);
                $createPostit = createPostit($postit);
                var_dump($createPostit);
                var_dump($createPostit['success']);
                echo ($createPostit['postit_id']);

                echo '<br> createPostit <br>';

                var_dump($createPostit);

                if(!$createPostit){
                    return $createPostit;
                }
                echo '<br> $users <br>';

                $users = array_map('intval', $_POST["user_id"] );
                var_dump($users);

                foreach($users as $user) {
                    echo '<br>';
                    echo 'user   : '.$user." <br>";
                    echo 'post it id   : '.$createPostit["postit_id"]." <br>";

                    
                    $userShared = shared_builder($createPostit["postit_id"], $user,1);
                    $sharedCreate = createShared($userShared);
                    var_dump($sharedCreate);

                    if(!$sharedCreate){
                        return $sharedCreate;
                    }
                }
                return $createPostit;
            } else {

            }
        }
    }


    function deletePostit() {
        require_once __DIR__.'/manager/PostitManager.php';
        $delete = delete_postit($_POST['postit_id']);
        if($delete == true) {
            if(isset($_POST['location']) && $_POST['location'] == 'visualisationPostit') {
                header("Location: http://".$_SERVER['HTTP_HOST']."/projet-ter/accueil.php");
                exit();
            } else {
                return $delete;
            }
        }
    }

    function logout() {
        session_start();
        $_SESSION = array();
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
        header("Location: http://".$_SERVER['HTTP_HOST']."/projet-ter/connexion.html");
        exit();
    }
?>