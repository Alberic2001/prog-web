<?php
    require_once('./cors.php');
    require_once('./controller.php');
    echo $_POST['inscription'];
    if(($_POST['inscription'])) {
        if (strpos($_POST['inscription'], 'createUser')===0) {
            $res = incription();
            header('Content-Type: application/json');
            echo json_encode(array('success' => $res));
            exit;
        } else {
            header('Content-Type: application/json');
            echo json_encode(array('success' => 'false 1'));
            exit;
        }
    } else {
        header('Content-Type: application/json');
        echo json_encode(array('success' => 'false 2'));
        exit;
    }
?>