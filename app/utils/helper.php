<?php

    function postit_builder($title, $content, $date, $user_id, $id = 0){
        return array('id' => $id, 'title'=>$title, 'content' => $content, 'date' => $date, 'user_id' => $user_id);
    }

    function shared_builder($postit_id, $user_id, $id = 0){
        return array('id' => $id, 'postit_id' => $postit_id, 'user_id' => $user_id);
    }

    function user_builder($email, $password, $surname, $lastname, $birth_date, $id = 0){
        return array('id'=>$id, 'email'=>$email, 'password'=>$password, 'surname'=>$surname, 'lastname'=>$lastname, 'birth_date'=>$birth_date);
    }

    function message_builder($success, $reason, $message){
        return array('success' => $success, 'reason' => $reason ,'message' => $message);
    }
