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

    // page home
    function home() {
        require (__ROOT__.'/app/models/SharedManager.php');
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
        $animationStart = false;
        if(isset($_GET['from']) && $_GET['from'] == 0) {
            $animationStart = true;
        }
        require (__ROOT__.'/app/views/accueil.php');
    }

    // page visualisation postit
    function visualisationPostit() {
        require (__ROOT__.'/app/models/SharedManager.php');
        require (__ROOT__.'/app/models/UserManager.php');

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
        require (__ROOT__.'/app/views/visualisation_post_it.php');
    }

    // page add postit
    function addPostitPage() {
        require (__ROOT__.'/app/models/UserManager.php');
        $arrayUser = read_all_user();
        if ($_POST['addPostitPage'] == 'create' ) {
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
            require (__ROOT__.'/app/models/SharedManager.php');
            $create = false;
            $postit = read_one_postit($_POST['postit_id']);
            $postit['name'] = $_SESSION['user_name'];
            $user_shared = read_all_postits($_POST['postit_id']);
            if($arrayUser['success']) {
                $shared_array = array();
                unset($arrayUser["success"]);
                unset($user_shared["success"]);
                foreach($arrayUser as $user) {
                    if($user["id"] != $_SESSION['user_id']){
                        $in_shared = false;
                        foreach($user_shared as $shared){
                            if(isset($shared['user_id'])){
                            if($user["id"] == $shared['user_id']){
                                $in_shared = true;
                                break;
                            }
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
        require (__ROOT__.'/app/views/ajout_post_it.php');
    }

    // page account
    function account() {
        require (__ROOT__.'/app/models/SharedManager.php');
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
        require (__ROOT__.'/app/views/compte.php');
    }