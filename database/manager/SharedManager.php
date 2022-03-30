<?php

$path = realpath(dirname(__DIR__) . '/.');
require_once($path.'/config/Database.php');
require_once($path.'/models/helper.php');
define('SHARED', 'shared');

function createShared($shared)
{
    $conn = connect();
    $query = 'INSERT INTO ' . SHARED . '(`user_id`, `postit_id`) VALUES (?,?);';
    $statement = $conn->prepare($query);
    if ($statement->bind_param('ii', $shared['user_id'], $shared['postit_id'])) {
        if ($statement->execute()) {
            $shared['id'] = $statement->insert_id;
            mysqli_close($conn);
            return true;
        }
        return "Something went wrong ! (" . $statement->errno . ") " . $statement->error;
    }
    return "Can't bind params (" . $statement->errno . ") " . $statement->error;
}

function read_all_shared()
{
    $conn = connect();
    $query = 'SELECT * FROM ' . SHARED . ';';
    $statement = $conn->prepare($query);
    if ($statement->execute()) {
        $result = $statement->get_result();
        $num = $result ? $result->num_rows : 0;
        if ($num > 0) {
            $shared_array = array();
            while ($row = $result->fetch_assoc()) {
                extract($row);
                $shared_item = shared_builder($postit_id, $user_id, $id);
                array_push($shared_array, $shared_item);
            }
            mysqli_close($conn);
            return $shared_array;
        }
        return 'There is nothing in the table !!';
    }
}

function read_one_shared($shared_id)
{
    $conn = connect();
    $query = 'SELECT id, user_id, postit_id FROM ' . SHARED . ' WHERE id = ?';
    $statement = $conn->prepare($query);
    if ($statement->bind_param('i', $shared_id)) {
        if ($statement->execute()) {
            $result = $statement->get_result();
            $num = $result ? $result->num_rows : 0;
            if ($num > 0) {
                extract($result->fetch_assoc());
                mysqli_close($conn);
                return shared_builder($postit_id, $user_id, $id);
            }
            return "There is no result :(";
        }
        return "Can't bind params (" . $statement->errno . ") " . $statement->error;
    }
    return "Can't prepare the query (" . $statement->errno . ") " . $statement->error;
}



function read_all_for_one_user_shared($userId)
{
    $conn = connect();
    $query = 'SELECT id, user_id, postit_id FROM ' . SHARED . ' WHERE user_id = ?';
    $statement = $conn->prepare($query);

    if ($statement->bind_param('s', $userId)) {
        if ($statement->execute()) {
            $result = $statement->get_result();
            $num = $result ? $result->num_rows : 0;
            if ($num > 0) {
                $shared_array = array();
                while ($row = $result->fetch_assoc()) {
                    extract($row);
                    $shared_item = shared_builder($postit_id, $user_id, $id);
                    array_push($shared_array, $shared_item);
                }
                mysqli_close($conn);
                return $shared_array;
            }
        }
        return "Can't bind params (" . $statement->errno . ") " . $statement->error;
    }
    return "Can't prepare the query (" . $statement->errno . ") " . $statement->error;
}



function update_shared($shared)
{
    $conn = connect();
    $query = 'UPDATE ' . SHARED . ' SET `user_id`=?,`postit_id`=? WHERE id = ?;';
    $statement = $conn->prepare($query);
    if ($statement->bind_param('iii', $shared['user_id'], $shared['postit_id'], $shared['id'])) {
        if ($statement->execute()) {
            $shared['id'] = $statement->insert_id;
            mysqli_close($conn);
            return true;
        }
        return "Something went wrong ! (" . $statement->errno . ") " . $statement->error;
    }
    return "Can't bind params (" . $statement->errno . ") " . $statement->error;
}

function delete_shared($id)
{
    $conn = connect();
    $query = 'DELETE FROM ' . SHARED . ' WHERE id = ?;';
    $statement = $conn->prepare($query);
    if ($statement->bind_param('i', $id)) {
        if ($statement->execute()) {
            mysqli_close($conn);
            return true;
        }
        return "Something went wrong ! (" . $statement->errno . ") " . $statement->error;
    }
    return "Can't bind params (" . $statement->errno . ") " . $statement->error;
}

function read_all_postits($postitId){
    $conn = connect();
    $query = 'SELECT id, user_id, postit_id FROM ' . SHARED . ' WHERE postit_id = ?';
    $statement = $conn->prepare($query);

    if ($statement->bind_param('s', $postitId)) {
        if ($statement->execute()) {
            $result = $statement->get_result();
            $num = $result ? $result->num_rows : 0;
            if ($num > 0) {
                $shared_array = array();
                while ($row = $result->fetch_assoc()) {
                    extract($row);
                    $shared_item = shared_builder($postit_id, $user_id, $id);
                    array_push($shared_array, $shared_item);
                }
                mysqli_close($conn);
                return $shared_array;
            }
        }
        return "Can't bind params (" . $statement->errno . ") " . $statement->error;
    }
    return "Can't prepare the query (" . $statement->errno . ") " . $statement->error;
}