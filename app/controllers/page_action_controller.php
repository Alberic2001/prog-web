<?php

    require (__ROOT__.'/app/database/config/Database.php');
    require (__ROOT__.'/app/utils/helper.php');
    require (__ROOT__.'/app/models/PostitManager.php');

    date_default_timezone_set('Europe/Paris');

    // start session if not started
    if(session_id() == '') {
        session_start();
    }

    // redirect user if not logged
    if(!isset($_SESSION['user_id'])) {
        header("Location: http://".$_SERVER['HTTP_HOST']."/projet-ter/app/index.php?logout");
        exit();
    }

    // add postit 
    function addPostit() {
        require (__ROOT__.'/app/models/SharedManager.php');
        if($_POST['addPostit'] == "create") {
            $postit = postit_builder($_POST['title'], $_POST['content'], date("y/m/d"), $_SESSION['user_id'], 1);
            $createPostit = createPostit($postit);

            if(!isset($createPostit) || !$createPostit['success']){
                return $createPostit;
            }
            if(isset($_POST["user_id"]) && count($_POST["user_id"]) != 0) {
                $users = array_map('intval', $_POST["user_id"] );
                foreach($users as $user) {
                    $userShared = shared_builder($createPostit["postit_id"], $user,1);
                    $sharedCreate = createShared($userShared);
                    if(!isset($sharedCreate) || !$sharedCreate['success']){
                        return $sharedCreate;
                    }
                }
            }            
             return $createPostit;
        } else {
            // create postit
            $postit = postit_builder($_POST['title'], $_POST['content'], date("y/m/d"), $_SESSION['user_id'],$_POST['postit_id']);
            // update postit
            $updatePostit = update_postit($postit);
            //
            if(!$updatePostit || !$updatePostit['success']){
                return $updatePostit;
            }
            // array of users shared
           if(isset($_POST["user_id"]) && count($_POST["user_id"]) != 0) {
                // array of users shared
                $users = array_map('intval', $_POST["user_id"]);
            } else {
                $users = [];
            }
            if(isset($_POST["shared_init"]) && count($_POST["shared_init"]) != 0) {
                // array of users initially shared
                $shared_init = array_map('intval', $_POST["shared_init"] );
            } else {
                $shared_init = [];
            }
            // update shared
            foreach($users as $user) {
                if(in_array($user, $shared_init)) {
                    $key = array_search($user, $shared_init);
                    if($key !== false && isset($shared_init[$key])){
                        unset($shared_init[$key]);
                    }
                } else {
                    $userShared = shared_builder($_POST['postit_id'], $user,1);
                    $sharedCreate = createShared($userShared);
                    if(!isset($sharedCreate) || !isset($sharedCreate['success'])) {
                        return $sharedCreate;
                    }
                }
            }
            // delete all init shared not selected 
            foreach($shared_init as $shared) {
                $sharedDelete = delete_shared($shared, $_POST['postit_id']);
                if(!isset($sharedDelete) || !$sharedDelete['success']) {
                    return $sharedDelete;
                }
            }
            return $updatePostit;
        }
    }

    // delete post it
    function deletePostit() {
        $delete = delete_postit($_POST['postit_id']);
        if(isset($delete) && $delete['success']) {
            if(isset($_POST['location']) && $_POST['location'] == 'visualisationPostit') {
                header("Location: http://".$_SERVER['HTTP_HOST']."/projet-ter/app/index.php?home");
                exit();
            } else {
                return $delete;
            }
        }
    }
