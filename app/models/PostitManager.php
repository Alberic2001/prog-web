<?php

define('POSTIT', 'postit');

function createPostit($postit)
{
    $conn = connect();
    $query = 'INSERT INTO ' . POSTIT . '(`title`, `content`, `date`, `user_id`) VALUES (?,?,?,?);';
    $statement = $conn->prepare($query);
    if ($statement->bind_param('sssi', ...[$postit['title'], $postit['content'], $postit['date'], $postit['user_id']])) {
        if ($statement->execute()) {
            $postit['id'] = $statement->insert_id;
            mysqli_close($conn);
            return array('success' => true, 'postit_id' => $postit['id']);
        }
        return message_builder(false, 'database', 'Something went wrong ! (' . $statement->errno . ') ' . $statement->error);
    }
    return message_builder(false, "database", "Can't bind params (" . $statement->errno . ") " . $statement->error);
}

function read_all_postit()
{
    $conn = connect();
    $query = 'SELECT * FROM ' . POSTIT . ';';
    $statement = $conn->prepare($query);
    if ($statement->execute()) {
        $result = $statement->get_result();
        $num = $result ? $result->num_rows : 0;
        if ($num > 0) {
            $postit_array = array();
            while ($row = $result->fetch_assoc()) {
                extract($row);
                $postit_item = postit_builder($title, $content, $date, $user_id, $id);
                array_push($postit_array, $postit_item);
            }
            mysqli_close($conn);
            $postit_array['success'] = true;
            return $postit_array;
        }
        return message_builder(false, 'database', 'There is nothing in the table !!');
    }
}

function read_one_postit($postitId)
{
    $conn = connect();
    $query = 'SELECT id, title, content, date, user_id FROM ' . POSTIT . ' WHERE id = ?';
    $statement = $conn->prepare($query);
    if ($statement->bind_param('s', $postitId)) {
        if ($statement->execute()) {
            $result = $statement->get_result();
            $num = $result ? $result->num_rows : 0;
            if ($num > 0) {
                extract($result->fetch_assoc());
                $postit = postit_builder($title, $content, $date, $user_id, $id);
                $postit['success'] = true;
                return $postit;
            }
            mysqli_close($conn);
            return message_builder(false, 'database', 'There is no result :(');
        }
        return message_builder(false, "database", "Can't bind params (" . $statement->errno . ") " . $statement->error);
    }
    return message_builder(false, "database", "Can't prepare the query (" . $statement->errno . ") " . $statement->error);
}

function read_all_for_one_user_postit($userId)
{
    $conn = connect();
    $query = 'SELECT id, title, content, date, user_id FROM ' . POSTIT . ' WHERE user_id = ? ORDER BY date DESC';
    $statement = $conn->prepare($query);
    if ($statement->bind_param('s', $userId)) {
        if ($statement->execute()) {
            $result = $statement->get_result();
            $num = $result ? $result->num_rows : 0;
            if ($num > 0) {
                $postit_array = array();
                while ($row = $result->fetch_assoc()) {
                    extract($row);
                    $postit_item = postit_builder($title, $content, $date, $user_id, $id);
                    array_push($postit_array, $postit_item);
                }
                mysqli_close($conn);
                $postit_array['success'] = true;
                return $postit_array;
            }
        }
        return message_builder(false, "database", "Can't bind params (" . $statement->errno . ") " . $statement->error);
    }
    return message_builder(false, "database", "Can't prepare the query (" . $statement->errno . ") " . $statement->error);
}

function update_postit($postit)
{
    $conn = connect();
    $query = 'UPDATE ' . POSTIT . ' SET `title`=?,`content`=?,`user_id`=? WHERE id = ?;';
    $statement = $conn->prepare($query);
    if ($statement->bind_param('ssii', ...[$postit['title'], $postit['content'], $postit['user_id'], $postit['id']])) {
        if ($statement->execute()) {
            $postit['id'] = $statement->insert_id;
            mysqli_close($conn);
            return array('success' => true);
        }
        return message_builder(false, "database", "Something went wrong ! (" . $statement->errno . ") " . $statement->error);
    }
    return message_builder(false, "database", "Can't bind params (" . $statement->errno . ") " . $statement->error);
}

function delete_postit($postit_id)
{
    $conn = connect();
    $query = 'DELETE FROM ' . POSTIT . ' WHERE id = ?;';
    $statement = $conn->prepare($query);
    if ($statement->bind_param('i', $postit_id)) {
        if ($statement->execute()) {
            mysqli_close($conn);
            return array('success' => true);
        }
        return message_builder(false, "database", "Something went wrong ! (" . $statement->errno . ") " . $statement->error);
    }
    return message_builder(false, "database", "Can't bind params (" . $statement->errno . ") " . $statement->error);
}

function get_username_for_postit($postitId){
    $conn = connect();
    $query = 'SELECT u.email, u.surname, u.lastname FROM user u INNER JOIN postit p ON u.id = p.user_id WHERE p.id = ?';
    $statement = $conn->prepare($query);
    if ($statement->bind_param('i', $postitId)) {
        if ($statement->execute()) {
            $result = $statement->get_result();
            $num = $result ? $result->num_rows : 0;
            if ($num > 0) {
                extract($result->fetch_assoc());
                mysqli_close($conn);
                return array('success' => true, 'email' => $email, 'name' => $surname . ' ' . $lastname);
            }
            return message_builder(false, "database", "There is no result :(");
        }
        return message_builder(false, "database", "Can't bind params (" . $statement->errno . ") " . $statement->error);
    }
    return message_builder(false, "database", "Can't prepare the query (" . $statement->errno . ") " . $statement->error);
}