<?php

define('USER', 'user');

function email_exists($conn, $user)
{
    $query = 'SELECT * FROM ' . USER . ' where email=?';
    $statement = $conn->prepare($query);
    if ($statement->bind_param('s', $user['email'])) {
        $statement->execute();
        $log = $statement->get_result()->num_rows > 0;
        return $log;
    }
}

function sign_up($user)
{
    $conn = connect();
    if (!email_exists($conn, $user)) {
        $query = 'INSERT INTO ' . USER . '(`email`, `password`, `surname`, `lastname`, `birth_date`) VALUES (?,?,?,?,?);';
        $statement = $conn->prepare($query);
        // $user->setBirth_date($user->getBirth_date());
        $hashed_password = password_hash($user['password'], PASSWORD_BCRYPT);
        if ($statement->bind_param('sssss', $user['email'], $hashed_password, $user['surname'], $user['lastname'], $user['birth_date'])) {
            if ($statement->execute()) {
                $user['id'] = $statement->insert_id;
                return array('success' => true, 'email' => $user['email'], 'id' => $user['id'] , 'surname' => $user['surname'], 'lastname' => $user['lastname']);
            }
            return message_builder(false, 'database' ,'database error');
        }
        return message_builder(false, 'database', 'database error');
    }
    return message_builder(false, 'email' , 'Email already exists');
}

function sign_in($email, $input_password)
{
    $conn = connect();
    $query = 'SELECT * FROM ' . USER . ' WHERE email = ?';
    $statement = $conn->prepare($query);
    if ($statement->bind_param('s', $email)) {
        if ($statement->execute()) {
            $result = $statement->get_result();
            $num = $result ? $result->num_rows : 0;
            if ($num > 0) {
                extract($result->fetch_assoc());
                if (password_verify($input_password, $password)) {
                    return array('success' => true, 'id'=>$id,'email' => $email ,'surname' => $surname, 'lastname' => $lastname);
                }
                mysqli_close($conn);
                return message_builder(false,  'password' ,'Password incorrect :(');
            }
            return message_builder(false, 'email', 'Email does not exist :(');
        }
        return message_builder(false, 'database' , 'database error');
    }
    return message_builder(false, 'database' , 'database error');
}

function read_all_user()
{
    $conn = connect();
    $query = 'SELECT * FROM ' . USER . ';';
    $statement = $conn->prepare($query);
    if ($statement->execute()) {
        $result = $statement->get_result();
        $num = $result ? $result->num_rows : 0;
        if ($num > 0) {
            $user_array = array();
            while ($row = $result->fetch_assoc()) {
                extract($row);
                $user = user_builder($email, '', $surname, $lastname, $birth_date, $id);
                array_push($user_array, $user);
            }
            mysqli_close($conn);
            $user_array['success'] = true;
            return $user_array;
        }
        return message_builder(false, 'sql' , 'There is nothing in the table !!');
    }
}

function get_email_for_userId($userId)
{
    $conn = connect();
    $query = 'SELECT email FROM user WHERE id = ?';
    $statement = $conn->prepare($query);
    if ($statement->bind_param('i', $userId)) {
        if ($statement->execute()) {
            $result = $statement->get_result();
            $num = $result ? $result->num_rows : 0;
            if ($num > 0) {
                extract($result->fetch_assoc());
                mysqli_close($conn);
                return array('success' => true, 'email' => $email);
            }
            return message_builder(false, 'sql' , 'There is no result :(');
        }
        return message_builder(false, 'database', "Can't bind params (" . $statement->errno . ") " . $statement->error);
    }
    return message_builder(false, 'database', "Can't prepare the query (" . $statement->errno . ") " . $statement->error);
}

function user_info($userId)
{
    $conn = connect();
    $query = 'SELECT * FROM user WHERE id = ?';
    $statement = $conn->prepare($query);
    if ($statement->bind_param('i', $userId)) {
        if ($statement->execute()) {
            $result = $statement->get_result();
            $num = $result ? $result->num_rows : 0;
            if ($num > 0) {
                extract($result->fetch_assoc());
                mysqli_close($conn);
                return array('success' => true, 'email' => $email, 'surname' =>  $surname, 'lastname' => $lastname, 'birth_date' =>  $birth_date);
            }
            return message_builder(false, 'sql' , 'There is no result :(');
        }
        return message_builder(false, 'database', "Can't bind params (" . $statement->errno . ") " . $statement->error);
    }
    return message_builder(false, 'database', "Can't prepare the query (" . $statement->errno . ") " . $statement->error);
}
