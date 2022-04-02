<?php

    require (__ROOT__.'/database/config/Database.php');
    require (__ROOT__.'/database/models/helper.php');
    require (__ROOT__.'/database/manager/PostitManager.php');

    date_default_timezone_set('Europe/Paris');

    // start session if not started
    if(session_id() == '') {
        session_start();
    }

    // redirect user if not logged
    if(!isset($_SESSION['user_id'])) {
        header("Location: http://".$_SERVER['HTTP_HOST']."/projet-ter/database/index.php?logout");
        exit();
    }

    // page home
    function home() {
        require (__ROOT__.'/database/manager/SharedManager.php');
        $user_postit = read_all_for_one_user_postit($_SESSION['user_id']);
        $user_shared = read_all_for_one_user_shared($_SESSION['user_id']);
        if (isset($user_shared) && $user_shared['success']) {
            unset($user_shared["success"]);
            $postit_shared = array(); 
            foreach ($user_shared as $shared) {
                $post = read_one_postit($shared["postit_id"]);
                if(isset($post) && $post['success']) {
                    unset($post['success']);
                    array_push($postit_shared, $post);
                }
            }
        }
        require (__ROOT__.'/accueil.php');
    }

    // page visualisation postit
    function visualisationPostit() {
        require (__ROOT__.'/database/manager/SharedManager.php');
        require (__ROOT__.'/database/manager/UserManager.php');

        $postit = read_one_postit($_POST['postit_id']);
        $user_name = '';
        if($postit['user_id'] == $_SESSION['user_id']){
            $user_name = $_SESSION['user_name'];
        } else {
            $user_name_postit = get_username_for_postit($postit['id']);
            $user_name = $user_name_postit['name'];
        }

        $user_shared = read_all_postits($_POST['postit_id']);
        if (isset($user_shared) && $user_shared['success']) {
            unset($user_shared["success"]);
            $shared_array = array();
            foreach ($user_shared as $shared) {
                $user = get_email_for_userId($shared["user_id"]);
                if (isset($user) && $user['success']) {
                    unset($user["success"]);
                    array_push($shared_array, $user);
                }
            }
        }
        $edition = false;
        if(isset($_POST['edition']) && $_POST['edition'] == "true") {
            $edition = true;
        }
        require (__ROOT__.'/visualisation_post_it.php');
    }

    // page add postit
    function addPostitPage() {
        require (__ROOT__.'/database/manager/UserManager.php');
        $arrayUser = read_all_user();
        if ($_POST['addPostitPage'] == 'create' || $_GET['addPostitPage'] == 'create') {
            $create = true;
            if(isset($arrayUser) && $arrayUser['success']) {
                $shared_array = array();
                unset($arrayUser["success"]);
                foreach($arrayUser as $user) {
                    if($user["id"] != $_SESSION['user_id']){
                        $user['checked'] = '';
                        array_push($shared_array, $user);
                    }
                }
            }
        } else {
            // update postit 
            require (__ROOT__.'/database/manager/SharedManager.php');
            $create = false;
            $postit = read_one_postit($_POST['postit_id']);
            $postit['name'] = $_SESSION['user_name'];
            $user_shared = read_all_postits($_POST['postit_id']);
            $time = strtotime($dateInUTC.' UTC');
            $dateInLocal = date("Y-m-d", $time);
            if($arrayUser['success']) {
                $shared_array = array();
                unset($arrayUser["success"]);
                unset($user_shared["success"]);
                foreach($arrayUser as $user) {
                    if($user["id"] != $_SESSION['user_id']){
                        $in_shared = false;
                        foreach($user_shared as $shared){
                            if($user["id"] == $shared['user_id']){
                                $in_shared = true;
                                break;
                            }
                        }
                        if($in_shared) {
                            $user['checked'] = 'checked';
                        } else {
                            $user['checked'] = '';
                        }
                        array_push($shared_array, $user);
                    }
                }
            }
        }
        require (__ROOT__.'/ajout_post_it.php');
    }

    // add postit 
    function addPostit() {
        require (__ROOT__.'/database/manager/SharedManager.php');
        if($_POST['addPostit'] == "create") {
            $postit = postit_builder($_POST['title'], $_POST['content'], date("y/m/d"), $_SESSION['user_id'], 1);
            $createPostit = createPostit($postit);

            if(!isset($createPostit) || !$createPostit['success']){
                return $createPostit;
            }

            $users = array_map('intval', $_POST["user_id"] );
            foreach($users as $user) {
                $userShared = shared_builder($createPostit["postit_id"], $user,1);
                $sharedCreate = createShared($userShared);
                if(!isset($sharedCreate) || !$sharedCreate['success']){
                    return $sharedCreate;
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
            $users = array_map('intval', $_POST["user_id"] );
            // array of users initially shared
            $shared_init = array_map('intval', $_POST["shared_init"] );
            // update shared
            foreach($users as $user) {
                if(in_array($user, $shared_init)) {
                    $key = array_search($user, $shared_init);
                    if($key !== false){
                        unset($shared_init[$key]);
                    }
                } else {
                    $userShared = shared_builder($_POST['postit_id'], $user,1);
                    $sharedCreate = createShared($userShared);
                    if(!isset($sharedCreate) || !$sharedCreate['success']) {
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
                header("Location: http://".$_SERVER['HTTP_HOST']."/projet-ter/database/index.php?home");
                exit();
            } else {
                return $delete;
            }
        }
    }

