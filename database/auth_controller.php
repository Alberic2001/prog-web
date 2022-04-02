<?php

    // logout user
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

    require (__ROOT__.'/database/config/Database.php');
    require (__ROOT__.'/database/models/Helper.php');
    require (__ROOT__.'/database/manager/UserManager.php');

    // sign up user
    function sign_up_user() {
        $user = user_builder($_POST['email'], $_POST['password'], $_POST['prenom'], $_POST['nom'], date('Y-m-d', strtotime($_POST['birthday'])), 1);
        $user_signup = sign_up($user);
        // initialise la session
        // session_set_cookie_params(['samesite' => 'None']);
        if ($user_signup['success']) {
            session_start();
            $_SESSION['user_id'] = $user_signup['id'];
            $_SESSION['user_name'] = $prenom.' '.$nom;
            $_SESSION['email'] = $user_signup['email'];
        }
        return $user_signup;
    }

    // login user
    function login() {
        $user_signin = sign_in($_POST['email'], $_POST['password']);
        if($user_signin['success']) {
            session_start();
            $_SESSION['user_id'] = $user_signin['id'];
            $_SESSION['user_name'] = $user_signin['surname']. ' '.$user_signin['lastname'];
            $_SESSION['email'] = $user_signin['email'];
        }
        return $user_signin;
    }
