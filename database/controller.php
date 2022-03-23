<?php

   function incription() {
        include_once('./config/Database.php');
        include_once('./config/Template.php');
        include_once('./models/User.php');
        require_once('./manager/UserManager.php');
        // $createUser = new UserManager();

        // if(!$createUser){
        //     return false;
        // }
        $user = user_builder($_POST['email'], $_POST['password'], $_POST['prenom'], $_POST['nom'], date('Y-m-d', strtotime($_POST['birthday'])), 1);
        if(!$user){
            return false;
        }

        if (sign_up($user)) {
            // ok creation
            echo 'account created';
            return true;
        } else {
            // not ok creation
            return false;
        }
    }
?>